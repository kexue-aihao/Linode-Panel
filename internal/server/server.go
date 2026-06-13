package server

import (
	"crypto/hmac"
	"crypto/rand"
	"crypto/sha256"
	"embed"
	"encoding/base64"
	"encoding/json"
	"errors"
	"io/fs"
	"log/slog"
	"net/http"
	"strconv"
	"strings"
	"time"

	"linode-panel/internal/auth"
	"linode-panel/internal/linode"
	"linode-panel/internal/store"
)

//go:embed static
var staticFiles embed.FS

type Server struct {
	store  *store.Store
	logger *slog.Logger
}

func New(st *store.Store, logger *slog.Logger) *Server {
	return &Server{store: st, logger: logger}
}

func (s *Server) Routes() http.Handler {
	mux := http.NewServeMux()
	mux.HandleFunc("/api/health", s.handleHealth)
	mux.HandleFunc("/api/setup", s.handleSetup)
	mux.HandleFunc("/api/login", s.handleLogin)
	mux.HandleFunc("/api/logout", s.handleLogout)
	mux.HandleFunc("/api/session", s.handleSession)
	mux.HandleFunc("/api/settings", s.requireAuth(s.handleSettings))
	mux.HandleFunc("/api/linode/test", s.requireAuth(s.handleLinodeTest))
	mux.HandleFunc("/api/linode/catalog", s.requireAuth(s.handleCatalog))
	mux.HandleFunc("/api/linode/instances", s.requireAuth(s.handleInstances))
	mux.HandleFunc("/api/linode/instances/", s.requireAuth(s.handleInstanceAction))
	mux.HandleFunc("/api/linode/firewalls", s.requireAuth(s.handleFirewalls))
	mux.HandleFunc("/api/linode/events", s.requireAuth(s.handleEvents))
	mux.Handle("/", s.staticHandler())
	return s.recover(s.withSecurityHeaders(mux))
}

func (s *Server) handleHealth(w http.ResponseWriter, r *http.Request) {
	writeJSON(w, http.StatusOK, map[string]any{
		"ok":         true,
		"configured": s.store.IsConfigured(),
		"time":       time.Now().UTC().Format(time.RFC3339),
	})
}

func (s *Server) handleSetup(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodPost {
		methodNotAllowed(w)
		return
	}
	if s.store.IsConfigured() {
		writeError(w, http.StatusConflict, "面板已经初始化")
		return
	}

	var req struct {
		AdminUser   string `json:"admin_user"`
		Password    string `json:"password"`
		LinodeToken string `json:"linode_token"`
	}
	if !readJSON(w, r, &req) {
		return
	}
	req.AdminUser = strings.TrimSpace(req.AdminUser)
	req.LinodeToken = strings.TrimSpace(req.LinodeToken)
	if req.AdminUser == "" {
		req.AdminUser = "admin"
	}
	if len(req.Password) < 10 {
		writeError(w, http.StatusBadRequest, "管理员密码至少需要 10 位")
		return
	}
	hash, err := auth.HashPassword(req.Password)
	if err != nil {
		writeError(w, http.StatusInternalServerError, "无法生成密码哈希")
		return
	}
	cfg, err := s.store.Setup(req.AdminUser, hash, req.LinodeToken)
	if err != nil {
		writeError(w, http.StatusBadRequest, err.Error())
		return
	}
	s.setSession(w, cfg.AdminUser, cfg.SessionSecret)
	writeJSON(w, http.StatusOK, s.publicConfig(cfg))
}

func (s *Server) handleLogin(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodPost {
		methodNotAllowed(w)
		return
	}
	if !s.store.IsConfigured() {
		writeError(w, http.StatusPreconditionRequired, "面板尚未初始化")
		return
	}
	var req struct {
		AdminUser string `json:"admin_user"`
		Password  string `json:"password"`
	}
	if !readJSON(w, r, &req) {
		return
	}
	cfg := s.store.Snapshot()
	if subtleUserMismatch(req.AdminUser, cfg.AdminUser) || !auth.VerifyPassword(req.Password, cfg.PasswordHash) {
		writeError(w, http.StatusUnauthorized, "用户名或密码不正确")
		return
	}
	s.setSession(w, cfg.AdminUser, cfg.SessionSecret)
	writeJSON(w, http.StatusOK, s.publicConfig(cfg))
}

