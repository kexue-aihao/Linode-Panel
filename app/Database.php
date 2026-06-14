<?php

declare(strict_types=1);

namespace LinodePanel;

use PDO;
use PDOException;

final class Database
{
    public static function connect(array $config): PDO
    {
        $db = $config['db'] ?? [];
        $host = (string)($db['host'] ?? '127.0.0.1');
        $port = (int)($db['port'] ?? 3306);
        $name = (string)($db['name'] ?? '');
        $user = (string)($db['user'] ?? '');
        $password = (string)($db['password'] ?? '');

        if ($name === '' || $user === '') {
            throw new PDOException('数据库配置不完整，请先运行 install.sh 或检查 config.php');
        }

        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $name);
        return new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public static function migrate(PDO $pdo): void
    {
        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS panel_settings (
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "INSERT INTO panel_settings (id, version, session_secret, created_at, updated_at)
             VALUES (1, 1, '', UTC_TIMESTAMP(), UTC_TIMESTAMP())
             ON DUPLICATE KEY UPDATE id = id"
        );

        $stmt = $pdo->query("SELECT session_secret FROM panel_settings WHERE id = 1");
        $secret = (string)($stmt->fetchColumn() ?: '');
        if ($secret === '') {
            $secret = self::randomSecret();
            $update = $pdo->prepare("UPDATE panel_settings SET session_secret = ?, updated_at = UTC_TIMESTAMP() WHERE id = 1");
            $update->execute([$secret]);
        }

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS proxy_profiles (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(120) NOT NULL,
                type VARCHAR(16) NOT NULL,
                host VARCHAR(255) NOT NULL,
                port INT UNSIGNED NOT NULL,
                username VARCHAR(255) NOT NULL DEFAULT '',
                password TEXT NULL,
                source VARCHAR(32) NOT NULL DEFAULT 'fixed',
                status VARCHAR(32) NOT NULL DEFAULT 'unknown',
                last_checked_at DATETIME NULL,
                last_error TEXT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_type (type),
                INDEX idx_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS linode_accounts (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                label VARCHAR(120) NOT NULL,
                token TEXT NOT NULL,
                proxy_profile_id INT UNSIGNED NULL,
                email VARCHAR(255) NOT NULL DEFAULT '',
                username VARCHAR(255) NOT NULL DEFAULT '',
                status VARCHAR(32) NOT NULL DEFAULT 'unknown',
                is_default TINYINT(1) NOT NULL DEFAULT 0,
                remark VARCHAR(255) NOT NULL DEFAULT '',
                last_checked_at DATETIME NULL,
                last_error TEXT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_status (status),
                INDEX idx_default (is_default),
                INDEX idx_proxy_profile_id (proxy_profile_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS dns_configs (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(120) NOT NULL,
                base_url VARCHAR(255) NOT NULL,
                uid INT UNSIGNED NOT NULL DEFAULT 0,
                api_key TEXT NULL,
                username VARCHAR(255) NOT NULL DEFAULT '',
                password TEXT NULL,
                enabled TINYINT(1) NOT NULL DEFAULT 1,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_enabled (enabled)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS dns_record_bindings (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                config_id INT UNSIGNED NOT NULL,
                name VARCHAR(120) NOT NULL,
                domain_id INT UNSIGNED NOT NULL,
                domain_name VARCHAR(255) NOT NULL,
                subdomain VARCHAR(255) NOT NULL DEFAULT '@',
                record_type VARCHAR(16) NOT NULL DEFAULT 'A',
                line VARCHAR(120) NOT NULL DEFAULT 'default',
                ttl INT UNSIGNED NOT NULL DEFAULT 60,
                remark VARCHAR(255) NOT NULL DEFAULT '',
                enabled TINYINT(1) NOT NULL DEFAULT 1,
                last_a_record_id VARCHAR(128) NOT NULL DEFAULT '',
                last_aaaa_record_id VARCHAR(128) NOT NULL DEFAULT '',
                last_ipv4 VARCHAR(64) NOT NULL DEFAULT '',
                last_ipv6 VARCHAR(128) NOT NULL DEFAULT '',
                last_synced_at DATETIME NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_config_id (config_id),
                INDEX idx_enabled (enabled)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS notification_settings (
                id TINYINT UNSIGNED NOT NULL PRIMARY KEY,
                enabled TINYINT(1) NOT NULL DEFAULT 0,
                bot_token TEXT NULL,
                telegram_chat_id VARCHAR(64) NOT NULL DEFAULT '',
                telegram_group_chat_ids TEXT NULL,
                check_interval_hours INT UNSIGNED NOT NULL DEFAULT 6,
                last_checked_at DATETIME NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "INSERT INTO notification_settings (id, created_at, updated_at)
             VALUES (1, UTC_TIMESTAMP(), UTC_TIMESTAMP())
             ON DUPLICATE KEY UPDATE id = id"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS execution_logs (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                source VARCHAR(32) NOT NULL,
                action VARCHAR(64) NOT NULL,
                status VARCHAR(32) NOT NULL,
                message TEXT NOT NULL,
                context TEXT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_created_at (created_at),
                INDEX idx_source_action (source, action)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS replenish_policies (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(120) NOT NULL,
                enabled TINYINT(1) NOT NULL DEFAULT 1,
                name_prefix VARCHAR(64) NOT NULL DEFAULT 'auto-linode',
                region VARCHAR(64) NOT NULL,
                linode_type VARCHAR(64) NOT NULL,
                image VARCHAR(128) NOT NULL,
                root_pass TEXT NULL,
                authorized_keys TEXT NULL,
                user_data MEDIUMTEXT NULL,
                tags VARCHAR(255) NOT NULL DEFAULT '',
                min_running_count INT UNSIGNED NOT NULL DEFAULT 1,
                target_count INT UNSIGNED NOT NULL DEFAULT 1,
                backups_enabled TINYINT(1) NOT NULL DEFAULT 0,
                private_ip TINYINT(1) NOT NULL DEFAULT 0,
                firewall_id INT UNSIGNED NULL,
                dns_binding_id INT UNSIGNED NULL,
                remark VARCHAR(255) NOT NULL DEFAULT '',
                last_run_at DATETIME NULL,
                last_error TEXT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_enabled (enabled),
                INDEX idx_last_run_at (last_run_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        self::ensureColumn($pdo, 'replenish_policies', 'user_data', "MEDIUMTEXT NULL AFTER authorized_keys");
    }

    private static function ensureColumn(PDO $pdo, string $table, string $column, string $definition): void
    {
        $stmt = $pdo->prepare(
            "SELECT COUNT(*)
             FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?"
        );
        $stmt->execute([$table, $column]);
        if ((int)$stmt->fetchColumn() === 0) {
            $pdo->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
        }
    }

    public static function randomSecret(int $bytes = 48): string
    {
        return rtrim(strtr(base64_encode(random_bytes($bytes)), '+/', '-_'), '=');
    }
}
