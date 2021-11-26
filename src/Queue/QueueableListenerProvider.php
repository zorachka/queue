<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue;

use Psr\EventDispatcher\ListenerProviderInterface;

final class QueueableListenerProvider implements ListenerProviderInterface
{
    /** @var array */
    private $listeners = [];
    private Queue $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @inheritDoc
     */
    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->listeners as $listener) {
            if ($event instanceof $listener['type']) {
                yield $listener['listener'];
            }
        }
    }

//    public function addListenerService(
//        string $serviceName,
//        string $methodName,
//        string $type,
//        bool $queue = false
//    ): void {
//        if ($queue && $this->channel) {
//            $listener = $this->makeListenerForQueue($serviceName, $methodName);
//        } else {
//            $listener = $this->makeListenerForService($serviceName, $methodName);
//        }
//
//        $this->listeners[] = [
//            'listener' => $listener,
//            'type' => $type,
//        ];
//    }
//
//    private function makeListenerForService(
//        string $serviceName,
//        string $methodName
//    ): callable {
//        $container = $this->container;
//        $listener = function (object $event) use ($serviceName, $methodName, $container): object {
//            $container->get($serviceName)->$methodName($event);
//
//            return $event;
//        };
//
//        return $listener;
//    }
//
//    private function makeListenerForQueue(
//        string $serviceName,
//        string $methodName
//    ): callable {
//        $channel = $this->channel;
//
//        $listener = function (object $event) use ($serviceName, $methodName, $channel): object {
//            $details = [
//                'serviceName' => $serviceName,
//                'methodName' => $methodName,
//                'event' => $event,
//            ];
//
//            \var_dump($details);
//
//            $message = new AMQPMessage(serialize($details));
//            $channel->basic_publish($message, '', 'events');
//
//            return $event;
//        };
//
//        return $listener;
//    }
}
