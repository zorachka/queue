<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue;

use Zorachka\Framework\Queue\Console\QueueListen;
use Psr\Container\ContainerInterface;
use Bunny\Client;
use Psr\EventDispatcher\ListenerProviderInterface;
use Zorachka\Framework\Console\ConsoleConfig;
use Zorachka\Framework\Container\ServiceProvider;
use Zorachka\Framework\EventDispatcher\EventDispatcherConfig;
use Zorachka\Framework\EventDispatcher\ImmutablePrioritizedListenerProvider;
use Zorachka\Framework\EventDispatcher\PrioritizedListenerProvider;

final class QueueServiceProvider implements ServiceProvider
{
    /**
     * @inheritDoc
     */
    public static function getDefinitions(): array
    {
        return [
            ListenerProviderInterface::class => static function (ContainerInterface $container) {
                /** @var Queue $queue */
                $queue = $container->get(Queue::class);

                /** @var EventDispatcherConfig $config */
                $config = $container->get(EventDispatcherConfig::class);
                $listeners = $config->listeners();

                $providers = [];
                foreach ($listeners as $eventClassName => $rawEventListeners) {
                    $eventListeners = [];

                    foreach ($rawEventListeners as $eventListener) {
                        $listener = $container->get($eventListener);

                        if ($listener instanceof ShouldQueue && \is_callable($listener)) {
                            $listener = new QueueEventListener($queue, $listener);
                        }

                        $eventListeners[] = $listener;
                    }
                    $providers[] = new PrioritizedListenerProvider($eventClassName, $eventListeners);
                }

                return new ImmutablePrioritizedListenerProvider($providers);
            },
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
            ConsoleConfig::class => static function(ConsoleConfig $config) {
                return $config->withCommand(QueueListen::class);
            },
        ];
    }
}
