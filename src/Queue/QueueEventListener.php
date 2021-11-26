<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue;

final class QueueEventListener
{
    /**
     * @var callable
     */
    private $listener;
    private Queue $queue;

    public function __construct(Queue $queue, callable $listener)
    {
        $this->listener = $listener;
        $this->queue = $queue;
    }

    public function __invoke(object $event): object
    {
        $message = \serialize([
            'event' => $event,
            'listener' => $this->listener,
        ]);

        $this->queue->push('events', $message);

        return $event;
    }
}
