package main

import (
	"context"
	"errors"
	"log/slog"
	"net/http"
	"os"
	"os/signal"
	"syscall"
	"time"

	"linode-panel/internal/server"
	"linode-panel/internal/store"
)

func main() {
	logger := slog.New(slog.NewTextHandler(os.Stdout, &slog.HandlerOptions{
		Level: slog.LevelInfo,
	}))

	addr := os.Getenv("LINODE_PANEL_ADDR")
	if addr == "" {
		addr = getenv("LINODE_PANEL_HOST", "127.0.0.1") + ":" + getenv("LINODE_PANEL_PORT", "8088")
	}

	dsn, err := store.DSNFromEnv()
	if err != nil {
		logger.Error("read database config", "error", err)
		os.Exit(1)
	}

	st, err := store.Open(dsn)
	if err != nil {
		logger.Error("open store", "error", err)
		os.Exit(1)
	}
	defer st.Close()

	app := server.New(st, logger)
	srv := &http.Server{
		Addr:              addr,
		Handler:           app.Routes(),
		ReadHeaderTimeout: 10 * time.Second,
	}

	go func() {
		logger.Info("linode panel started", "addr", addr, "database", "mysql")
		if err := srv.ListenAndServe(); err != nil && !errors.Is(err, http.ErrServerClosed) {
			logger.Error("http server stopped", "error", err)
			os.Exit(1)
		}
	}()

	stop := make(chan os.Signal, 1)
	signal.Notify(stop, os.Interrupt, syscall.SIGTERM)
	<-stop

	ctx, cancel := context.WithTimeout(context.Background(), 10*time.Second)
	defer cancel()
	if err := srv.Shutdown(ctx); err != nil {
		logger.Error("shutdown", "error", err)
		os.Exit(1)
	}
	logger.Info("linode panel stopped")
}

func getenv(key, fallback string) string {
	value := os.Getenv(key)
	if value == "" {
		return fallback
	}
	return value
}
