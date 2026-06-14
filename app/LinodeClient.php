<?php

declare(strict_types=1);

namespace LinodePanel;

final class LinodeClient
{
    public function __construct(
        private readonly string $token,
        private readonly string $proxyUrl = '',
        private readonly ?array $proxyConfig = null
    ) {
    }

    public function request(string $method, string $path, ?array $payload = null): array
    {
        $ch = curl_init();
        $url = str_starts_with($path, 'http') ? $path : 'https://api.linode.com' . $path;
        $headers = [
            'Accept: application/json',
            'Authorization: Bearer ' . $this->token,
        ];

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 15,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if ($this->proxyConfig !== null) {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxyConfig['host'] . ':' . $this->proxyConfig['port']);
            if (($this->proxyConfig['type'] ?? '') === 'socks5') {
                curl_setopt($ch, CURLOPT_PROXYTYPE, defined('CURLPROXY_SOCKS5_HOSTNAME') ? CURLPROXY_SOCKS5_HOSTNAME : CURLPROXY_SOCKS5);
            }
            $username = (string)($this->proxyConfig['username'] ?? '');
            $password = (string)($this->proxyConfig['password'] ?? '');
            if ($username !== '' || $password !== '') {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $username . ':' . $password);
            }
        } elseif ($this->proxyUrl !== '') {
            curl_setopt($ch, CURLOPT_PROXY, $this->proxyUrl);
        }

        if ($payload !== null) {
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        $body = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ($errno !== 0) {
            throw new \RuntimeException('Linode API 网络请求失败：' . $error, 502);
        }

        $decoded = $body !== false && trim((string)$body) !== '' ? json_decode((string)$body, true) : [];
        if (!is_array($decoded)) {
            $decoded = ['raw' => (string)$body];
        }

        if ($status >= 400) {
            $message = $decoded['errors'][0]['reason'] ?? ('Linode API 返回错误：HTTP ' . $status);
            throw new LinodeException($message, $status, $decoded);
        }

        return $decoded;
    }

    public function listAll(string $path): array
    {
        $page = 1;
        $pages = 1;
        $data = [];
        $results = 0;

        while ($page <= $pages) {
            $separator = str_contains($path, '?') ? '&' : '?';
            $response = $this->request('GET', $path . $separator . 'page=' . $page . '&page_size=100');
            $data = array_merge($data, $response['data'] ?? []);
            $pages = (int)($response['pages'] ?? 1);
            $results = (int)($response['results'] ?? count($data));
            $page++;
        }

        return [
            'data' => $data,
            'page' => 1,
            'pages' => 1,
            'results' => $results ?: count($data),
        ];
    }
}