func (s *Server) handleLogout(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodPost {
		methodNotAllowed(w)
		return
	}
	http.SetCookie(w, &http.Cookie{
		Name:     "linode_panel_session",
		Value:    "",
		Path:     "/",
		MaxAge:   -1,
		HttpOnly: true,
		SameSite: http.SameSiteLaxMode,
	})
	writeJSON(w, http.StatusOK, map[string]any{"ok": true})
}

func (s *Server) handleSession(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		methodNotAllowed(w)
		return
	}
	cfg := s.store.Snapshot()
	writeJSON(w, http.StatusOK, map[string]any{
		"configured":    s.store.IsConfigured(),
		"authenticated": s.isAuthenticated(r),
		"settings":      s.publicConfig(cfg),
	})
}

func (s *Server) handleSettings(w http.ResponseWriter, r *http.Request) {
	switch r.Method {
	case http.MethodGet:
		writeJSON(w, http.StatusOK, s.publicConfig(s.store.Snapshot()))
	case http.MethodPut:
		var req struct {
			AdminUser        *string `json:"admin_user"`
			Password         *string `json:"password"`
			LinodeToken      *string `json:"linode_token"`
			ClearLinodeToken bool    `json:"clear_linode_token"`
			ProxyURL         *string `json:"proxy_url"`
			ClearProxyURL    bool    `json:"clear_proxy_url"`
		}
		if !readJSON(w, r, &req) {
			return
		}
		update := store.SettingsUpdate{ClearLinodeToken: req.ClearLinodeToken, ClearProxyURL: req.ClearProxyURL, EnsureSecret: true}
		if req.AdminUser != nil {
			user := strings.TrimSpace(*req.AdminUser)
			if user == "" {
				writeError(w, http.StatusBadRequest, "管理员用户名不能为空")
				return
			}
			update.AdminUser = &user
		}
		if req.Password != nil && *req.Password != "" {
			if len(*req.Password) < 10 {
				writeError(w, http.StatusBadRequest, "管理员密码至少需要 10 位")
				return
			}
			hash, err := auth.HashPassword(*req.Password)
			if err != nil {
				writeError(w, http.StatusInternalServerError, "无法生成密码哈希")
				return
			}
			update.PasswordHash = &hash
		}
		if req.LinodeToken != nil {
			token := strings.TrimSpace(*req.LinodeToken)
			update.LinodeToken = &token
		}
		if req.ProxyURL != nil {
			proxyURL := strings.TrimSpace(*req.ProxyURL)
			update.ProxyURL = &proxyURL
		}
		cfg, err := s.store.UpdateSettings(update)
		if err != nil {
			writeError(w, http.StatusInternalServerError, "保存设置失败")
			return
		}
		writeJSON(w, http.StatusOK, s.publicConfig(cfg))
	default:
		methodNotAllowed(w)
	}
}

func (s *Server) handleLinodeTest(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodPost {
		methodNotAllowed(w)
		return
	}
	client, ok := s.linodeClient(w)
	if !ok {
		return
	}
	raw, err := client.DoJSON(r.Context(), http.MethodGet, "/v4/profile", nil)
	if err != nil {
		writeLinodeError(w, err)
		return
	}
	writeRawJSON(w, http.StatusOK, raw)
}

