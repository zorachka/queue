<?php

declare(strict_types=1);

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Zorachka\Framework\Queue\Driver;
use Zorachka\Framework\Tests\Unit\Queue\Fixtures\Message;

test('Queue', function () {
    $queue = new Zorachka\Framework\Queue\Queue(
        'tasks',
        [
            Message::class => function (Message $message) {

            }
        ],
        mock(Driver::class)->expect(
            publish: fn() => true,
        ),
        mock(SerializerInterface::class)->expect(
            serialize: fn() => '{"type": "Zorachka\\Framework\\Tests\\Unit\\Queue\\Fixtures\\Message","payload":{"value": "value"}}',
        )
    );

    $queue->publish(new Message('value'));
});
