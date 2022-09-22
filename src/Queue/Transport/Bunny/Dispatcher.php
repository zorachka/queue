<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue\Transport\Bunny;

use Bunny\Message;
use Symfony\Component\Serializer\Serializer;
use Zorachka\Framework\Queue\Person;

final class Dispatcher
{
    private QueueableListenerProvider $listenerProvider;
    private Serializer $serializer;

    public function __construct(QueueableListenerProvider $listenerProvider, Serializer $serializer)
    {
        $this->listenerProvider = $listenerProvider;
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(Message $message): bool
    {
        $this->serializer->deserialize($message->content, Person::class, 'json');
        $this->serializer->deserialize($message->content, $message['payload'], 'json');
        $listeners = $this->listenerProvider->getListenersForEvent($content['data']);

        foreach ($listeners as $listener) {

        }
        $data = $content['data'];

        $result = $listener($data);

        return $result;
    }
}
