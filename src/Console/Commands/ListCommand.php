<?php

namespace Src\Console\Commands;

use Src\Console\Command;
use Src\Console\Kernel;

class ListCommand extends Command
{
    protected string $name = 'list';
    protected string $description = 'List all available commands';

    protected ?Kernel $kernel = null;

    public function setKernel(Kernel $kernel): void
    {
        $this->kernel = $kernel;
    }

    public function handle(array $args): int
    {
        global $kernel;

        $source = $this->kernel ?? $kernel ?? null;

        $this->line("\nAvailable Commands:\n");

        $rows = [];
        foreach (($source ? $source->getCommands() : []) as $cmd) {
            $rows[] = [$cmd->getName(), $cmd->getDescription()];
        }

        if (empty($rows)) {
            $this->info('No commands registered');
            return 0;
        }

        $this->table(['Command', 'Description'], $rows);
        $this->line('');

        return 0;
    }
}
