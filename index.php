<?php

declare(strict_types=1);

use Zorachka\Framework\Queue\Message;

class Notify
{
    public function __construct(
        private string $userId
    ) {}

    public function userId(): string
    {
        return $this->userId;
    }
}

class NotifyHandler
{
    public function __construct(
        private Malier $mailer, private UserFetcher $users
    ) {}
    public function __invoke(Notify $job): void
    {}
}

$job = new Notify('$userId');

final class Queue
{
    public function push(string $queueName, Message $message): void
    {

    }

    public function listen(string $queueName, Message $message): void
    {
        $class = $message->type();
        $params = json_decode($message->payload());

        $job = new $class(...$params);

        $handler = $container->get($class . 'Handler');
        $handler($job);
    }
}

$queue->push('jobs', $job);

$queue->listen('jobs', function ($message) use ($container) {
    $class = $message->type();
    $params = json_decode($message->payload());

    $job = new $class(...$params);

    $handler = $container->get($class . 'Handler');
    $handler($job);
});
