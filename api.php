<?php

declare(strict_types=1);

use LinodePanel\Auth;
use LinodePanel\Database;
use LinodePanel\LinodeClient;
use LinodePanel\LinodeException;
use LinodePanel\SettingsStore;

require __DIR__ . '/app/bootstrap.php';

try {
    $config = lp_config();
    if ($config === []) {
        lp_error(503, '缺少 config.php，请先运行 install.sh 或复制 config.example.php');
    }

    Auth::start($config);
    $pdo = Database::connect($config);
    Database::migrate($pdo);
    $store = new SettingsStore($pdo);

    $action = trim((string)($_GET['action'] ?? ''), '/');
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if ($action === 'health') {
        lp_json(200, [
            'ok' => true,
            'configured' => $store->isConfigured(),
            'time' => gmdate(DATE_ATOM),
        ]);
    }

    if ($action === 'session' && $method === 'GET') {
        lp_json(200, [
            'configured' => $store->isConfigured(),
            'authenticated' => Auth::check($store),
            'settings' => $store->publicSettings(),
        ]);
    }

    if ($action === 'setup' && $method === 'POST') {
        if ($store->isConfigured()) {
            lp_error(409, '面板已经初始化');
        }
        $body = lp_read_json();
        $adminUser = trim((string)($body['admin_user'] ?? 'admin')) ?: 'admin';
        $password = (string)($body['password'] ?? '');
        if (strlen($password) < 10) {
            lp_error(400, '管理员密码至少需要 10 位');
        }
        $settings = $store->setup($adminUser, $password);
        Auth::login($settings['admin_user']);
        lp_json(200, $store->publicSettings());
    }

    if ($action === 'login' && $method === 'POST') {
        if (!$store->isConfigured()) {
            lp_error(428, '面板尚未初始化');
        }
        $body = lp_read_json();
        $settings = $store->get();
        $adminUser = trim((string)($body['admin_user'] ?? ''));
        $password = (string)($body['password'] ?? '');
        if (strcasecmp($adminUser, $settings['admin_user']) !== 0 || !password_verify($password, $settings['password_hash'])) {
            lp_error(401, '用户名或密码不正确');
        }
        Auth::login($settings['admin_user']);
        lp_json(200, $store->publicSettings());
    }

    if ($action === 'logout' && $method === 'POST') {
        Auth::logout();
        lp_json(200, ['ok' => true]);
    }

    if (!Auth::check($store)) {
        lp_error(401, '请先登录');
    }

    if ($action === 'settings') {
        if ($method === 'GET') {
            lp_json(200, $store->publicSettings());
        }
        if ($method === 'PUT') {
            $settings = $store->update(lp_read_json());
            lp_json(200, $store->publicSettings());
        }
    }

    $settings = $store->get();
    if ($settings['linode_token'] === '') {
        lp_error(428, '请先在设置中保存 Linode Personal Access Token');
    }
    $client = new LinodeClient($settings['linode_token'], $settings['proxy_url']);

    if ($action === 'linode/test' && $method === 'POST') {
        lp_json(200, $client->request('GET', '/v4/profile'));
    }

    if ($action === 'linode/catalog' && $method === 'GET') {
        lp_json(200, [
            'regions' => $client->listAll('/v4/regions'),
            'types' => $client->listAll('/v4/linode/types'),
            'images' => $client->listAll('/v4/images?is_public=true'),
            'stackscripts' => $client->listAll('/v4/linode/stackscripts?is_public=true'),
            'firewalls' => $client->listAll('/v4/networking/firewalls'),
        ]);
    }

    if ($action === 'linode/instances') {
        if ($method === 'GET') {
            lp_json(200, $client->listAll('/v4/linode/instances'));
        }
        if ($method === 'POST') {
            lp_json(202, $client->request('POST', '/v4/linode/instances', lp_read_json()));
        }
    }

    if (preg_match('#^linode/instances/(\d+)(?:/([a-z-]+))?$#', $action, $matches)) {
        $id = (int)$matches[1];
        $sub = $matches[2] ?? '';
        $base = '/v4/linode/instances/' . $id;

        if ($sub === '') {
            if ($method === 'GET') {
                lp_json(200, $client->request('GET', $base));
            }
            if ($method === 'PUT') {
                lp_json(200, $client->request('PUT', $base, lp_read_json()));
            }
            if ($method === 'DELETE') {
                lp_json(200, $client->request('DELETE', $base));
            }
        }

        if (in_array($sub, ['boot', 'reboot', 'shutdown', 'rebuild', 'rescue', 'resize', 'migrate', 'password'], true) && $method === 'POST') {
            $raw = file_get_contents('php://input');
            $trimmedRaw = trim((string)$raw);
            $payload = ($trimmedRaw === '' || $trimmedRaw === '{}') ? null : json_decode($trimmedRaw, true);
            if ($payload !== null && !is_array($payload)) {
                lp_error(400, 'JSON 请求体不正确');
            }
            lp_json(202, $client->request('POST', $base . '/' . $sub, $payload));
        }
        if (in_array($sub, ['ips', 'stats'], true) && $method === 'GET') {
            lp_json(200, $client->request('GET', $base . '/' . $sub));
        }
    }

    if ($action === 'linode/firewalls') {
        if ($method === 'GET') {
            lp_json(200, $client->listAll('/v4/networking/firewalls'));
        }
        if ($method === 'POST') {
            lp_json(201, $client->request('POST', '/v4/networking/firewalls', lp_read_json()));
        }
    }

    if ($action === 'linode/events' && $method === 'GET') {
        lp_json(200, $client->listAll('/v4/account/events'));
    }

    lp_error(404, 'API 不存在');
} catch (LinodeException $e) {
    $payload = $e->payload;
    if (!isset($payload['errors'])) {
        $payload = ['errors' => [['reason' => $e->getMessage()]]];
    }
    lp_json($e->getCode() > 0 ? $e->getCode() : 502, $payload);
} catch (Throwable $e) {
    lp_error(500, $e->getMessage());
}
