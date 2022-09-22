<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue;

final class __Queue
{
    public function publish(string $queueName, Message $message): void
    {

    }

    public function consume(string $queueName, callable $callback): void
    {
        $class = $message->type();
        $params = json_decode($message->payload());

        $job = new $class(...$params);

        $handler = $container->get($class . 'Handler');
        $handler($job);
    }
}
