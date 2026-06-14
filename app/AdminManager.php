<?php

declare(strict_types=1);

namespace LinodePanel;

use PDO;

final class AdminManager
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly SettingsStore $settings
    ) {
    }

    public function dashboard(): array
    {
        return [
            'users' => [
                [
                    'id' => 1,
                    'email' => $this->settings->get()['admin_user'],
                    'role' => 'admin',
                    'disabled' => false,
                    'account_count' => $this->count('linode_accounts'),
                    'proxy_count' => $this->count('proxy_profiles'),
                    'dns_config_count' => $this->count('dns_configs'),
                    'dns_binding_count' => $this->count('dns_record_bindings'),
                    'workflow_count' => $this->count('replenish_policies'),
                    'execution_log_count' => $this->count('execution_logs'),
                ],
            ],
            'stats' => [
                'linode_accounts' => $this->count('linode_accounts'),
                'proxies' => $this->count('proxy_profiles'),
                'dns_configs' => $this->count('dns_configs'),
                'dns_bindings' => $this->count('dns_record_bindings'),
                'replenish_policies' => $this->count('replenish_policies'),
                'logs' => $this->count('execution_logs'),
                'available_accounts' => $this->countWhere('linode_accounts', "status = 'available'"),
                'available_proxies' => $this->countWhere('proxy_profiles', "status = 'available'"),
            ],
            'php' => [
                'version' => PHP_VERSION,
                'extensions' => [
                    'pdo_mysql' => extension_loaded('pdo_mysql'),
                    'curl' => extension_loaded('curl'),
                    'json' => extension_loaded('json'),
                    'session' => extension_loaded('session'),
                ],
            ],
        ];
    }

    private function count(string $table): int
    {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
    }

    private function countWhere(string $table, string $where): int
    {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM `$table` WHERE $where")->fetchColumn();
    }
}
