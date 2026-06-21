<?php

namespace Src\Console;

use Src\Console\Commands\ListCommand;
use Src\Console\Commands\MakeControllerCommand;
use Src\Console\Commands\MakeMigrationCommand;
use Src\Console\Commands\MakeMiddlewareCommand;
use Src\Console\Commands\MakeModelCommand;
use Src\Console\Commands\MigrateCommand;
use Src\Console\Commands\MigrateRefreshCommand;
use Src\Console\Commands\MigrateRollbackCommand;
use Src\Console\Commands\MigrateStatusCommand;

class Kernel
{
    protected array $commands = [];

    public function __construct()
    {
        $this->registerCommands();
    }

    protected function registerCommands(): void
    {
        $this->register(new ListCommand());
        $this->register(new MakeControllerCommand());
        $this->register(new MakeMigrationCommand());
        $this->register(new MakeMiddlewareCommand());
        $this->register(new MakeModelCommand());
        $this->register(new MigrateCommand());
        $this->register(new MigrateRefreshCommand());
        $this->register(new MigrateRollbackCommand());
        $this->register(new MigrateStatusCommand());
    }

    public function register(Command $command): void
    {
        $this->commands[$command->getName()] = $command;
    }

    public function handle(array $argv): void
    {
        array_shift($argv);

        if (empty($argv)) {
            $this->showHelp();
            return;
        }

        $commandName = array_shift($argv);

        if ($commandName === 'help' || $commandName === '-h' || $commandName === '--help') {
            $this->showHelp();
            return;
        }

        if (!isset($this->commands[$commandName])) {
            echo "❌ Command '$commandName' not found\n";
            echo "\nRun 'php bin/artisan list' to see available commands\n";
            exit(1);
        }

        $command = $this->commands[$commandName];
        $exitCode = $command->handle($argv);
        exit($exitCode ?? 0);
    }

    public function showHelp(): void
    {
        echo "\n";
        echo "╔════════════════════════════════════════╗\n";
        echo "║   PHP Framework Console Application    ║\n";
        echo "╚════════════════════════════════════════╝\n";
        echo "\n";
        echo "Usage: php bin/artisan <command> [options]\n\n";
        echo "Available Commands:\n";

        foreach ($this->commands as $command) {
            if ($command->getName() !== 'list') {
                printf("  %-30s %s\n", $command->getName(), $command->getDescription());
            }
        }

        echo "\n";
    }

    public function getCommands(): array
    {
        return $this->commands;
    }
}
