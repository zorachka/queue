<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue\Transport\Bunny;

use Symfony\Component\Serializer\Serializer;
use Webmozart\Assert\Assert;

final class QueueEventListener
{
    private Queue $queue;
    private string $queueName;
    private Serializer $serializer;

    public function __construct(Queue $queue, Serializer $serializer, string $queueName)
    {
        $this->queue = $queue;
        $this->serializer = $serializer;
        Assert::notEmpty($queueName);
        $this->queueName = $queueName;
    }

    public function __invoke(object $event): object
    {
        $message = \json_encode([
            'type' => $event::class,
            'payload' => $this->serializer->serialize($event, 'json'),
        ]);

        $this->queue->push($this->queueName, $message);

        return $event;
    }
}
