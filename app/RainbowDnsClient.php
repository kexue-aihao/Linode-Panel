<?php

declare(strict_types=1);

namespace LinodePanel;

use RuntimeException;

final class RainbowDnsClient
{
    private string $cookie = '';

    public function __construct(private readonly array $config)
    {
    }

    public function listDomains(array $options = []): array
    {
        $result = $this->post('/api/domain', [
            'offset' => $options['offset'] ?? 0,
            'limit' => $options['limit'] ?? 100,
            'kw' => $options['kw'] ?? '',
        ]);
        return [
            'total' => (int)($result['total'] ?? count($result['rows'] ?? [])),
            'rows' => $result['rows'] ?? [],
        ];
    }

    public function getDomain(int $domainId): array
    {
        if ($this->usesPasswordAuth()) {
            $domains = $this->post('/domain/data', ['id' => $domainId, 'offset' => 0, 'limit' => 1]);
            $quick = $this->post('/record/quickinfo/' . $domainId);
            return array_merge($domains['rows'][0] ?? ['id' => $domainId], $quick['data'] ?? []);
        }
        $result = $this->post('/api/domain/' . $domainId, ['loginurl' => 1]);
        return $result['data'] ?? $result;
    }

    public function listRecords(int $domainId, array $filters = []): array
    {
        $result = $this->post('/api/record/data/' . $domainId, [
            'offset' => $filters['offset'] ?? 0,
            'limit' => $filters['limit'] ?? 100,
            'keyword' => $filters['keyword'] ?? '',
            'subdomain' => $filters['subdomain'] ?? '',
            'value' => $filters['value'] ?? '',
            'type' => $filters['type'] ?? '',
            'line' => $filters['line'] ?? '',
            'status' => $filters['status'] ?? '',
        ]);
        if (array_is_list($result)) {
            return ['total' => count($result), 'rows' => $result];
        }
        return [
            'total' => (int)($result['total'] ?? count($result['rows'] ?? [])),
            'rows' => $result['rows'] ?? [],
        ];
    }

    public function addRecord(int $domainId, array $input): array
    {
        return $this->post('/api/record/add/' . $domainId, $this->recordInput($input));
    }

    public function updateRecord(int $domainId, string $recordId, array $input): array
    {
        return $this->post('/api/record/update/' . $domainId, ['recordid' => $recordId] + $this->recordInput($input));
    }

    public function deleteRecord(int $domainId, string $recordId): array
    {
        return $this->post('/api/record/delete/' . $domainId, ['recordid' => $recordId]);
    }

    private function post(string $path, array $params = []): array
    {
        if ($this->usesPasswordAuth()) {
            return $this->postWithCookie($this->cookiePath($path), $params);
        }

        $uid = (int)($this->config['uid'] ?? 0);
        $apiKey = (string)($this->config['api_key'] ?? '');
        if ($uid <= 0 || $apiKey === '') {
            throw new RuntimeException('彩虹 DNS UID/API Key 配置不完整');
        }
        $timestamp = time();
        return $this->request($path, [
            'uid' => $uid,
            'timestamp' => $timestamp,
            'sign' => md5($uid . $timestamp . $apiKey),
        ] + $params);
    }

    private function postWithCookie(string $path, array $params = []): array
    {
        $cookie = $this->login();
        return $this->request($path, $params, ['Cookie: ' . $cookie, 'X-Requested-With: XMLHttpRequest']);
    }

    private function login(): string
    {
        if ($this->cookie !== '') {
            return $this->cookie;
        }
        $response = $this->rawRequest('/login', [
            'username' => (string)$this->config['username'],
            'password' => (string)$this->config['password'],
        ], ['X-Requested-With: XMLHttpRequest'], true);
        $this->cookie = $response['cookie'];
        if ($this->cookie === '') {
            throw new RuntimeException('彩虹 DNS 登录成功但未返回 Cookie');
        }
        return $this->cookie;
    }

    private function request(string $path, array $params, array $headers = []): array
    {
        $response = $this->rawRequest($path, $params, $headers);
        $json = $response['json'];
        if (isset($json['code']) && (int)$json['code'] !== 0) {
            throw new RuntimeException((string)($json['msg'] ?? '彩虹 DNS API 调用失败'));
        }
        return $json;
    }

    private function rawRequest(string $path, array $params, array $headers = [], bool $captureCookie = false): array
    {
        $baseUrl = rtrim((string)$this->config['base_url'], '/');
        $ch = curl_init($baseUrl . $path);
        $responseHeaders = [];
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 25,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER => array_merge([
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            ], $headers),
            CURLOPT_HEADERFUNCTION => static function ($curl, string $line) use (&$responseHeaders): int {
                $responseHeaders[] = trim($line);
                return strlen($line);
            },
        ]);
        $body = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ($errno !== 0) {
            throw new RuntimeException('彩虹 DNS 请求失败：' . $error);
        }
        $json = json_decode((string)$body, true);
        if (!is_array($json)) {
            throw new RuntimeException('彩虹 DNS 返回非 JSON 响应：HTTP ' . $status);
        }
        if ($status >= 400) {
            throw new RuntimeException((string)($json['msg'] ?? ('彩虹 DNS 请求失败：HTTP ' . $status)));
        }
        return [
            'json' => $json,
            'cookie' => $captureCookie ? $this->extractCookie($responseHeaders) : '',
        ];
    }

    private function usesPasswordAuth(): bool
    {
        return trim((string)($this->config['username'] ?? '')) !== '';
    }

    private function cookiePath(string $path): string
    {
        if ($path === '/api/domain') {
            return '/domain/data';
        }
        if (str_starts_with($path, '/api/record/')) {
            return preg_replace('#^/api/#', '/', $path) ?? $path;
        }
        return $path;
    }

    private function extractCookie(array $headers): string
    {
        $cookies = [];
        foreach ($headers as $line) {
            if (stripos($line, 'Set-Cookie:') === 0) {
                $cookie = trim(substr($line, 11));
                $cookies[] = explode(';', $cookie, 2)[0];
            }
        }
        return implode('; ', array_filter($cookies));
    }

    private function recordInput(array $input): array
    {
        return [
            'name' => (string)$input['name'],
            'type' => (string)$input['type'],
            'value' => (string)$input['value'],
            'line' => (string)$input['line'],
            'ttl' => max(1, (int)$input['ttl']),
            'mx' => $input['mx'] === '' ? null : $input['mx'],
            'weight' => $input['weight'] === '' ? null : $input['weight'],
            'remark' => (string)($input['remark'] ?? ''),
        ];
    }
}
