<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue\Console;

use Zorachka\Framework\Queue\Queue;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class QueueListen extends Command
{
    protected static $defaultName = 'queue:listen';
    private Queue $queue;

    public function __construct(Queue $queue)
    {
        parent::__construct();
        $this->queue = $queue;
    }

    public function configure(): void
    {
        $this
            ->setDescription('Listen for queue messages');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->queue->listen('events');

        return 0;
    }
}
