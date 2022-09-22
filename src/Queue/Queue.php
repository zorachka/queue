<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue;

use Symfony\Component\Serializer\SerializerInterface;

final class Queue
{
    private string $name;
    private Driver $driver;
    private array $handlers;
    private SerializerInterface $serializer;

    /**
     * @param string $name
     * @param array $handlers
     * @param Driver $driver
     * @param SerializerInterface $serializer
     */
    public function __construct(
        string $name,
        array $handlers,
        Driver $driver,
        SerializerInterface $serializer,
    ) {
        $this->name = $name;
        $this->handlers = $handlers;
        $this->driver = $driver;
        $this->serializer = $serializer;
    }

    public function publish(object $message): void
    {
        $this->driver->publish(
            $this->name,
            $this->serializer->serialize([
                'type' => $message::class,
                'payload' => $message,
            ], 'json')
        );
    }

    public function consume(): void
    {
        $this->driver->consume(
            $this->name,
            function (string $message) {
                $deserialized = \json_decode($message, true);
                $payload = $this->serializer->deserialize($deserialized['payload'], $deserialized['type'], 'json');

                $handle = $this->handlers[$deserialized['type']];

                $handle($payload);
            },
        );
    }
}
