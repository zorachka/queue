<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue;

interface QueueFactory
{
    public function create(string $queueName): Queue;
}
