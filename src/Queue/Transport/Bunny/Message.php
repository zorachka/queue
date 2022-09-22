<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue\Transport\Bunny;

final class Message
{
    private string $type;
    private array $payload;

    public function __construct(string $type, array $payload)
    {
        $this->type = $type;
        $this->payload = $payload;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }
}
