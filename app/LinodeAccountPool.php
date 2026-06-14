<?php

declare(strict_types=1);

namespace LinodePanel;

use PDO;
use RuntimeException;

final class LinodeAccountPool
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly SettingsStore $settings,
        private readonly ProxyManager $proxies,
        private readonly ?ExecutionLogger $logger = null
    ) {
    }

    public function list(): array
    {
        $stmt = $this->pdo->query(
            "SELECT a.*, p.name AS proxy_name, p.type AS proxy_type, p.host AS proxy_host, p.port AS proxy_port
             FROM linode_accounts a
             LEFT JOIN proxy_profiles p ON p.id = a.proxy_profile_id
             ORDER BY a.is_default DESC, a.id DESC"
        );
        return array_map([$this, 'publicAccount'], $stmt->fetchAll());
    }

    public function add(array $data): array
    {
        $label = trim((string)($data['label'] ?? $data['name'] ?? ''));
        $token = trim((string)($data['token'] ?? $data['linode_token'] ?? ''));
        if ($label === '') {
            $label = 'Linode ' . date('His');
        }
        if ($token === '') {
            throw new RuntimeException('请填写 Linode Token');
        }
        $proxyId = (int)($data['proxy_profile_id'] ?? 0);
        $remark = trim((string)($data['remark'] ?? ''));
        $makeDefault = !empty($data['is_default']);

        $client = new LinodeClient($token, '', $this->proxies->getRuntimeById($proxyId));
        $profile = $client->request('GET', '/v4/profile');

        $stmt = $this->pdo->prepare(
            "INSERT INTO linode_accounts
                (label, token, proxy_profile_id, email, username, status, is_default, remark, last_checked_at, last_error, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, 'available', ?, ?, UTC_TIMESTAMP(), '', UTC_TIMESTAMP(), UTC_TIMESTAMP())"
        );
        $stmt->execute([
            $label,
            $token,
            $proxyId > 0 ? $proxyId : null,
            (string)($profile['email'] ?? ''),
            (string)($profile['username'] ?? ''),
            $makeDefault ? 1 : 0,
            $remark,
        ]);
        $id = (int)$this->pdo->lastInsertId();
        if ($makeDefault || count($this->list()) === 1) {
            $this->setDefault($id);
        }

        $account = $this->find($id);
        $this->logger?->log('linode_account', 'add', 'success', 'Linode 号池账号已添加：' . $account['label'], ['account_id' => $id]);
        return $account;
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM linode_accounts WHERE id = ?");
        $stmt->execute([$id]);
        $this->logger?->log('linode_account', 'delete', 'success', 'Linode 号池账号已删除', ['account_id' => $id]);
    }

    public function setDefault(int $id): array
    {
        $account = $this->privateAccount($id);
        $this->pdo->exec("UPDATE linode_accounts SET is_default = 0");
        $stmt = $this->pdo->prepare("UPDATE linode_accounts SET is_default = 1, updated_at = UTC_TIMESTAMP() WHERE id = ?");
        $stmt->execute([$id]);
        $this->settings->setLinodeToken((string)$account['token']);
        $this->logger?->log('linode_account', 'default', 'success', '默认 Linode Token 已切换', ['account_id' => $id]);
        return $this->find($id);
    }

    public function check(int $id): array
    {
        $account = $this->privateAccount($id);
        try {
            $client = $this->clientForAccount($account);
            $profile = $client->request('GET', '/v4/profile');
            $stmt = $this->pdo->prepare(
                "UPDATE linode_accounts
                 SET status = 'available', email = ?, username = ?, last_checked_at = UTC_TIMESTAMP(), last_error = '', updated_at = UTC_TIMESTAMP()
                 WHERE id = ?"
            );
            $stmt->execute([(string)($profile['email'] ?? ''), (string)($profile['username'] ?? ''), $id]);
            $this->logger?->log('linode_account', 'check', 'success', 'Linode 账号检测可用', ['account_id' => $id]);
            return ['ok' => true, 'account' => $this->find($id)];
        } catch (\Throwable $e) {
            $stmt = $this->pdo->prepare(
                "UPDATE linode_accounts
                 SET status = 'failed', last_checked_at = UTC_TIMESTAMP(), last_error = ?, updated_at = UTC_TIMESTAMP()
                 WHERE id = ?"
            );
            $stmt->execute([$e->getMessage(), $id]);
            $this->logger?->log('linode_account', 'check', 'failed', 'Linode 账号检测失败：' . $e->getMessage(), ['account_id' => $id]);
            return ['ok' => false, 'error' => $e->getMessage(), 'account' => $this->find($id)];
        }
    }

    public function accountInfo(int $id): array
    {
        $account = $this->privateAccount($id);
        $client = $this->clientForAccount($account);
        $profile = $client->request('GET', '/v4/profile');
        $billing = $client->request('GET', '/v4/account');
        $public = $this->find($id);

        $this->logger?->log('linode_account', 'info', 'success', 'Linode 账户信息已读取', ['account_id' => $id]);
        return [
            'account' => $public,
            'profile' => $profile,
            'billing' => $billing,
            'summary' => $this->accountSummary($profile, $billing),
        ];
    }

    public function applyPromoCode(int $id, string $code): array
    {
        $code = trim($code);
        if ($code === '') {
            throw new RuntimeException('请填写优惠码');
        }

        $account = $this->privateAccount($id);
        $client = $this->clientForAccount($account);
        $result = $client->request('POST', '/v4/account/promo-codes', ['promo_code' => $code]);
        $info = $this->accountInfo($id);
        $this->logger?->log('linode_account', 'promo_code', 'success', 'Linode 优惠码已应用：' . $code, ['account_id' => $id]);

        return [
            'ok' => true,
            'result' => $result,
            'info' => $info,
        ];
    }

    public function defaultClient(): LinodeClient
    {
        $stmt = $this->pdo->query("SELECT * FROM linode_accounts WHERE is_default = 1 ORDER BY id DESC LIMIT 1");
        $account = $stmt->fetch();
        if ($account) {
            return new LinodeClient(
                (string)$account['token'],
                '',
                $this->proxies->getRuntimeById((int)($account['proxy_profile_id'] ?? 0))
            );
        }

        $settings = $this->settings->get();
        if ($settings['linode_token'] === '') {
            throw new RuntimeException('请先在 Linode 号池中保存 Linode Personal Access Token，并设为默认账号', 428);
        }
        return new LinodeClient($settings['linode_token'], $settings['proxy_url']);
    }

    public function countAvailable(): int
    {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM linode_accounts WHERE status = 'available'")->fetchColumn();
    }

    private function clientForAccount(array $account): LinodeClient
    {
        return new LinodeClient(
            (string)$account['token'],
            '',
            $this->proxies->getRuntimeById((int)($account['proxy_profile_id'] ?? 0))
        );
    }

    private function accountSummary(array $profile, array $billing): array
    {
        $activePromotions = $billing['active_promotions'] ?? [];
        $promotion = is_array($activePromotions) && isset($activePromotions[0]) && is_array($activePromotions[0])
            ? $activePromotions[0]
            : [];

        return [
            'email' => (string)($profile['email'] ?? ''),
            'username' => (string)($profile['username'] ?? ''),
            'created' => (string)($profile['created'] ?? $billing['created'] ?? ''),
            'balance' => $billing['balance'] ?? null,
            'uninvoiced' => $billing['uninvoiced_balance'] ?? $billing['uninvoiced'] ?? null,
            'promotion_code' => (string)($promotion['code'] ?? $promotion['summary'] ?? $promotion['description'] ?? ''),
            'promotion_expires' => (string)($promotion['expire_dt'] ?? $promotion['expires'] ?? $promotion['expiration'] ?? ''),
            'promotion_remaining' => $promotion['remaining'] ?? $promotion['credit_remaining'] ?? $promotion['amount_remaining'] ?? null,
            'active_promotions' => is_array($activePromotions) ? $activePromotions : [],
        ];
    }

    private function find(int $id): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT a.*, p.name AS proxy_name, p.type AS proxy_type, p.host AS proxy_host, p.port AS proxy_port
             FROM linode_accounts a
             LEFT JOIN proxy_profiles p ON p.id = a.proxy_profile_id
             WHERE a.id = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new RuntimeException('Linode 号池账号不存在');
        }
        return $this->publicAccount($row);
    }

    private function privateAccount(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM linode_accounts WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            throw new RuntimeException('Linode 号池账号不存在');
        }
        return $row;
    }

    private function publicAccount(array $row): array
    {
        return [
            'id' => (int)$row['id'],
            'label' => (string)$row['label'],
            'email' => (string)($row['email'] ?? ''),
            'username' => (string)($row['username'] ?? ''),
            'status' => (string)($row['status'] ?? 'unknown'),
            'is_default' => (bool)$row['is_default'],
            'proxy_profile_id' => isset($row['proxy_profile_id']) ? (int)$row['proxy_profile_id'] : 0,
            'proxy_name' => (string)($row['proxy_name'] ?? ''),
            'proxy_label' => !empty($row['proxy_host']) ? ((string)$row['proxy_type'] . '://' . $row['proxy_host'] . ':' . $row['proxy_port']) : '',
            'remark' => (string)($row['remark'] ?? ''),
            'last_checked_at' => (string)($row['last_checked_at'] ?? ''),
            'last_error' => (string)($row['last_error'] ?? ''),
            'created_at' => (string)$row['created_at'],
            'token_masked' => $this->maskToken((string)($row['token'] ?? '')),
        ];
    }

    private function maskToken(string $token): string
    {
        if ($token === '') {
            return '';
        }
        if (strlen($token) <= 12) {
            return substr($token, 0, 3) . '***';
        }
        return substr($token, 0, 6) . '****' . substr($token, -6);
    }
}
