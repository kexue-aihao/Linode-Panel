<?php

declare(strict_types=1);

namespace LinodePanel;

use PDO;
use RuntimeException;

final class ReplenishManager
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly LinodeAccountPool $accounts,
        private readonly ?ExecutionLogger $logger = null,
        private readonly ?NotificationManager $notifications = null
    ) {
    }

    public function list(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM replenish_policies ORDER BY id DESC");
        return array_map([$this, 'publicPolicy'], $stmt->fetchAll());
    }

    public function save(array $data): array
    {
        $id = (int)($data['id'] ?? 0);
        $policy = [
            'name' => trim((string)($data['name'] ?? '')),
            'enabled' => !array_key_exists('enabled', $data) || (bool)$data['enabled'],
            'name_prefix' => trim((string)($data['name_prefix'] ?? 'auto-linode')),
            'region' => trim((string)($data['region'] ?? '')),
            'linode_type' => trim((string)($data['linode_type'] ?? $data['type'] ?? '')),
            'image' => trim((string)($data['image'] ?? '')),
            'root_pass' => (string)($data['root_pass'] ?? ''),
            'authorized_keys' => trim((string)($data['authorized_keys'] ?? '')),
            'tags' => trim((string)($data['tags'] ?? '')),
            'min_running_count' => max(0, (int)($data['min_running_count'] ?? 1)),
            'target_count' => max(1, (int)($data['target_count'] ?? 1)),
            'backups_enabled' => !empty($data['backups_enabled']),
            'private_ip' => !empty($data['private_ip']),
            'firewall_id' => (int)($data['firewall_id'] ?? 0),
            'dns_binding_id' => (int)($data['dns_binding_id'] ?? 0),
            'remark' => trim((string)($data['remark'] ?? '')),
        ];

        if ($policy['name'] === '' || $policy['region'] === '' || $policy['linode_type'] === '' || $policy['image'] === '') {
            throw new RuntimeException('请填写策略名称、区域、套餐和镜像');
        }
        if ($policy['root_pass'] === '' && $policy['authorized_keys'] === '') {
            throw new RuntimeException('自动补机策略需要 Root 密码或 SSH 公钥');
        }

        if ($id > 0) {
            $stmt = $this->pdo->prepare(
                "UPDATE replenish_policies
                 SET name = ?, enabled = ?, name_prefix = ?, region = ?, linode_type = ?, image = ?,
                     root_pass = ?, authorized_keys = ?, tags = ?, min_running_count = ?, target_count = ?,
                     backups_enabled = ?, private_ip = ?, firewall_id = ?, dns_binding_id = ?, remark = ?,
                     updated_at = UTC_TIMESTAMP()
                 WHERE id = ?"
            );
            $stmt->execute([
                $policy['name'], $policy['enabled'] ? 1 : 0, $policy['name_prefix'], $policy['region'], $policy['linode_type'], $policy['image'],
                $policy['root_pass'], $policy['authorized_keys'], $policy['tags'], $policy['min_running_count'], $policy['target_count'],
                $policy['backups_enabled'] ? 1 : 0, $policy['private_ip'] ? 1 : 0, $policy['firewall_id'] ?: null,
                $policy['dns_binding_id'] ?: null, $policy['remark'], $id,
            ]);
            $this->logger?->log('replenish', 'save', 'success', '自动补机策略已更新：' . $policy['name'], ['policy_id' => $id]);
            return $this->find($id);
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO replenish_policies
                (name, enabled, name_prefix, region, linode_type, image, root_pass, authorized_keys, tags,
                 min_running_count, target_count, backups_enabled, private_ip, firewall_id, dns_binding_id, remark,
                 created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())"
        );
        $stmt->execute([
            $policy['name'], $policy['enabled'] ? 1 : 0, $policy['name_prefix'], $policy['region'], $policy['linode_type'], $policy['image'],
            $policy['root_pass'], $policy['authorized_keys'], $policy['tags'], $policy['min_running_count'], $policy['target_count'],
            $policy['backups_enabled'] ? 1 : 0, $policy['private_ip'] ? 1 : 0, $policy['firewall_id'] ?: null,
            $policy['dns_binding_id'] ?: null, $policy['remark'],
        ]);
        $id = (int)$this->pdo->lastInsertId();
        $this->logger?->log('replenish', 'save', 'success', '自动补机策略已创建：' . $policy['name'], ['policy_id' => $id]);
        return $this->find($id);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM replenish_policies WHERE id = ?");
        $stmt->execute([$id]);
        $this->logger?->log('replenish', 'delete', 'success', '自动补机策略已删除', ['policy_id' => $id]);
    }

    public function run(int $id): array
    {
        $policy = $this->privatePolicy($id);
        if (!(bool)$policy['enabled']) {
            throw new RuntimeException('策略未启用');
        }

        $client = $this->accounts->defaultClient();
        $instances = $client->listAll('/v4/linode/instances')['data'] ?? [];
        $prefix = (string)$policy['name_prefix'];
        $running = array_values(array_filter($instances, static function (array $item) use ($prefix): bool {
            return str_starts_with((string)($item['label'] ?? ''), $prefix)
                && (string)($item['status'] ?? '') === 'running';
        }));

        $target = max((int)$policy['min_running_count'], (int)$policy['target_count']);
        $missing = max(0, $target - count($running));
        $created = [];

        for ($i = 0; $i < $missing; $i++) {
            $created[] = $client->request('POST', '/v4/linode/instances', $this->createPayload($policy, $i + 1));
        }

        $stmt = $this->pdo->prepare(
            "UPDATE replenish_policies SET last_run_at = UTC_TIMESTAMP(), last_error = '', updated_at = UTC_TIMESTAMP() WHERE id = ?"
        );
        $stmt->execute([$id]);

        $message = sprintf(
            '自动补机执行完成：当前运行 %d 台，目标 %d 台，新建 %d 台',
            count($running),
            $target,
            count($created)
        );
        $this->logger?->log('replenish', 'run', 'success', $message, ['policy_id' => $id]);
        $this->notifications?->sendIfEnabled("Linode Panel 自动补机\n策略: {$policy['name']}\n{$message}");

        return [
            'ok' => true,
            'running_count' => count($running),
            'target_count' => $target,
            'created_count' => count($created),
            'created' => $created,
            'message' => $message,
        ];
    }

    private function createPayload(array $policy, int $index): array
    {
        $payload = [
            'label' => sprintf('%s-%s-%02d', $policy['name_prefix'], gmdate('YmdHis'), $index),
            'region' => (string)$policy['region'],
            'type' => (string)$policy['linode_type'],
            'image' => (string)$policy['image'],
            'backups_enabled' => (bool)$policy['backups_enabled'],
            'private_ip' => (bool)$policy['private_ip'],
        ];
        if ((string)$policy['root_pass'] !== '') {
            $payload['root_pass'] = (string)$policy['root_pass'];
        }
        $keys = array_values(array_filter(array_map('trim', preg_split('/\R+/', (string)$policy['authorized_keys']) ?: [])));
        if ($keys !== []) {
            $payload['authorized_keys'] = $keys;
        }
        $tags = array_values(array_filter(array_map('trim', explode(',', (string)$policy['tags']))));
        if ($tags !== []) {
            $payload['tags'] = $tags;
        }
        if (!empty($policy['firewall_id'])) {
            $payload['firewall_id'] = (int)$policy['firewall_id'];
        }
        return $payload;
    }

    private function find(int $id): array
    {
        return $this->publicPolicy($this->privatePolicy($id));
    }

    private function privatePolicy(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM replenish_policies WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new RuntimeException('自动补机策略不存在');
        }
        return $row;
    }

    private function publicPolicy(array $row): array
    {
        return [
            'id' => (int)$row['id'],
            'name' => (string)$row['name'],
            'enabled' => (bool)$row['enabled'],
            'name_prefix' => (string)$row['name_prefix'],
            'region' => (string)$row['region'],
            'linode_type' => (string)$row['linode_type'],
            'image' => (string)$row['image'],
            'authorized_keys_set' => trim((string)$row['authorized_keys']) !== '',
            'root_pass_set' => (string)$row['root_pass'] !== '',
            'tags' => (string)$row['tags'],
            'min_running_count' => (int)$row['min_running_count'],
            'target_count' => (int)$row['target_count'],
            'backups_enabled' => (bool)$row['backups_enabled'],
            'private_ip' => (bool)$row['private_ip'],
            'firewall_id' => isset($row['firewall_id']) ? (int)$row['firewall_id'] : 0,
            'dns_binding_id' => isset($row['dns_binding_id']) ? (int)$row['dns_binding_id'] : 0,
            'remark' => (string)$row['remark'],
            'last_run_at' => (string)($row['last_run_at'] ?? ''),
            'last_error' => (string)($row['last_error'] ?? ''),
            'created_at' => (string)$row['created_at'],
        ];
    }
}
