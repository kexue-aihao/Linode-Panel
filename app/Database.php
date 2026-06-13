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
    }

    public static function randomSecret(int $bytes = 48): string
    {
        return rtrim(strtr(base64_encode(random_bytes($bytes)), '+/', '-_'), '=');
    }
}

