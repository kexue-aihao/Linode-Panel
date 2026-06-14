<?php

declare(strict_types=1);

namespace LinodePanel;

use PDO;
use RuntimeException;

final class RainbowDnsManager
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly ?ExecutionLogger $logger = null
    ) {
    }

    public function configs(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM dns_configs ORDER BY id DESC");
        return array_map([$this, 'publicConfig'], $stmt->fetchAll());
    }

    public function saveConfig(array $data): array
    {
        $id = (int)($data['id'] ?? 0);
        $name = trim((string)($data['name'] ?? ''));
        $baseUrl = rtrim(trim((string)($data['base_url'] ?? '')), '/');
        $username = trim((string)($data['username'] ?? ''));
        $password = (string)($data['password'] ?? '');
        $uid = (int)($data['uid'] ?? 0);
        $apiKey = trim((string)($data['api_key'] ?? ''));
        $enabled = !array_key_exists('enabled', $data) || (bool)$data['enabled'];

        if ($name === '' || $baseUrl === '') {
            throw new RuntimeException('请填写 DNS 配置名称和面板地址');
        }
        if (!preg_match('#^https?://#i', $baseUrl)) {
            throw new RuntimeException('彩虹 DNS 面板地址必须以 http:// 或 https:// 开头');
        }
        if ($username === '' && ($uid <= 0 || $apiKey === '')) {
            throw new RuntimeException('请填写彩虹 DNS 用户名密码，或 UID/API Key');
        }

        if ($id > 0) {
            $existing = $this->privateConfig($id);
            $stmt = $this->pdo->prepare(
                "UPDATE dns_configs
                 SET name = ?, base_url = ?, uid = ?, api_key = ?, username = ?, password = ?, enabled = ?, updated_at = UTC_TIMESTAMP()
                 WHERE id = ?"
            );
            $stmt->execute([
                $name,
                $baseUrl,
                $uid,
                $apiKey !== '' ? $apiKey : (string)$existing['api_key'],
                $username,
                $password !== '' ? $password : (string)$existing['password'],
                $enabled ? 1 : 0,
                $id,
            ]);
            $this->logger?->log('dns', 'config_save', 'success', 'DNS 配置已更新：' . $name, ['config_id' => $id]);
            return $this->publicConfig($this->privateConfig($id));
        }

        if ($username !== '' && $password === '') {
            throw new RuntimeException('新增账号密码模式 DNS 配置必须填写密码');
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO dns_configs (name, base_url, uid, api_key, username, password, enabled, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())"
        );
        $stmt->execute([$name, $baseUrl, $uid, $apiKey, $username, $password, $enabled ? 1 : 0]);
        $id = (int)$this->pdo->lastInsertId();
        $this->logger?->log('dns', 'config_save', 'success', 'DNS 配置已添加：' . $name, ['config_id' => $id]);
        return $this->publicConfig($this->privateConfig($id));
    }

    public function deleteConfig(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM dns_configs WHERE id = ?");
        $stmt->execute([$id]);
        $stmt = $this->pdo->prepare("DELETE FROM dns_record_bindings WHERE config_id = ?");
        $stmt->execute([$id]);
        $this->logger?->log('dns', 'config_delete', 'success', 'DNS 配置已删除', ['config_id' => $id]);
    }

    public function testConfig(int $id): array
    {
        $client = $this->client($id);
        $domains = $client->listDomains();
        $this->logger?->log('dns', 'config_test', 'success', '彩虹 DNS 连接测试成功', ['config_id' => $id]);
        return ['ok' => true, 'total' => $domains['total'], 'rows' => array_slice($domains['rows'], 0, 5)];
    }

    public function domains(int $configId): array
    {
        return $this->client($configId)->listDomains();
    }

    public function domainDetail(int $configId, int $domainId): array
    {
        return $this->client($configId)->getDomain($domainId);
    }

    public function records(int $configId, int $domainId, array $filters = []): array
    {
        return $this->client($configId)->listRecords($domainId, $filters);
    }

    public function saveRecord(array $data): array
    {
        $configId = (int)($data['config_id'] ?? 0);
        $domainId = (int)($data['domain_id'] ?? 0);
        $recordId = trim((string)($data['record_id'] ?? ''));
        if ($configId <= 0 || $domainId <= 0) {
            throw new RuntimeException('缺少 DNS 配置或域名 ID');
        }
        $input = [
            'name' => trim((string)($data['name'] ?? '@')) ?: '@',
            'type' => strtoupper(trim((string)($data['type'] ?? 'A')) ?: 'A'),
            'value' => trim((string)($data['value'] ?? '')),
            'line' => trim((string)($data['line'] ?? 'default')) ?: 'default',
            'ttl' => (int)($data['ttl'] ?? 60),
            'mx' => $data['mx'] ?? '',
            'weight' => $data['weight'] ?? '',
            'remark' => trim((string)($data['remark'] ?? '')),
        ];
        if ($input['value'] === '') {
            throw new RuntimeException('请填写记录值');
        }
        $client = $this->client($configId);
        if ($recordId !== '') {
            $client->updateRecord($domainId, $recordId, $input);
            $action = 'updated';
        } else {
            $client->addRecord($domainId, $input);
            $action = 'created';
        }
        $this->logger?->log('dns', 'record_save', 'success', 'DNS 解析记录已保存', ['config_id' => $configId, 'domain_id' => $domainId]);
        return ['ok' => true, 'action' => $action];
    }

    public function deleteRecord(int $configId, int $domainId, string $recordId): array
    {
        $this->client($configId)->deleteRecord($domainId, $recordId);
        $this->logger?->log('dns', 'record_delete', 'success', 'DNS 解析记录已删除', ['config_id' => $configId, 'domain_id' => $domainId, 'record_id' => $recordId]);
        return ['ok' => true];
    }

    public function bindings(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM dns_record_bindings ORDER BY id DESC");
        return array_map([$this, 'publicBinding'], $stmt->fetchAll());
    }

    public function saveBinding(array $data): array
    {
        $id = (int)($data['id'] ?? 0);
        $payload = [
            'config_id' => (int)($data['config_id'] ?? 0),
            'name' => trim((string)($data['name'] ?? '')),
            'domain_id' => (int)($data['domain_id'] ?? 0),
            'domain_name' => trim((string)($data['domain_name'] ?? '')),
            'subdomain' => trim((string)($data['subdomain'] ?? '@')) ?: '@',
            'record_type' => strtoupper(trim((string)($data['record_type'] ?? 'A')) ?: 'A'),
            'line' => trim((string)($data['line'] ?? 'default')) ?: 'default',
            'ttl' => (int)($data['ttl'] ?? 60),
            'remark' => trim((string)($data['remark'] ?? '')),
            'enabled' => !array_key_exists('enabled', $data) || (bool)$data['enabled'],
        ];
        if ($payload['config_id'] <= 0 || $payload['domain_id'] <= 0 || $payload['name'] === '' || $payload['domain_name'] === '') {
            throw new RuntimeException('请填写完整 DNS 绑定信息');
        }
        if ($id > 0) {
            $stmt = $this->pdo->prepare(
                "UPDATE dns_record_bindings
                 SET config_id = ?, name = ?, domain_id = ?, domain_name = ?, subdomain = ?, record_type = ?, line = ?, ttl = ?, remark = ?, enabled = ?, updated_at = UTC_TIMESTAMP()
                 WHERE id = ?"
            );
            $stmt->execute([
                $payload['config_id'], $payload['name'], $payload['domain_id'], $payload['domain_name'],
                $payload['subdomain'], $payload['record_type'], $payload['line'], $payload['ttl'],
                $payload['remark'], $payload['enabled'] ? 1 : 0, $id,
            ]);
            return $this->binding($id);
        }
        $stmt = $this->pdo->prepare(
            "INSERT INTO dns_record_bindings
                (config_id, name, domain_id, domain_name, subdomain, record_type, line, ttl, remark, enabled, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())"
        );
        $stmt->execute([
            $payload['config_id'], $payload['name'], $payload['domain_id'], $payload['domain_name'],
            $payload['subdomain'], $payload['record_type'], $payload['line'], $payload['ttl'],
            $payload['remark'], $payload['enabled'] ? 1 : 0,
        ]);
        return $this->binding((int)$this->pdo->lastInsertId());
    }

    public function deleteBinding(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM dns_record_bindings WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function client(int $configId): RainbowDnsClient
    {
        return new RainbowDnsClient($this->privateConfig($configId));
    }

    private function privateConfig(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM dns_configs WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new RuntimeException('DNS 配置不存在');
        }
        return $row;
    }

    private function publicConfig(array $row): array
    {
        return [
            'id' => (int)$row['id'],
            'name' => (string)$row['name'],
            'base_url' => (string)$row['base_url'],
            'auth_mode' => (string)($row['username'] ?? '') !== '' ? 'password' : 'api',
            'username_set' => (string)($row['username'] ?? '') !== '',
            'api_key_set' => (string)($row['api_key'] ?? '') !== '',
            'uid' => (int)($row['uid'] ?? 0),
            'enabled' => (bool)$row['enabled'],
            'created_at' => (string)$row['created_at'],
        ];
    }

    private function binding(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM dns_record_bindings WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new RuntimeException('DNS 绑定不存在');
        }
        return $this->publicBinding($row);
    }

    private function publicBinding(array $row): array
    {
        $subdomain = (string)$row['subdomain'];
        $domain = (string)$row['domain_name'];
        return [
            'id' => (int)$row['id'],
            'config_id' => (int)$row['config_id'],
            'name' => (string)$row['name'],
            'domain_id' => (int)$row['domain_id'],
            'domain_name' => $domain,
            'subdomain' => $subdomain,
            'fqdn' => $subdomain === '@' || $subdomain === '' ? $domain : $subdomain . '.' . $domain,
            'record_type' => (string)$row['record_type'],
            'line' => (string)$row['line'],
            'ttl' => (int)$row['ttl'],
            'remark' => (string)($row['remark'] ?? ''),
            'enabled' => (bool)$row['enabled'],
            'last_ipv4' => (string)($row['last_ipv4'] ?? ''),
            'last_ipv6' => (string)($row['last_ipv6'] ?? ''),
            'last_synced_at' => (string)($row['last_synced_at'] ?? ''),
            'created_at' => (string)$row['created_at'],
        ];
    }
}
