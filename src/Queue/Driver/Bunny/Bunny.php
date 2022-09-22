<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue\Driver\Bunny;

use Bunny\Channel;
use Bunny\Client;
use Psr\Log\LoggerInterface;
use React\Promise\PromiseInterface;
use Zorachka\Framework\Queue\Driver;

final class Bunny implements Driver
{
    private Client $client;
    private PromiseInterface|Channel $channel;
    private LoggerInterface $logger;

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client->connect();
        $this->channel = $client->channel();
        $this->logger = $logger;
    }

    public function publish(string $queueName, string $message): void
    {
        $this->channel->queueDeclare($queueName, false, true, false, false);

        $this->channel->publish(
            $message,
            [
                'delivery-mode' => 2
            ],
            '',
            $queueName,
        );

        $this->logger->debug(" [·] Sent «" . $message . "»\n");

        $this->channel->close();
        $this->client->disconnect();
    }

    public function consume(string $queueName, callable $callback): void
    {
        $this->channel->queueDeclare($queueName, false, true, false, false);

        $this->logger->debug(" [*] Waiting for messages in «" . $queueName . "» queue. To exit press CTRL+C\n");

        $this->channel->qos(0, 1);
        $this->channel->run(
            new Worker($this->logger, $callback),
            $queueName
        );
    }
}
