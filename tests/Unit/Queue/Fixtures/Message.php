<?php

declare(strict_types=1);

namespace Zorachka\Framework\Tests\Unit\Queue\Fixtures;

final class Message
{
    public string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
