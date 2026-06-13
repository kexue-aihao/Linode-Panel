package linode

import (
	"bytes"
	"context"
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"net/url"
	"strings"
	"time"
)

const DefaultBaseURL = "https://api.linode.com"

type Client struct {
	baseURL string
	token   string
	http    *http.Client
}

type Error struct {
	StatusCode int           `json:"status_code"`
	Errors     []ErrorObject `json:"errors"`
	Body       string        `json:"body,omitempty"`
}

type ErrorObject struct {
	Field  string `json:"field"`
	Reason string `json:"reason"`
}

func (e *Error) Error() string {
	if len(e.Errors) == 0 {
		return fmt.Sprintf("linode api returned status %d", e.StatusCode)
	}
	parts := make([]string, 0, len(e.Errors))
	for _, item := range e.Errors {
		if item.Field == "" {
			parts = append(parts, item.Reason)
			continue
		}
		parts = append(parts, item.Field+": "+item.Reason)
	}
	return strings.Join(parts, "; ")
}

type pageEnvelope struct {
	Data    []json.RawMessage `json:"data"`
	Page    int               `json:"page"`
	Pages   int               `json:"pages"`
	Results int               `json:"results"`
}

func New(token, proxyURL string) (*Client, error) {
	transport := http.DefaultTransport.(*http.Transport).Clone()
	if proxyURL != "" {
		parsed, err := url.Parse(proxyURL)
		if err != nil {
			return nil, fmt.Errorf("invalid proxy url: %w", err)
		}
		transport.Proxy = http.ProxyURL(parsed)
	}
	return &Client{
		baseURL: DefaultBaseURL,
		token:   token,
		http: &http.Client{
			Timeout:   60 * time.Second,
			Transport: transport,
		},
	}, nil
}

func (c *Client) DoJSON(ctx context.Context, method, path string, in any) (json.RawMessage, error) {
	var body io.Reader
	if in != nil {
		data, err := json.Marshal(in)
		if err != nil {
			return nil, err
		}
		body = bytes.NewReader(data)
	}

	req, err := http.NewRequestWithContext(ctx, method, c.absoluteURL(path), body)
	if err != nil {
		return nil, err
	}
	req.Header.Set("Accept", "application/json")
	if in != nil {
		req.Header.Set("Content-Type", "application/json")
	}
	if c.token != "" {
		req.Header.Set("Authorization", "Bearer "+c.token)
	}

	resp, err := c.http.Do(req)
	if err != nil {
		return nil, err
	}
	defer resp.Body.Close()

	data, err := io.ReadAll(io.LimitReader(resp.Body, 16<<20))
	if err != nil {
		return nil, err
	}
	if resp.StatusCode >= 400 {
		apiErr := &Error{StatusCode: resp.StatusCode, Body: string(data)}
		var parsed struct {
			Errors []ErrorObject `json:"errors"`
		}
		if json.Unmarshal(data, &parsed) == nil {
			apiErr.Errors = parsed.Errors
		}
		return nil, apiErr
	}
	if len(bytes.TrimSpace(data)) == 0 {
		return json.RawMessage(`{}`), nil
	}
	return json.RawMessage(data), nil
}

func (c *Client) ListAll(ctx context.Context, path string) (json.RawMessage, error) {
	page := 1
	pages := 1
	results := 0
	all := make([]json.RawMessage, 0)

	for page <= pages {
		raw, err := c.DoJSON(ctx, http.MethodGet, addPage(path, page), nil)
		if err != nil {
			return nil, err
		}
		var envelope pageEnvelope
		if err := json.Unmarshal(raw, &envelope); err != nil {
			return nil, err
		}
		all = append(all, envelope.Data...)
		if envelope.Page > 0 {
			page = envelope.Page + 1
		} else {
			page++
		}
		if envelope.Pages > 0 {
			pages = envelope.Pages
		}
		results = envelope.Results
		if envelope.Pages == 0 {
			break
		}
	}

	out := struct {
		Data    []json.RawMessage `json:"data"`
		Page    int               `json:"page"`
		Pages   int               `json:"pages"`
		Results int               `json:"results"`
	}{
		Data:    all,
		Page:    1,
		Pages:   1,
		Results: results,
	}
	if results == 0 {
		out.Results = len(all)
	}
	return json.Marshal(out)
}

func (c *Client) absoluteURL(path string) string {
	if strings.HasPrefix(path, "http://") || strings.HasPrefix(path, "https://") {
		return path
	}
	if !strings.HasPrefix(path, "/") {
		path = "/" + path
	}
	return c.baseURL + path
}

func addPage(path string, page int) string {
	separator := "?"
	if strings.Contains(path, "?") {
		separator = "&"
	}
	return fmt.Sprintf("%s%spage=%d&page_size=100", path, separator, page)
}
