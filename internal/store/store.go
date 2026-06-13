package store

import (
	"context"
	"crypto/rand"
	"database/sql"
	"encoding/base64"
	"errors"
	"fmt"
	"os"
	"strconv"
	"time"

	"github.com/go-sql-driver/mysql"
)

type Config struct {
	Version       int       `json:"version"`
	AdminUser     string    `json:"admin_user"`
	PasswordHash  string    `json:"password_hash"`
	LinodeToken   string    `json:"linode_token"`
	ProxyURL      string    `json:"proxy_url"`
	SessionSecret string    `json:"session_secret"`
	CreatedAt     time.Time `json:"created_at"`
	UpdatedAt     time.Time `json:"updated_at"`
}

type Store struct {
	db *sql.DB
}

func DSNFromEnv() (string, error) {
	if dsn := os.Getenv("LINODE_PANEL_DB_DSN"); dsn != "" {
		return dsn, nil
	}

	host := getenv("LINODE_PANEL_DB_HOST", "127.0.0.1")
	port := getenv("LINODE_PANEL_DB_PORT", "3306")
	name := getenv("LINODE_PANEL_DB_NAME", "linode_panel")
	user := getenv("LINODE_PANEL_DB_USER", "linode_panel")
	password := os.Getenv("LINODE_PANEL_DB_PASSWORD")

	if _, err := strconv.Atoi(port); err != nil {
		return "", fmt.Errorf("LINODE_PANEL_DB_PORT must be a number: %w", err)
	}
	if name == "" || user == "" {
		return "", errors.New("database name and user are required")
	}

	cfg := mysql.NewConfig()
	cfg.User = user
	cfg.Passwd = password
	cfg.Net = "tcp"
	cfg.Addr = host + ":" + port
	cfg.DBName = name
	cfg.ParseTime = true
	cfg.Loc = time.UTC
	cfg.Params = map[string]string{
		"charset":      "utf8mb4",
		"collation":    "utf8mb4_unicode_ci",
		"timeout":      "10s",
		"readTimeout":  "30s",
		"writeTimeout": "30s",
	}
	return cfg.FormatDSN(), nil
}

func Open(dsn string) (*Store, error) {
	if dsn == "" {
		return nil, errors.New("missing MySQL/MariaDB DSN")
	}

	db, err := sql.Open("mysql", dsn)
	if err != nil {
		return nil, err
	}
	db.SetMaxOpenConns(25)
	db.SetMaxIdleConns(10)
	db.SetConnMaxLifetime(3 * time.Minute)
	db.SetConnMaxIdleTime(90 * time.Second)

	ctx, cancel := context.WithTimeout(context.Background(), 15*time.Second)
	defer cancel()
	if err := db.PingContext(ctx); err != nil {
		_ = db.Close()
		return nil, err
	}

	st := &Store{db: db}
	if err := st.migrate(ctx); err != nil {
		_ = db.Close()
		return nil, err
	}
	if err := st.ensureSessionSecret(ctx); err != nil {
		_ = db.Close()
		return nil, err
	}
	return st, nil
}

func (s *Store) Close() error {
	return s.db.Close()
}

func (s *Store) IsConfigured() bool {
	cfg := s.Snapshot()
	return cfg.AdminUser != "" && cfg.PasswordHash != ""
}

func (s *Store) Snapshot() Config {
	ctx, cancel := context.WithTimeout(context.Background(), 5*time.Second)
	defer cancel()

	cfg, err := s.getConfig(ctx)
	if err != nil {
		return Config{}
	}
	return cfg
}

func (s *Store) Setup(adminUser, passwordHash, token string) (Config, error) {
	ctx, cancel := context.WithTimeout(context.Background(), 10*time.Second)
	defer cancel()

	current, err := s.getConfig(ctx)
	if err != nil && !errors.Is(err, sql.ErrNoRows) {
		return Config{}, err
	}
	if current.AdminUser != "" && current.PasswordHash != "" {
		return Config{}, errors.New("panel is already configured")
	}

	secret := randomSecret(48)
	_, err = s.db.ExecContext(ctx, `
		UPDATE panel_settings
		SET version = 1,
			admin_user = ?,
			password_hash = ?,
			linode_token = ?,
			proxy_url = '',
			session_secret = ?,
			updated_at = UTC_TIMESTAMP()
		WHERE id = 1`,
		adminUser, passwordHash, token, secret,
	)
	if err != nil {
		return Config{}, err
	}
	return s.getConfig(ctx)
}

