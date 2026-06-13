<?php

declare(strict_types=1);

namespace LinodePanel;

final class LinodeException extends \RuntimeException
{
    public function __construct(string $message, int $code, public readonly array $payload)
    {
        parent::__construct($message, $code);
    }
}

