<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue;

use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;
use Psr\Log\LoggerInterface;

final class Worker
{
    private LoggerInterface $logger;
    private Dispatcher $dispatcher;

    public function __construct(LoggerInterface $logger, Dispatcher $dispatcher)
    {
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
    }

    public function __invoke(Message $message, Channel $channel, Client $bunny): void
    {
        $this->logger->info(" [·] Received «" . $message->content . "»\n");

        // Handle your message here
        $success = $this->dispatcher->dispatch($message);

        if ($success) {
            $channel->ack($message); // Acknowledge message
            $this->logger->info(" [✓] Succeed «" . $message->content . "»\n");

            return;
        }

        $channel->nack($message); // Mark message fail, message will be redelivered

        $this->logger->info(" [×] Failed «" . $message->content . "»\n");
    }
}
