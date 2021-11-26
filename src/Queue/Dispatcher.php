<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue;

use Bunny\Message;

final class Dispatcher
{
    /**
     * @inheritDoc
     */
    public function dispatch(Message $message): bool
    {
        \var_dump('Message: ', $message);

        $data = \unserialize($message->content);
        $listener = $data['listener'];
        $event = $data['event'];

        \var_dump($data);

        $result = $listener($event);

        \var_dump($result);

        return true;
    }
}
