<?php

declare(strict_types=1);

use Zorachka\Framework\Queue\Driver\InMemory;

test('InMemory driver can publish message', function () {
    $driver = new InMemory();

    $driver->publish('tasks', 'message');
    expect($driver->count('tasks'))->toBe(1);
});

test('InMemory driver can consume message', function () {
    $driver = new InMemory();
    $driver->publish('tasks', 'message');

    $driver->consume('tasks', function ($message) {
        expect($message)->toBe('message');
    });
});
