<?php

namespace Src\Console\Commands;

use Src\Console\Command;

class ListCommand extends Command
{
    protected string $name = 'list';
    protected string $description = 'List all available commands';

    public function handle(array $args): int
    {
        global $kernel;

        $this->line("\nAvailable Commands:\n");

        $rows = [];
        foreach ($kernel->getCommands() ?? [] as $cmd) {
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