func (s *Server) handleCatalog(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		methodNotAllowed(w)
		return
	}
	client, ok := s.linodeClient(w)
	if !ok {
		return
	}

	type result struct {
		key string
		raw json.RawMessage
		err error
	}
	ctx := r.Context()
	jobs := []struct {
		key  string
		path string
	}{
		{"regions", "/v4/regions"},
		{"types", "/v4/linode/types"},
		{"images", `/v4/images?is_public=true`},
		{"stackscripts", `/v4/linode/stackscripts?is_public=true`},
		{"firewalls", "/v4/networking/firewalls"},
	}

	ch := make(chan result, len(jobs))
	for _, job := range jobs {
		go func(key, path string) {
			raw, err := client.ListAll(ctx, path)
			ch <- result{key: key, raw: raw, err: err}
		}(job.key, job.path)
	}

	payload := map[string]json.RawMessage{}
	for range jobs {
		res := <-ch
		if res.err != nil {
			writeLinodeError(w, res.err)
			return
		}
		payload[res.key] = res.raw
	}
	writeJSON(w, http.StatusOK, payload)
}

func (s *Server) handleInstances(w http.ResponseWriter, r *http.Request) {
	client, ok := s.linodeClient(w)
	if !ok {
		return
	}
	switch r.Method {
	case http.MethodGet:
		raw, err := client.ListAll(r.Context(), "/v4/linode/instances")
		if err != nil {
			writeLinodeError(w, err)
			return
		}
		writeRawJSON(w, http.StatusOK, raw)
	case http.MethodPost:
		var req map[string]any
		if !readJSON(w, r, &req) {
			return
		}
		raw, err := client.DoJSON(r.Context(), http.MethodPost, "/v4/linode/instances", req)
		if err != nil {
			writeLinodeError(w, err)
			return
		}
		writeRawJSON(w, http.StatusAccepted, raw)
	default:
		methodNotAllowed(w)
	}
}

func (s *Server) handleInstanceAction(w http.ResponseWriter, r *http.Request) {
	client, ok := s.linodeClient(w)
	if !ok {
		return
	}

	rest := strings.TrimPrefix(r.URL.Path, "/api/linode/instances/")
	parts := strings.Split(strings.Trim(rest, "/"), "/")
	if len(parts) == 0 || parts[0] == "" {
		writeError(w, http.StatusNotFound, "实例路径不正确")
		return
	}
	linodeID, err := strconv.Atoi(parts[0])
	if err != nil || linodeID <= 0 {
		writeError(w, http.StatusBadRequest, "实例 ID 不正确")
		return
	}
	base := "/v4/linode/instances/" + strconv.Itoa(linodeID)

	if len(parts) == 1 {
		switch r.Method {
		case http.MethodGet:
			raw, err := client.DoJSON(r.Context(), http.MethodGet, base, nil)
			if err != nil {
				writeLinodeError(w, err)
				return
			}
			writeRawJSON(w, http.StatusOK, raw)
		case http.MethodPut:
			var req map[string]any
			if !readJSON(w, r, &req) {
				return
			}
			raw, err := client.DoJSON(r.Context(), http.MethodPut, base, req)
			if err != nil {
				writeLinodeError(w, err)
				return
			}
			writeRawJSON(w, http.StatusOK, raw)
		case http.MethodDelete:
			raw, err := client.DoJSON(r.Context(), http.MethodDelete, base, nil)
			if err != nil {
				writeLinodeError(w, err)
				return
			}
			writeRawJSON(w, http.StatusOK, raw)
		default:
			methodNotAllowed(w)
		}
		return
	}

	action := parts[1]
	switch action {
	case "boot", "reboot", "shutdown", "rebuild", "rescue", "resize", "migrate", "password":
		if r.Method != http.MethodPost {
			methodNotAllowed(w)
			return
		}
		var body any
		if r.ContentLength != 0 {
			var req map[string]any
			if !readJSON(w, r, &req) {
				return
			}
			body = req
		}
		raw, err := client.DoJSON(r.Context(), http.MethodPost, base+"/"+action, body)
		if err != nil {
			writeLinodeError(w, err)
			return
		}
		writeRawJSON(w, http.StatusAccepted, raw)
	case "ips":
		if r.Method != http.MethodGet {
			methodNotAllowed(w)
			return
		}
		raw, err := client.DoJSON(r.Context(), http.MethodGet, base+"/ips", nil)
		if err != nil {
			writeLinodeError(w, err)
			return
		}
		writeRawJSON(w, http.StatusOK, raw)
	case "stats":
		if r.Method != http.MethodGet {
			methodNotAllowed(w)
			return
		}
		raw, err := client.DoJSON(r.Context(), http.MethodGet, base+"/stats", nil)
		if err != nil {
			writeLinodeError(w, err)
			return
		}
		writeRawJSON(w, http.StatusOK, raw)
	default:
		writeError(w, http.StatusNotFound, "不支持的实例操作")
	}
}

