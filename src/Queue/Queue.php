<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue;

use Psr\Log\LoggerInterface;
use Bunny\Client;

final class Queue
{
    private Client $client;
    private LoggerInterface $logger;
    private Worker $worker;

    public function __construct(Client $client, LoggerInterface $logger, Worker $worker)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->worker = $worker;
    }

    public function push(string $queueName, string $message): void
    {
        $this->client->connect();

        $channel = $this->client->channel();
        $channel->queueDeclare($queueName); // Queue name

        $channel->publish(
            $message, // $message,    // The message you're publishing as a string
            [],          // Any headers you want to add to the message
            '',          // Exchange name
            $queueName // Routing key, in this example the queue's name
        );
        $channel->close();

        $this->client->disconnect();
    }

    public function listen(string $queueName): void
    {
        $this->client->connect();

        $channel = $this->client->channel();
        $channel->queueDeclare($queueName); // Queue name

        $this->logger->info(" [*] Waiting for messages in «" . $queueName . "» queue. To exit press CTRL+C\n");

        $channel->run(
            $this->worker,
            $queueName,
        );
    }
}
