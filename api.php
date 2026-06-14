<?php

declare(strict_types=1);

use LinodePanel\Auth;
use LinodePanel\AdminManager;
use LinodePanel\Database;
use LinodePanel\ExecutionLogger;
use LinodePanel\LinodeAccountPool;
use LinodePanel\LinodeClient;
use LinodePanel\LinodeException;
use LinodePanel\NotificationManager;
use LinodePanel\ProxyManager;
use LinodePanel\RainbowDnsManager;
use LinodePanel\ReplenishManager;
use LinodePanel\SettingsStore;

require __DIR__ . '/app/bootstrap.php';

function lp_attach_power_on_times(array $response, LinodeClient $client): array
{
    $instances = $response['data'] ?? [];
    if (!is_array($instances) || $instances === []) {
        return $response;
    }

    $instanceIds = [];
    foreach ($instances as $instance) {
        $id = (int)($instance['id'] ?? 0);
        if ($id > 0) {
            $instanceIds[$id] = true;
        }
    }

    $eventTimes = [];
    try {
        $events = $client->request('GET', '/v4/account/events?page=1&page_size=200')['data'] ?? [];
        $bootActions = ['linode_boot', 'linode_reboot', 'linode_create'];
        foreach ($events as $event) {
            $action = (string)($event['action'] ?? '');
            $status = (string)($event['status'] ?? '');
            $entityId = (int)($event['entity']['id'] ?? 0);
            $created = (string)($event['created'] ?? '');
            if (!isset($instanceIds[$entityId]) || !in_array($action, $bootActions, true) || $created === '' || $status === 'failed') {
                continue;
            }
            $createdTs = strtotime($created) ?: 0;
            $currentTs = strtotime((string)($eventTimes[$entityId]['time'] ?? '')) ?: 0;
            if ($createdTs > $currentTs) {
                $eventTimes[$entityId] = ['time' => $created, 'source' => $action];
            }
        }
    } catch (Throwable) {
        $eventTimes = [];
    }

    $now = time();
    foreach ($instances as $index => $instance) {
        $id = (int)($instance['id'] ?? 0);
        $running = (string)($instance['status'] ?? '') === 'running';
        $powerOnAt = '';
        $source = '';

        if ($running && isset($eventTimes[$id])) {
            $powerOnAt = (string)$eventTimes[$id]['time'];
            $source = (string)$eventTimes[$id]['source'];
        } elseif ($running) {
            $powerOnAt = (string)($instance['updated'] ?? $instance['created'] ?? '');
            $source = $powerOnAt !== '' ? 'updated' : '';
        }

        $hours = null;
        if ($powerOnAt !== '') {
            $startedAt = strtotime($powerOnAt) ?: 0;
            if ($startedAt > 0) {
                $hours = max(0, (int)floor(($now - $startedAt) / 3600));
            }
        }

        $instances[$index]['power_on_at'] = $powerOnAt;
        $instances[$index]['power_on_source'] = $source;
        $instances[$index]['uptime_hours'] = $hours;
        $instances[$index]['uptime_display'] = $running ? lp_format_uptime_hours($hours) : '-';
    }

    $response['data'] = $instances;
    return $response;
}

function lp_format_uptime_hours(?int $hours): string
{
    if ($hours === null) {
        return '-';
    }
    if ($hours < 1) {
        return '不足1小时';
    }

    $days = intdiv($hours, 24);
    $remainingHours = $hours % 24;
    if ($days > 0) {
        return $days . '天' . ($remainingHours > 0 ? $remainingHours . '小时' : '');
    }
    return $hours . '小时';
}