func (s *Server) handleFirewalls(w http.ResponseWriter, r *http.Request) {
	client, ok := s.linodeClient(w)
	if !ok {
		return
	}
	switch r.Method {
	case http.MethodGet:
		raw, err := client.ListAll(r.Context(), "/v4/networking/firewalls")
		if err != nil {
			writeLinodeError(w, err)
			return
		}
		writeRawJSON(w, http.StatusOK, raw)
	case http.MethodPost:
		var req map[string]any
		if !readJSON(w, r, &req) {
			return
		}
		raw, err := client.DoJSON(r.Context(), http.MethodPost, "/v4/networking/firewalls", req)
		if err != nil {
			writeLinodeError(w, err)
			return
		}
		writeRawJSON(w, http.StatusCreated, raw)
	default:
		methodNotAllowed(w)
	}
}

func (s *Server) handleEvents(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		methodNotAllowed(w)
		return
	}
	client, ok := s.linodeClient(w)
	if !ok {
		return
	}
	raw, err := client.ListAll(r.Context(), "/v4/account/events")
	if err != nil {
		writeLinodeError(w, err)
		return
	}
	writeRawJSON(w, http.StatusOK, raw)
}

func (s *Server) linodeClient(w http.ResponseWriter) (*linode.Client, bool) {
	cfg := s.store.Snapshot()
	if cfg.LinodeToken == "" {
		writeError(w, http.StatusPreconditionRequired, "请先在设置中保存 Linode Personal Access Token")
		return nil, false
	}
	client, err := linode.New(cfg.LinodeToken, cfg.ProxyURL)
	if err != nil {
		writeError(w, http.StatusBadRequest, err.Error())
		return nil, false
	}
	return client, true
}

func (s *Server) publicConfig(cfg store.Config) map[string]any {
	return map[string]any{
		"configured":       cfg.AdminUser != "" && cfg.PasswordHash != "",
		"admin_user":       cfg.AdminUser,
		"has_linode_token": cfg.LinodeToken != "",
		"proxy_url":        cfg.ProxyURL,
		"created_at":       cfg.CreatedAt,
		"updated_at":       cfg.UpdatedAt,
	}
}

func (s *Server) setSession(w http.ResponseWriter, username, secret string) {
	issued := strconv.FormatInt(time.Now().Unix(), 10)
	nonce := make([]byte, 18)
	if _, err := rand.Read(nonce); err != nil {
		panic(err)
	}
	payload := username + "|" + issued + "|" + base64.RawURLEncoding.EncodeToString(nonce)
	sig := sign(payload, secret)
	http.SetCookie(w, &http.Cookie{
		Name:     "linode_panel_session",
		Value:    base64.RawURLEncoding.EncodeToString([]byte(payload + "|" + sig)),
		Path:     "/",
		MaxAge:   60 * 60 * 12,
		HttpOnly: true,
		SameSite: http.SameSiteLaxMode,
	})
}

func (s *Server) requireAuth(next http.HandlerFunc) http.HandlerFunc {
	return func(w http.ResponseWriter, r *http.Request) {
		if !s.store.IsConfigured() {
			writeError(w, http.StatusPreconditionRequired, "面板尚未初始化")
			return
		}
		if !s.isAuthenticated(r) {
			writeError(w, http.StatusUnauthorized, "请先登录")
			return
		}
		next(w, r)
	}
}

