<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue\Transport\Bunny;

use Bunny\Channel;
use Bunny\Client;
use Psr\Log\LoggerInterface;

final class Queue
{
    private Client $client;
    private LoggerInterface $logger;
    private Worker $worker;

    private bool $isConnected = false;
    private bool $isDeclared = false;

    public function __construct(
        Client $client,
        LoggerInterface $logger,
        Worker $worker
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->worker = $worker;
    }

    private function connect(): void
    {
        if (!$this->isConnected) {
            $this->client->connect();
            $this->isConnected = true;
        }
    }

    private function declareQueue(string $queueName): Channel
    {
        $channel = $this->client->channel();

        if (false === $this->isDeclared) {
            $channel = $this->client->channel();
            $channel->queueDeclare($queueName); // Queue name

            $this->isDeclared = true;
        }

        return $channel;
    }

    public function push(string $queueName, string $message): void
    {
        $this->connect();

        $channel = $this->declareQueue($queueName);
        $channel->publish(
            $message,    // The message you're publishing as a string
            [],          // Any headers you want to add to the message
            '', // Exchange name
            $queueName,  // Routing key, in this example the queue's name
        );

        $channel->close();
        $this->client->disconnect();
    }

    public function listen(string $queueName): void
    {
        $this->connect();

        $channel = $this->declareQueue($queueName);

        $this->logger->info(" [*] Waiting for messages in «" . $queueName . "» queue. To exit press CTRL+C\n");

        $channel->run(
            $this->worker,
            $queueName,
        );
    }
}
