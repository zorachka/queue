<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue;

final class Message
{
    private string $type;
    private string $payload;

    public function __construct(string $type, string $payload)
    {
        $this->type = $type;
        $this->payload = $payload;
    }

    /**
     * @return class-string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function payload(): string
    {
        return $this->payload;
    }
}
