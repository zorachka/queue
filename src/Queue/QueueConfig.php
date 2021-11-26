<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue;

final class QueueConfig
{
    private string $host;
    private int $port;
    private string $user;
    private string $password;

    public function __construct(string $host, int $port, string $user, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
    }

    public static function withDefaults(
        string $host = 'localhost',
        int $port = 5672,
        string $user = 'guest',
        string $password = 'guest',
    ) {
        return new self($host, $port, $user, $password);
    }

    /**
     * @return string
     */
    public function host(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function port(): int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function user(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function password(): string
    {
        return $this->password;
    }
}
