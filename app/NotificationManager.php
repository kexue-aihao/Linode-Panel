<?php

declare(strict_types=1);

namespace LinodePanel;

use PDO;
use RuntimeException;

final class NotificationManager
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly ?ExecutionLogger $logger = null
    ) {
    }

    public function get(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM notification_settings WHERE id = 1");
        $row = $stmt->fetch() ?: [];
        return $this->publicSettings($row);
    }

    public function save(array $data): array
    {
        $existing = $this->privateSettings();
        $enabled = !empty($data['enabled']);
        $botToken = trim((string)($data['bot_token'] ?? ''));
        $chatId = trim((string)($data['telegram_chat_id'] ?? $data['chat_id'] ?? ''));
        $groupIds = $this->normalizeChatIds($data['telegram_group_chat_ids'] ?? $data['group_chat_ids'] ?? '');
        $interval = max(1, min(24 * 30, (int)($data['check_interval_hours'] ?? $data['subscription_check_interval_hours'] ?? 6)));

        if ($enabled && $chatId === '' && $groupIds === '') {
            throw new RuntimeException('启用通知前请至少填写一个 Chat ID');
        }
        if ($enabled && $botToken === '' && (string)($existing['bot_token'] ?? '') === '') {
            throw new RuntimeException('启用通知前请填写 Telegram Bot Token');
        }
        if ($botToken !== '' && !preg_match('/^\d+:[A-Za-z0-9_-]{20,}$/', $botToken)) {
            throw new RuntimeException('Telegram Bot Token 格式不正确');
        }

        $stmt = $this->pdo->prepare(
            "UPDATE notification_settings
             SET enabled = ?, bot_token = ?, telegram_chat_id = ?, telegram_group_chat_ids = ?,
                 check_interval_hours = ?, updated_at = UTC_TIMESTAMP()
             WHERE id = 1"
        );
        $stmt->execute([
            $enabled ? 1 : 0,
            $botToken !== '' ? $botToken : (string)($existing['bot_token'] ?? ''),
            $chatId,
            $groupIds,
            $interval,
        ]);
        $this->logger?->log('notification', 'save', 'success', '通知设置已保存');
        return $this->get();
    }

    public function test(string $message = ''): array
    {
        $settings = $this->privateSettings();
        $token = (string)($settings['bot_token'] ?? '');
        $chatIds = $this->chatIdList((string)($settings['telegram_chat_id'] ?? ''), (string)($settings['telegram_group_chat_ids'] ?? ''));
        if (!(bool)($settings['enabled'] ?? false) || $token === '' || $chatIds === []) {
            throw new RuntimeException('Telegram 通知未启用或配置不完整');
        }
        $sent = [];
        $failed = [];
        foreach ($chatIds as $chatId) {
            try {
                $this->send($token, $chatId, $message ?: 'Linode Panel 通知测试成功');
                $sent[] = $chatId;
            } catch (\Throwable $e) {
                $failed[] = ['chat_id' => $chatId, 'error' => $e->getMessage()];
            }
        }
        $this->logger?->log('notification', 'test', $sent === [] ? 'failed' : 'success', sprintf('通知测试完成：成功 %d 个，失败 %d 个', count($sent), count($failed)));
        return ['sent' => $sent, 'failed' => $failed];
    }

    public function sendIfEnabled(string $text): void
    {
        $settings = $this->privateSettings();
        if (!(bool)($settings['enabled'] ?? false)) {
            return;
        }
        $token = (string)($settings['bot_token'] ?? '');
        $chatIds = $this->chatIdList((string)($settings['telegram_chat_id'] ?? ''), (string)($settings['telegram_group_chat_ids'] ?? ''));
        if ($token === '' || $chatIds === []) {
            return;
        }
        foreach ($chatIds as $chatId) {
            try {
                $this->send($token, $chatId, $text);
            } catch (\Throwable $e) {
                $this->logger?->log('notification', 'send', 'failed', 'Telegram 通知失败：' . $e->getMessage());
            }
        }
    }

    private function send(string $token, string $chatId, string $text): void
    {
        $ch = curl_init('https://api.telegram.org/bot' . $token . '/sendMessage');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'chat_id' => $chatId,
                'text' => substr($text, 0, 3900),
                'disable_web_page_preview' => true,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Accept: application/json'],
        ]);
        $body = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if ($errno !== 0) {
            throw new RuntimeException($error);
        }
        $json = json_decode((string)$body, true);
        if ($status >= 400 || !is_array($json) || empty($json['ok'])) {
            throw new RuntimeException((string)($json['description'] ?? ('HTTP ' . $status)));
        }
    }

    private function privateSettings(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM notification_settings WHERE id = 1");
        return $stmt->fetch() ?: [];
    }

    private function publicSettings(array $row): array
    {
        $token = (string)($row['bot_token'] ?? '');
        $chatId = (string)($row['telegram_chat_id'] ?? '');
        $groupIds = (string)($row['telegram_group_chat_ids'] ?? '');
        return [
            'enabled' => (bool)($row['enabled'] ?? false),
            'bot_token_configured' => $token !== '',
            'bot_token_masked' => $this->mask($token, 6, 6),
            'telegram_chat_id' => $chatId,
            'telegram_chat_id_masked' => $this->mask($chatId, 3, 3),
            'telegram_group_chat_ids' => $groupIds,
            'telegram_group_chat_id_list' => $this->chatIdList('', $groupIds),
            'check_interval_hours' => (int)($row['check_interval_hours'] ?? 6),
            'updated_at' => (string)($row['updated_at'] ?? ''),
        ];
    }

    private function normalizeChatIds(mixed $value): string
    {
        return implode("\n", $this->chatIdList('', is_array($value) ? implode("\n", $value) : (string)$value));
    }

    private function chatIdList(string $chatId, string $groupIds): array
    {
        $raw = trim($chatId . "\n" . $groupIds);
        if ($raw === '') {
            return [];
        }
        return array_values(array_unique(array_filter(array_map('trim', preg_split('/[\s,;，；]+/u', $raw) ?: []))));
    }

    private function mask(string $value, int $left, int $right): string
    {
        if ($value === '') {
            return '';
        }
        if (strlen($value) <= $left + $right + 3) {
            return substr($value, 0, min(3, strlen($value))) . '***';
        }
        return substr($value, 0, $left) . '****' . substr($value, -$right);
    }
}
