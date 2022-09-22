<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue;

use Bunny\Client;
use Psr\Container\ContainerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Zorachka\Framework\Console\ConsoleConfig;
use Zorachka\Framework\Container\ServiceProvider;
use Zorachka\Framework\Queue\Console\QueueListen;

final class QueueServiceProvider implements ServiceProvider
{
    /**
     * @inheritDoc
     */
    public static function getDefinitions(): array
    {
        return [
            Serializer::class => static function (ContainerInterface $container) {
                $encoders = [new JsonEncoder()];
                $normalizers = [new ObjectNormalizer()];

                return new Serializer($normalizers, $encoders);
            },
//            ListenerProviderInterface::class => static function (ContainerInterface $container) {
//                /** @var Queue $queue */
//                $queue = $container->get(Queue::class);
//
//                /** @var EventDispatcherConfig $config */
//                $config = $container->get(EventDispatcherConfig::class);
//                $listeners = $config->listeners();
//
//                $providers = [];
//                foreach ($listeners as $eventClassName => $rawEventListeners) {
//                    $eventListeners = [];
//
//                    foreach ($rawEventListeners as $eventListener) {
//                        $listener = $container->get($eventListener);
//
//                        if ($listener instanceof ShouldQueue && \is_callable($listener)) {
//                            $listener = new QueueEventListener($queue, $listener);
//                        }
//
//                        $eventListeners[] = $listener;
//                    }
//                    $providers[] = new PrioritizedListenerProvider($eventClassName, $eventListeners);
//                }
//
//                return new ImmutablePrioritizedListenerProvider($providers);
//            },
            Client::class => static function(ContainerInterface $container) {
                /** @var QueueConfig $config */
                $config = $container->get(QueueConfig::class);

                $connection = [
                    'host'      => $config->host(), // The default host is localhost
                    'vhost'     => '/',    // The default vhost is /
                    'user'      => $config->user(), // The default user is guest
                    'password'  => $config->password(), // The default password is guest
                ];

                return new Client($connection);
            },
            QueueConfig::class => static fn() => QueueConfig::withDefaults(),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getExtensions(): array
    {
        return [
            ConsoleConfig::class => static fn(ConsoleConfig $config) =>
                $config->withCommand(QueueListen::class),
        ];
    }
}
