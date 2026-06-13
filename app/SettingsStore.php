<?php

declare(strict_types=1);

namespace LinodePanel;

use PDO;

final class SettingsStore
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function get(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM panel_settings WHERE id = 1');
        $row = $stmt->fetch() ?: [];
        return [
            'version' => (int)($row['version'] ?? 1),
            'admin_user' => (string)($row['admin_user'] ?? ''),
            'password_hash' => (string)($row['password_hash'] ?? ''),
            'linode_token' => (string)($row['linode_token'] ?? ''),
            'proxy_url' => (string)($row['proxy_url'] ?? ''),
            'session_secret' => (string)($row['session_secret'] ?? ''),
            'created_at' => (string)($row['created_at'] ?? ''),
            'updated_at' => (string)($row['updated_at'] ?? ''),
        ];
    }

    public function isConfigured(): bool
    {
        $settings = $this->get();
        return $settings['admin_user'] !== '' && $settings['password_hash'] !== '';
    }

    public function setup(string $adminUser, string $password, string $token): array
    {
        if ($this->isConfigured()) {
            throw new \RuntimeException('面板已经初始化');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $secret = Database::randomSecret();
        $stmt = $this->pdo->prepare(
            "UPDATE panel_settings
             SET version = 1, admin_user = ?, password_hash = ?, linode_token = ?, proxy_url = '',
                 session_secret = ?, updated_at = UTC_TIMESTAMP()
             WHERE id = 1"
        );
        $stmt->execute([$adminUser, $hash, $token, $secret]);
        return $this->get();
    }

    public function update(array $data): array
    {
        $settings = $this->get();

        if (array_key_exists('admin_user', $data)) {
            $settings['admin_user'] = trim((string)$data['admin_user']);
            if ($settings['admin_user'] === '') {
                throw new \RuntimeException('管理员用户名不能为空');
            }
        }
        if (!empty($data['password'])) {
            if (strlen((string)$data['password']) < 10) {
                throw new \RuntimeException('管理员密码至少需要 10 位');
            }
            $settings['password_hash'] = password_hash((string)$data['password'], PASSWORD_DEFAULT);
        }
        if (!empty($data['clear_linode_token'])) {
            $settings['linode_token'] = '';
        } elseif (array_key_exists('linode_token', $data)) {
            $settings['linode_token'] = trim((string)$data['linode_token']);
        }
        if (!empty($data['clear_proxy_url'])) {
            $settings['proxy_url'] = '';
        } elseif (array_key_exists('proxy_url', $data)) {
            $settings['proxy_url'] = trim((string)$data['proxy_url']);
        }
        if ($settings['session_secret'] === '') {
            $settings['session_secret'] = Database::randomSecret();
        }

        $stmt = $this->pdo->prepare(
            "UPDATE panel_settings
             SET version = ?, admin_user = ?, password_hash = ?, linode_token = ?, proxy_url = ?,
                 session_secret = ?, updated_at = UTC_TIMESTAMP()
             WHERE id = 1"
        );
        $stmt->execute([
            $settings['version'],
            $settings['admin_user'],
            $settings['password_hash'],
            $settings['linode_token'],
            $settings['proxy_url'],
            $settings['session_secret'],
        ]);

        return $this->get();
    }

    public function publicSettings(): array
    {
        $settings = $this->get();
        return [
            'configured' => $settings['admin_user'] !== '' && $settings['password_hash'] !== '',
            'admin_user' => $settings['admin_user'],
            'has_linode_token' => $settings['linode_token'] !== '',
            'proxy_url' => $settings['proxy_url'],
            'created_at' => $settings['created_at'],
            'updated_at' => $settings['updated_at'],
        ];
    }
}
