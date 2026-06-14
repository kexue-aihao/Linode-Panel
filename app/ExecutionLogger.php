<?php

declare(strict_types=1);

namespace LinodePanel;

use PDO;
use Throwable;

final class ExecutionLogger
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function log(string $source, string $action, string $status, string $message, array $context = []): void
    {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO execution_logs (source, action, status, message, context, created_at)
                 VALUES (?, ?, ?, ?, ?, UTC_TIMESTAMP())"
            );
            $stmt->execute([
                substr($source, 0, 32),
                substr($action, 0, 64),
                substr($status, 0, 32),
                $message,
                $context === [] ? null : json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]);
        } catch (Throwable) {
            // Logging must never break user-facing operations.
        }
    }

    public function list(int $limit = 100): array
    {
        $limit = max(1, min(500, $limit));
        $stmt = $this->pdo->query(
            "SELECT id, source, action, status, message, context, created_at
             FROM execution_logs
             ORDER BY id DESC
             LIMIT " . $limit
        );
        return array_map([$this, 'publicLog'], $stmt->fetchAll());
    }

    private function publicLog(array $row): array
    {
        $context = [];
        if ((string)($row['context'] ?? '') !== '') {
            $decoded = json_decode((string)$row['context'], true);
            $context = is_array($decoded) ? $decoded : [];
        }
        return [
            'id' => (int)$row['id'],
            'source' => (string)$row['source'],
            'action' => (string)$row['action'],
            'status' => (string)$row['status'],
            'message' => (string)$row['message'],
            'context' => $context,
            'created_at' => (string)$row['created_at'],
        ];
    }
}
