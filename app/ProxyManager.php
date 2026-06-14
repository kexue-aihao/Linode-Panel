<?php

declare(strict_types=1);

namespace LinodePanel;

use PDO;
use RuntimeException;

final class ProxyManager
{
    private const TYPES = ['http', 'socks5'];

    public function __construct(
        private readonly PDO $pdo,
        private readonly ?ExecutionLogger $logger = null
    ) {
    }

    public function list(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM proxy_profiles ORDER BY id DESC"
        );
        return array_map([$this, 'publicProxy'], $stmt->fetchAll());
    }

    public function getRuntimeById(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }
        $stmt = $this->pdo->prepare("SELECT * FROM proxy_profiles WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        return $this->runtimeProxy($row);
    }

    public function add(array $data): array
    {
        $proxy = $this->normalizeProxy($data);
        if (!empty($data['validate'])) {
            $this->validateConnection($proxy);
        }
        $name = trim((string)($data['name'] ?? ''));
        if ($name === '') {
            $name = strtoupper($proxy['type']) . ' ' . $proxy['host'];
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO proxy_profiles
                (name, type, host, port, username, password, source, status, last_checked_at, last_error, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'unknown', NULL, '', UTC_TIMESTAMP(), UTC_TIMESTAMP())"
        );
        $stmt->execute([
            $name,
            $proxy['type'],
            $proxy['host'],
            $proxy['port'],
            $proxy['username'],
            $proxy['password'],
            (string)($data['source'] ?? 'fixed') ?: 'fixed',
        ]);

        $created = $this->find((int)$this->pdo->lastInsertId());
        $this->logger?->log('proxy', 'add', 'success', '代理配置已添加：' . $created['label'], ['proxy_id' => $created['id']]);
        return $created;
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM proxy_profiles WHERE id = ?");
        $stmt->execute([$id]);
        $this->logger?->log('proxy', 'delete', 'success', '代理配置已删除', ['proxy_id' => $id]);
    }

    public function check(int $id, bool $deleteOnFail = false): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM proxy_profiles WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new RuntimeException('代理配置不存在');
        }

        $proxy = $this->runtimeProxy($row);
        try {
            $this->validateConnection($proxy);
            $this->updateStatus($id, 'available', '');
            $public = $this->find($id);
            $this->logger?->log('proxy', 'check', 'success', '代理可用：' . $public['label'], ['proxy_id' => $id]);
            return [
                'proxy_id' => $id,
                'status' => 'available',
                'deleted' => false,
                'message' => '代理可用',
                'error' => '',
                'proxy' => $public,
            ];
        } catch (RuntimeException $e) {
            if ($deleteOnFail) {
                $this->delete($id);
                return [
                    'proxy_id' => $id,
                    'status' => 'deleted',
                    'deleted' => true,
                    'message' => '代理不可用，已删除',
                    'error' => $e->getMessage(),
                    'proxy' => null,
                ];
            }
            $this->updateStatus($id, 'failed', $e->getMessage());
            $this->logger?->log('proxy', 'check', 'failed', '代理不可用：' . $e->getMessage(), ['proxy_id' => $id]);
            return [
                'proxy_id' => $id,
                'status' => 'failed',
                'deleted' => false,
                'message' => '代理不可用',
                'error' => $e->getMessage(),
                'proxy' => $this->find($id),
            ];
        }
    }

    public function importFromApi(string $apiUrl, string $rawType = '', int $limit = 10, bool $validate = false): array
    {
        $text = $this->fetchApi($apiUrl);
        $parsed = $this->parseApiResponse($text, $apiUrl, $rawType, $limit);
        $saved = [];
        $errors = $parsed['errors'];
        $index = 0;

        foreach ($parsed['proxies'] as $item) {
            try {
                if ($validate) {
                    $this->validateConnection($item);
                }
                $index++;
                $saved[] = $this->add([
                    'name' => 'API代理-' . $index,
                    'type' => $item['type'],
                    'host' => $item['host'],
                    'port' => $item['port'],
                    'username' => $item['username'],
                    'password' => $item['password'],
                    'source' => 'api',
                ]);
            } catch (RuntimeException $e) {
                $errors[] = $item['host'] . ':' . $item['port'] . ': ' . $e->getMessage();
            }
        }

        $this->logger?->log('proxy', 'api_import', $saved === [] ? 'failed' : 'success', sprintf(
            '代理 API 导入完成：成功 %d 个，失败 %d 个，识别 %d 条',
            count($saved),
            count($errors),
            $parsed['total_candidates']
        ), ['api_url' => $this->maskUrl($apiUrl)]);

        return [
            'mode' => 'api',
            'raw_type' => $parsed['raw_type'],
            'total_candidates' => $parsed['total_candidates'],
            'imported' => count($saved),
            'failed' => count($errors),
            'errors' => array_slice($errors, 0, 20),
            'proxies' => $saved,
        ];
    }

    public function parseFromApiUrl(string $apiUrl, string $rawType = '', int $limit = 10): array
    {
        return $this->parseApiResponse($this->fetchApi($apiUrl), $apiUrl, $rawType, $limit);
    }

    public function parseApiResponse(string $text, string $apiUrl = '', string $rawType = '', int $limit = 100): array
    {
        $type = $this->inferRawType($apiUrl, $rawType);
        $strings = [$text];
        $decoded = json_decode($text, true);
        if (is_array($decoded)) {
            $this->collectStrings($decoded, $strings);
        }

        $candidates = [];
        foreach ($strings as $value) {
            foreach (preg_split('/[\s,;，；]+/u', str_replace(['<br>', '<br/>', '<br />', "\r"], "\n", $value)) ?: [] as $part) {
                $part = trim($part);
                if ($part !== '') {
                    $candidates[$part] = true;
                }
            }
        }

        $proxies = [];
        $errors = [];
        foreach (array_keys($candidates) as $candidate) {
            try {
                $proxy = $this->parseProxyString($candidate, $type);
                $key = $proxy['type'] . '|' . $proxy['host'] . '|' . $proxy['port'] . '|' . $proxy['username'];
                $proxies[$key] = $proxy;
            } catch (RuntimeException $e) {
                $errors[] = $candidate . ': ' . $e->getMessage();
            }
        }

        $limit = max(1, min(100, $limit));
        $items = array_slice(array_values($proxies), 0, $limit);
        return [
            'raw_type' => $type,
            'total_candidates' => count($candidates),
            'proxies' => $items,
            'errors' => array_slice($errors, 0, 20),
        ];
    }

    public function curlOptionsFor(?array $proxy): array
    {
        if (!$proxy) {
            return [];
        }
        $options = [
            CURLOPT_PROXY => $proxy['host'] . ':' . $proxy['port'],
        ];
        if ($proxy['type'] === 'socks5') {
            $options[CURLOPT_PROXYTYPE] = defined('CURLPROXY_SOCKS5_HOSTNAME') ? CURLPROXY_SOCKS5_HOSTNAME : CURLPROXY_SOCKS5;
        }
        if ($proxy['username'] !== '' || $proxy['password'] !== '') {
            $options[CURLOPT_PROXYUSERPWD] = $proxy['username'] . ':' . $proxy['password'];
        }
        return $options;
    }

    private function fetchApi(string $apiUrl): string
    {
        if (!preg_match('#^https?://#i', $apiUrl)) {
            throw new RuntimeException('代理 API 链接仅支持 http:// 或 https://');
        }

        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 25,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_USERAGENT => 'Linode-Panel proxy-api-import',
            CURLOPT_HTTPHEADER => ['Accept: text/plain, application/json, */*'],
        ]);
        $body = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ($errno !== 0) {
            throw new RuntimeException('代理 API 拉取失败：' . $error);
        }
        if ($status >= 400) {
            throw new RuntimeException('代理 API 返回错误：HTTP ' . $status);
        }
        return (string)$body;
    }

    private function parseProxyString(string $value, string $rawType): array
    {
        if (str_contains($value, '://')) {
            $parts = parse_url($value);
            if (!is_array($parts) || empty($parts['scheme']) || empty($parts['host'])) {
                throw new RuntimeException('代理 URL 格式无效');
            }
            return $this->normalizeProxy([
                'type' => $parts['scheme'],
                'host' => $parts['host'],
                'port' => $parts['port'] ?? '',
                'username' => isset($parts['user']) ? rawurldecode((string)$parts['user']) : '',
                'password' => isset($parts['pass']) ? rawurldecode((string)$parts['pass']) : '',
            ]);
        }

        $parts = explode(':', $value, 4);
        if (count($parts) < 2) {
            throw new RuntimeException('无法识别代理格式');
        }
        return $this->normalizeProxy([
            'type' => $rawType === 'auto' ? 'socks5' : $rawType,
            'host' => $parts[0],
            'port' => $parts[1],
            'username' => $parts[2] ?? '',
            'password' => $parts[3] ?? '',
        ]);
    }

    private function inferRawType(string $apiUrl, string $fallback): string
    {
        $fallback = strtolower(trim($fallback));
        if (in_array($fallback, self::TYPES, true)) {
            return $fallback;
        }
        $query = parse_url($apiUrl, PHP_URL_QUERY);
        if (is_string($query)) {
            parse_str($query, $params);
            foreach (['GenType', 'type', 'protocol'] as $key) {
                $value = strtolower(trim((string)($params[$key] ?? '')));
                if (in_array($value, self::TYPES, true)) {
                    return $value;
                }
            }
        }
        return 'auto';
    }

    private function normalizeProxy(array $data): array
    {
        $type = strtolower(trim((string)($data['type'] ?? '')));
        if (!in_array($type, self::TYPES, true)) {
            throw new RuntimeException('代理类型仅支持 HTTP 或 SOCKS5');
        }
        $host = trim((string)($data['host'] ?? ''));
        if ($host === '' || preg_match('/[\s\/]/', $host)) {
            throw new RuntimeException('代理主机格式无效');
        }
        $port = (int)($data['port'] ?? 0);
        if ($port <= 0 || $port > 65535) {
            throw new RuntimeException('代理端口必须是 1-65535');
        }
        return [
            'type' => $type,
            'host' => $host,
            'port' => $port,
            'username' => trim((string)($data['username'] ?? '')),
            'password' => (string)($data['password'] ?? ''),
        ];
    }

    private function validateConnection(array $proxy): void
    {
        $ch = curl_init('https://api.linode.com/v4/regions?page_size=1');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_CONNECTTIMEOUT => 8,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ] + $this->curlOptionsFor($proxy));
        curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ($errno !== 0) {
            throw new RuntimeException($error);
        }
        if ($status >= 500 || $status === 0) {
            throw new RuntimeException('代理出口请求异常：HTTP ' . $status);
        }
    }

    private function updateStatus(int $id, string $status, string $error): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE proxy_profiles
             SET status = ?, last_error = ?, last_checked_at = UTC_TIMESTAMP(), updated_at = UTC_TIMESTAMP()
             WHERE id = ?"
        );
        $stmt->execute([$status, $error, $id]);
    }

    private function find(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM proxy_profiles WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new RuntimeException('代理配置不存在');
        }
        return $this->publicProxy($row);
    }

    private function runtimeProxy(array $row): array
    {
        return [
            'type' => (string)$row['type'],
            'host' => (string)$row['host'],
            'port' => (int)$row['port'],
            'username' => (string)($row['username'] ?? ''),
            'password' => (string)($row['password'] ?? ''),
        ];
    }

    private function publicProxy(array $row): array
    {
        $runtime = $this->runtimeProxy($row);
        return [
            'id' => (int)$row['id'],
            'name' => (string)$row['name'],
            'type' => $runtime['type'],
            'host' => $runtime['host'],
            'port' => $runtime['port'],
            'auth_enabled' => $runtime['username'] !== '' || $runtime['password'] !== '',
            'source' => (string)($row['source'] ?? 'fixed'),
            'status' => (string)($row['status'] ?? 'unknown'),
            'last_error' => (string)($row['last_error'] ?? ''),
            'last_checked_at' => (string)($row['last_checked_at'] ?? ''),
            'created_at' => (string)$row['created_at'],
            'label' => $this->maskProxy($runtime),
        ];
    }

    private function maskProxy(array $proxy): string
    {
        $auth = $proxy['username'] !== '' || $proxy['password'] !== '' ? '***@' : '';
        return $proxy['type'] . '://' . $auth . $proxy['host'] . ':' . $proxy['port'];
    }

    private function collectStrings(mixed $value, array &$output): void
    {
        if (is_string($value)) {
            $output[] = $value;
            return;
        }
        if (is_array($value)) {
            foreach ($value as $item) {
                $this->collectStrings($item, $output);
            }
        }
    }

    private function maskUrl(string $url): string
    {
        return preg_replace('/([?&](?:KeyName|Crc|key|token|password)=)[^&]+/i', '$1***', $url) ?? $url;
    }
}
