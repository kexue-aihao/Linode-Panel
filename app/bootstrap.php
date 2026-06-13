<?php

declare(strict_types=1);

const LP_ROOT = __DIR__ . '/..';
const LP_CONFIG_FILE = LP_ROOT . '/config.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'LinodePanel\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $path = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

function lp_config(): array
{
    if (!is_file(LP_CONFIG_FILE)) {
        return [];
    }

    $config = require LP_CONFIG_FILE;
    return is_array($config) ? $config : [];
}

function lp_json(int $status, mixed $payload): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function lp_error(int $status, string $message): void
{
    lp_json($status, ['errors' => [['reason' => $message]]]);
}

function lp_read_json(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $data = json_decode($raw, true);
    if (!is_array($data)) {
        lp_error(400, 'JSON 请求体不正确');
    }
    return $data;
}

