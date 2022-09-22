<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue\Driver;

use SplQueue;
use Zorachka\Framework\Queue\Driver;

final class InMemory implements Driver
{
    /**
     * @var array<SplQueue>
     */
    private array $queues = [];

    public function publish(string $queueName, string $message): void
    {
        if (isset($this->queues[$queueName])) {
            $this->queues[$queueName]->push($message);
        } else {
            $this->queues[$queueName] = new SplQueue();
            $this->queues[$queueName]->push($message);
        }
    }

    public function consume(string $queueName, callable $callback): void
    {
        $queue = $this->queues[$queueName];
        $message = $queue->dequeue();
        $callback($message);
    }

    public function count(string $queueName): int
    {
        $queue = $this->queues[$queueName];

        return $queue->count();
    }
}
