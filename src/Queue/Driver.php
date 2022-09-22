<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue;

interface Driver
{
    public function publish(string $queueName, string $message): void;

    public function consume(string $queueName, callable $callback): void;
}