try {
    $config = lp_config();
    if ($config === []) {
        lp_error(503, '缺少 config.php，请先运行 install.sh 或复制 config.example.php');
    }

    Auth::start($config);
    $pdo = Database::connect($config);
    Database::migrate($pdo);
    $store = new SettingsStore($pdo);
    $logger = new ExecutionLogger($pdo);
    $proxies = new ProxyManager($pdo, $logger);
    $accounts = new LinodeAccountPool($pdo, $store, $proxies, $logger);
    $dns = new RainbowDnsManager($pdo, $logger);
    $notifications = new NotificationManager($pdo, $logger);
    $replenish = new ReplenishManager($pdo, $accounts, $logger, $notifications);
    $admin = new AdminManager($pdo, $store);

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

    if ($action === 'logs' && $method === 'GET') {
        lp_json(200, ['data' => $logger->list((int)($_GET['limit'] ?? 100))]);
    }

    if ($action === 'admin/dashboard' && $method === 'GET') {
        lp_json(200, $admin->dashboard());
    }

    if ($action === 'notifications') {
        if ($method === 'GET') {
            lp_json(200, $notifications->get());
        }
        if (in_array($method, ['POST', 'PUT'], true)) {
            lp_json(200, $notifications->save(lp_read_json()));
        }
    }

    if ($action === 'notifications/test' && $method === 'POST') {
        $body = lp_read_json();
        lp_json(200, $notifications->test((string)($body['message'] ?? '')));
    }

    if ($action === 'security' && $method === 'GET') {
        $settings = $store->publicSettings();
        lp_json(200, [
            'admin_user' => $settings['admin_user'],
            'session_authenticated' => true,
            'has_linode_token' => $settings['has_linode_token'],
            'updated_at' => $settings['updated_at'],
        ]);
    }

    if ($action === 'security' && in_array($method, ['POST', 'PUT'], true)) {
        $body = lp_read_json();
        $payload = [];
        if (array_key_exists('admin_user', $body)) {
            $payload['admin_user'] = $body['admin_user'];
        }
        if (!empty($body['new_password'])) {
            $current = (string)($body['current_password'] ?? '');
            $settings = $store->get();
            if (!password_verify($current, $settings['password_hash'])) {
                lp_error(401, '当前密码不正确');
            }
            $payload['password'] = $body['new_password'];
        }
        if ($payload === []) {
            lp_error(400, '没有可保存的安全设置');
        }
        $store->update($payload);
        $logger->log('security', 'update', 'success', '账号安全设置已更新');
        lp_json(200, $store->publicSettings());
    }

    if ($action === 'proxies' && $method === 'GET') {
        lp_json(200, ['data' => $proxies->list()]);
    }

    if ($action === 'proxies' && $method === 'POST') {
        $body = lp_read_json();
        if (trim((string)($body['proxy_api_url'] ?? '')) !== '') {
            lp_json(200, $proxies->importFromApi(
                trim((string)$body['proxy_api_url']),
                (string)($body['raw_type'] ?? $body['type'] ?? ''),
                (int)($body['proxy_api_limit'] ?? 10),
                !empty($body['validate'])
            ));
        }
        lp_json(201, $proxies->add($body));
    }

    if ($action === 'proxies/api/parse' && $method === 'POST') {
        $body = lp_read_json();
        $url = trim((string)($body['proxy_api_url'] ?? ''));
        if ($url === '') {
            lp_json(200, ['mode' => 'api_parse', 'raw_type' => 'auto', 'total_candidates' => 0, 'errors' => [], 'proxies' => []]);
        }
        lp_json(200, ['mode' => 'api_parse'] + $proxies->parseFromApiUrl($url, (string)($body['raw_type'] ?? $body['type'] ?? ''), (int)($body['proxy_api_limit'] ?? 10)));
    }

    if (preg_match('#^proxies/(\d+)(?:/(check))?$#', $action, $matches)) {
        $id = (int)$matches[1];
        $sub = $matches[2] ?? '';
        if ($method === 'DELETE' && $sub === '') {
            $proxies->delete($id);
            lp_json(200, ['ok' => true]);
        }
        if ($method === 'POST' && $sub === 'check') {
            $body = lp_read_json();
            lp_json(200, $proxies->check($id, !empty($body['delete_on_fail'])));
        }
    }

    if ($action === 'linode/accounts') {
        if ($method === 'GET') {
            lp_json(200, ['data' => $accounts->list()]);
        }
        if ($method === 'POST') {
            lp_json(201, $accounts->add(lp_read_json()));
        }
    }

    if (preg_match('#^linode/accounts/(\d+)(?:/(check|default))?$#', $action, $matches)) {
        $id = (int)$matches[1];
        $sub = $matches[2] ?? '';
        if ($method === 'DELETE' && $sub === '') {
            $accounts->delete($id);
            lp_json(200, ['ok' => true]);
        }
        if ($method === 'POST' && $sub === 'check') {
            lp_json(200, $accounts->check($id));
        }
        if ($method === 'POST' && $sub === 'default') {
            lp_json(200, $accounts->setDefault($id));
        }
    }

    if ($action === 'dns/configs') {
        if ($method === 'GET') {
            lp_json(200, ['data' => $dns->configs()]);
        }
        if (in_array($method, ['POST', 'PUT'], true)) {
            lp_json(200, $dns->saveConfig(lp_read_json()));
        }
    }

    if (preg_match('#^dns/configs/(\d+)(?:/(test|domains|domain|records))?$#', $action, $matches)) {
        $configId = (int)$matches[1];
        $sub = $matches[2] ?? '';
        if ($method === 'DELETE' && $sub === '') {
            $dns->deleteConfig($configId);
            lp_json(200, ['ok' => true]);
        }
        if ($method === 'POST' && $sub === 'test') {
            lp_json(200, $dns->testConfig($configId));
        }
        if ($method === 'GET' && $sub === 'domains') {
            lp_json(200, $dns->domains($configId));
        }
        if ($method === 'GET' && $sub === 'domain') {
            lp_json(200, $dns->domainDetail($configId, (int)($_GET['domain_id'] ?? 0)));
        }
        if ($sub === 'records') {
            $domainId = (int)($_GET['domain_id'] ?? 0);
            if ($method === 'GET') {
                lp_json(200, $dns->records($configId, $domainId, $_GET));
            }
            if ($method === 'POST') {
                $body = lp_read_json();
                $body['config_id'] = $configId;
                $body['domain_id'] = $domainId ?: (int)($body['domain_id'] ?? 0);
                lp_json(200, $dns->saveRecord($body));
            }
        }
    }

    if (preg_match('#^dns/configs/(\d+)/records/([^/]+)$#', $action, $matches) && $method === 'DELETE') {
        $dns->deleteRecord((int)$matches[1], (int)($_GET['domain_id'] ?? 0), rawurldecode($matches[2]));
        lp_json(200, ['ok' => true]);
    }

    if ($action === 'dns/bindings') {
        if ($method === 'GET') {
            lp_json(200, ['data' => $dns->bindings()]);
        }
        if (in_array($method, ['POST', 'PUT'], true)) {
            lp_json(200, $dns->saveBinding(lp_read_json()));
        }
    }

    if (preg_match('#^dns/bindings/(\d+)$#', $action, $matches) && $method === 'DELETE') {
        $dns->deleteBinding((int)$matches[1]);
        lp_json(200, ['ok' => true]);
    }

    if ($action === 'replenish/policies') {
        if ($method === 'GET') {
            lp_json(200, ['data' => $replenish->list()]);
        }
        if (in_array($method, ['POST', 'PUT'], true)) {
            lp_json(200, $replenish->save(lp_read_json()));
        }
    }

    if (preg_match('#^replenish/policies/(\d+)(?:/(run))?$#', $action, $matches)) {
        $id = (int)$matches[1];
        $sub = $matches[2] ?? '';
        if ($method === 'DELETE' && $sub === '') {
            $replenish->delete($id);
            lp_json(200, ['ok' => true]);
        }
        if ($method === 'POST' && $sub === 'run') {
            lp_json(200, $replenish->run($id));
        }
    }

    $client = null;
    if (str_starts_with($action, 'linode/')) {
        $client = $accounts->defaultClient();
    }

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

    if ($action === 'linode/api/official' && $method === 'POST') {
        $body = lp_read_json();
        $apiMethod = strtoupper(trim((string)($body['method'] ?? 'GET')));
        $path = trim((string)($body['path'] ?? ''));
        $listAll = !empty($body['list_all']);
        $payload = $body['payload'] ?? null;

        if (!in_array($apiMethod, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            lp_error(400, '请求方法只支持 GET、POST、PUT、PATCH、DELETE');
        }
        if (!str_starts_with($path, '/v4/')) {
            lp_error(400, '官方 API 路径必须以 /v4/ 开头');
        }
        if (preg_match('#^/v4/(?:debug|internal|admin)(?:/|$)#i', $path)) {
            lp_error(400, '不允许访问内部或调试路径');
        }
        if ($payload !== null && !is_array($payload)) {
            lp_error(400, '请求体必须是 JSON 对象，留空表示不发送请求体');
        }
        if ($listAll && $apiMethod !== 'GET') {
            lp_error(400, '自动翻页只支持 GET 请求');
        }

        $result = $listAll ? $client->listAll($path) : $client->request($apiMethod, $path, $payload);
        $logger->log(
            'linode',
            'official_api',
            'success',
            '官方 API 已执行：' . $apiMethod . ' ' . $path,
            ['method' => $apiMethod, 'path' => $path, 'list_all' => $listAll]
        );
        lp_json(200, ['ok' => true, 'method' => $apiMethod, 'path' => $path, 'result' => $result]);
    }

    if ($action === 'linode/instances') {
        if ($method === 'GET') {
            lp_json(200, lp_attach_power_on_times($client->listAll('/v4/linode/instances'), $client));
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
        if ($sub === 'allocate-ip' && $method === 'POST') {
            $payload = [
                'type' => 'ipv4',
                'public' => true,
                'linode_id' => $id,
            ];
            $result = $client->request('POST', '/v4/networking/ips', $payload);
            $address = (string)($result['address'] ?? '');
            $logger->log(
                'linode',
                'allocate_ip',
                'success',
                '已为 Linode 实例分配新的公网 IPv4' . ($address !== '' ? '：' . $address : ''),
                ['linode_id' => $id, 'address' => $address]
            );
            lp_json(200, $result);
        }
        if ($sub === 'ddos-protection' && $method === 'GET') {
            lp_json(200, [
                'supported' => false,
                'enabled_by_default' => null,
                'message' => '未发现 Linode 官方 API 提供 VM 实例级 DDoS 防护开关；本面板不提供伪开启按钮，请以 Linode/Akamai 控制台或支持渠道显示的账号/平台防护状态为准。',
            ]);
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
    $code = (int)$e->getCode();
    lp_error($code >= 400 && $code < 600 ? $code : 500, $e->getMessage());
}
