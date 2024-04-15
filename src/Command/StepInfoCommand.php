<?php

namespace App\Command;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(
    name: 'app:step:info',
    description: 'Get current git step info',
)]
class StepInfoCommand extends Command
{
    public function __construct(
        private readonly CacheInterface $cache,
    )
    {
        parent::__construct();
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $step = $this->cache->get('app.current_step', function ($item) {
            $process = new Process(['git', 'tag', '-l', '--points-at', 'HEAD']);
            $process->mustRun();
            $item->expiresAfter(30);
        });

        $output->writeln($step);

        return Command::SUCCESS;
    }
}
