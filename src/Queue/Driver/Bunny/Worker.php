<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue\Driver\Bunny;

use Bunny\Channel;
use Bunny\Client;
use Bunny\Message;
use Psr\Log\LoggerInterface;
use Throwable;

final class Worker
{
    /**
     * @var callable
     */
    private $callback;
    private LoggerInterface $logger;

    public function __construct(
        LoggerInterface $logger,
        callable $callback,
    ) {
        $this->callback = $callback;
        $this->logger = $logger;
    }

    public function __invoke(Message $message, Channel $channel, Client $client)
    {
        // Handle your message here
        try {
            $callback = $this->callback;
            $callback(new \Zorachka\Framework\Queue\Message('', $message->content));

            $channel->ack($message); // Acknowledge message
            $this->logger->debug(" [✓] Succeed «" . $message->content . "»\n");

            return;
        } catch (Throwable $throwable) {
            $channel->nack($message); // Mark message fail, message will be redelivered

            $this->logger->error(" [×] Failed «" . $message->content . "», because of " . $throwable->getMessage() ." \n");
        }
    }
}