func (s *Server) isAuthenticated(r *http.Request) bool {
	cfg := s.store.Snapshot()
	cookie, err := r.Cookie("linode_panel_session")
	if err != nil || cookie.Value == "" || cfg.SessionSecret == "" {
		return false
	}
	raw, err := base64.RawURLEncoding.DecodeString(cookie.Value)
	if err != nil {
		return false
	}
	parts := strings.Split(string(raw), "|")
	if len(parts) != 4 {
		return false
	}
	payload := strings.Join(parts[:3], "|")
	if !hmac.Equal([]byte(sign(payload, cfg.SessionSecret)), []byte(parts[3])) {
		return false
	}
	if parts[0] != cfg.AdminUser {
		return false
	}
	issued, err := strconv.ParseInt(parts[1], 10, 64)
	if err != nil {
		return false
	}
	return time.Since(time.Unix(issued, 0)) <= 12*time.Hour
}

func sign(payload, secret string) string {
	h := hmac.New(sha256.New, []byte(secret))
	h.Write([]byte(payload))
	return base64.RawURLEncoding.EncodeToString(h.Sum(nil))
}

func subtleUserMismatch(a, b string) bool {
	a = strings.TrimSpace(a)
	b = strings.TrimSpace(b)
	return !strings.EqualFold(a, b)
}

func (s *Server) staticHandler() http.Handler {
	sub, err := fs.Sub(staticFiles, "static")
	if err != nil {
		panic(err)
	}
	files := http.FileServer(http.FS(sub))
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		if strings.HasPrefix(r.URL.Path, "/api/") {
			writeError(w, http.StatusNotFound, "API 不存在")
			return
		}
		path := strings.TrimPrefix(r.URL.Path, "/")
		if path == "" {
			path = "index.html"
		}
		if _, err := sub.Open(path); err != nil {
			r.URL.Path = "/"
		}
		files.ServeHTTP(w, r)
	})
}

func (s *Server) withSecurityHeaders(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		w.Header().Set("X-Content-Type-Options", "nosniff")
		w.Header().Set("X-Frame-Options", "SAMEORIGIN")
		w.Header().Set("Referrer-Policy", "same-origin")
		next.ServeHTTP(w, r)
	})
}

func (s *Server) recover(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		defer func() {
			if rec := recover(); rec != nil {
				s.logger.Error("panic", "value", rec, "path", r.URL.Path)
				writeError(w, http.StatusInternalServerError, "服务内部错误")
			}
		}()
		next.ServeHTTP(w, r)
	})
}

func readJSON(w http.ResponseWriter, r *http.Request, out any) bool {
	defer r.Body.Close()
	dec := json.NewDecoder(http.MaxBytesReader(w, r.Body, 2<<20))
	dec.DisallowUnknownFields()
	if err := dec.Decode(out); err != nil {
		writeError(w, http.StatusBadRequest, "JSON 请求体不正确")
		return false
	}
	return true
}

func writeJSON(w http.ResponseWriter, status int, payload any) {
	data, err := json.Marshal(payload)
	if err != nil {
		writeError(w, http.StatusInternalServerError, "JSON 序列化失败")
		return
	}
	writeRawJSON(w, status, data)
}

func writeRawJSON(w http.ResponseWriter, status int, data []byte) {
	w.Header().Set("Content-Type", "application/json; charset=utf-8")
	w.WriteHeader(status)
	_, _ = w.Write(data)
}

func writeError(w http.ResponseWriter, status int, message string) {
	writeJSON(w, status, map[string]any{
		"errors": []map[string]string{{"reason": message}},
	})
}

func writeLinodeError(w http.ResponseWriter, err error) {
	var apiErr *linode.Error
	if errors.As(err, &apiErr) {
		status := apiErr.StatusCode
		if status == 0 {
			status = http.StatusBadGateway
		}
		if len(apiErr.Errors) > 0 {
			writeJSON(w, status, map[string]any{"errors": apiErr.Errors})
			return
		}
		writeError(w, status, "Linode API 请求失败")
		return
	}
	writeError(w, http.StatusBadGateway, err.Error())
}

func methodNotAllowed(w http.ResponseWriter) {
	writeError(w, http.StatusMethodNotAllowed, "请求方法不支持")
}
