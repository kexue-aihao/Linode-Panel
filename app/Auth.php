<?php

declare(strict_types=1);

namespace LinodePanel;

final class Auth
{
    public static function start(array $config): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

        session_name((string)($config['session_name'] ?? 'linode_panel_session'));
        session_set_cookie_params([
            'lifetime' => 60 * 60 * 12,
            'path' => '/',
            'httponly' => true,
            'secure' => $secure,
            'samesite' => 'Lax',
        ]);
        session_start();
    }

    public static function login(string $adminUser): void
    {
        session_regenerate_id(true);
        $_SESSION['authenticated'] = true;
        $_SESSION['admin_user'] = $adminUser;
        $_SESSION['issued_at'] = time();
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    public static function check(SettingsStore $store): bool
    {
        $settings = $store->get();
        return !empty($_SESSION['authenticated'])
            && ($_SESSION['admin_user'] ?? '') === $settings['admin_user']
            && time() - (int)($_SESSION['issued_at'] ?? 0) <= 60 * 60 * 12;
    }
}