type SettingsUpdate struct {
	AdminUser        *string
	PasswordHash     *string
	LinodeToken      *string
	ClearLinodeToken bool
	ProxyURL         *string
	ClearProxyURL    bool
	EnsureSecret     bool
}

func (s *Store) UpdateSettings(update SettingsUpdate) (Config, error) {
	ctx, cancel := context.WithTimeout(context.Background(), 10*time.Second)
	defer cancel()

	cfg, err := s.getConfig(ctx)
	if err != nil {
		return Config{}, err
	}

	if update.AdminUser != nil {
		cfg.AdminUser = *update.AdminUser
	}
	if update.PasswordHash != nil {
		cfg.PasswordHash = *update.PasswordHash
	}
	if update.ClearLinodeToken {
		cfg.LinodeToken = ""
	} else if update.LinodeToken != nil {
		cfg.LinodeToken = *update.LinodeToken
	}
	if update.ClearProxyURL {
		cfg.ProxyURL = ""
	} else if update.ProxyURL != nil {
		cfg.ProxyURL = *update.ProxyURL
	}
	if update.EnsureSecret && cfg.SessionSecret == "" {
		cfg.SessionSecret = randomSecret(48)
	}
	if cfg.Version == 0 {
		cfg.Version = 1
	}

	_, err = s.db.ExecContext(ctx, `
		UPDATE panel_settings
		SET version = ?,
			admin_user = ?,
			password_hash = ?,
			linode_token = ?,
			proxy_url = ?,
			session_secret = ?,
			updated_at = UTC_TIMESTAMP()
		WHERE id = 1`,
		cfg.Version, cfg.AdminUser, cfg.PasswordHash, cfg.LinodeToken, cfg.ProxyURL, cfg.SessionSecret,
	)
	if err != nil {
		return Config{}, err
	}
	return s.getConfig(ctx)
}

func (s *Store) migrate(ctx context.Context) error {
	if _, err := s.db.ExecContext(ctx, `
		CREATE TABLE IF NOT EXISTS panel_settings (
			id TINYINT UNSIGNED NOT NULL PRIMARY KEY,
			version INT UNSIGNED NOT NULL DEFAULT 1,
			admin_user VARCHAR(128) NOT NULL DEFAULT '',
			password_hash VARCHAR(255) NOT NULL DEFAULT '',
			linode_token TEXT NULL,
			proxy_url VARCHAR(512) NOT NULL DEFAULT '',
			session_secret VARCHAR(255) NOT NULL DEFAULT '',
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			INDEX idx_updated_at (updated_at)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci`); err != nil {
		return err
	}

	_, err := s.db.ExecContext(ctx, `
		INSERT INTO panel_settings (id, version, created_at, updated_at)
		VALUES (1, 1, UTC_TIMESTAMP(), UTC_TIMESTAMP())
		ON DUPLICATE KEY UPDATE id = id`)
	return err
}

func (s *Store) ensureSessionSecret(ctx context.Context) error {
	_, err := s.db.ExecContext(ctx, `
		UPDATE panel_settings
		SET session_secret = ?, updated_at = UTC_TIMESTAMP()
		WHERE id = 1 AND session_secret = ''`,
		randomSecret(48),
	)
	return err
}

func (s *Store) getConfig(ctx context.Context) (Config, error) {
	var cfg Config
	var token sql.NullString
	err := s.db.QueryRowContext(ctx, `
		SELECT version, admin_user, password_hash, linode_token, proxy_url, session_secret, created_at, updated_at
		FROM panel_settings
		WHERE id = 1`).Scan(
		&cfg.Version,
		&cfg.AdminUser,
		&cfg.PasswordHash,
		&token,
		&cfg.ProxyURL,
		&cfg.SessionSecret,
		&cfg.CreatedAt,
		&cfg.UpdatedAt,
	)
	if err != nil {
		return Config{}, err
	}
	cfg.LinodeToken = token.String
	return cfg, nil
}

func randomSecret(size int) string {
	buf := make([]byte, size)
	if _, err := rand.Read(buf); err != nil {
		panic(err)
	}
	return base64.RawURLEncoding.EncodeToString(buf)
}

func getenv(key, fallback string) string {
	value := os.Getenv(key)
	if value == "" {
		return fallback
	}
	return value
}
