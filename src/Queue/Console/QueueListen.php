<?php

declare(strict_types=1);

namespace Zorachka\Framework\Queue\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zorachka\Framework\Queue\Transport\Bunny\Queue;

final class QueueListen extends Command
{
    protected static $defaultName = 'queue:listen';
    protected static $defaultDescription = 'Listen for queue messages';

    private Queue $queue;

    public function __construct(Queue $queue)
    {
        parent::__construct();
        $this->queue = $queue;
    }

    public function configure(): void
    {
        $this
            ->addArgument('queueName', InputArgument::REQUIRED, 'Queue name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $queueName */
        $queueName = $input->getArgument('queueName');
        $this->queue->listen($queueName);

        return Command::SUCCESS;
    }
}
