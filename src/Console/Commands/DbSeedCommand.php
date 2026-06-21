<?php

namespace Src\Console\Commands;

use Src\Console\Command;

class DbSeedCommand extends Command
{
    protected string $name        = 'db:seed';
    protected string $description = 'Run database seeders';

    public function handle(array $args): int
    {
        // --class=SomeSeeder  or  default DatabaseSeeder
        $class = 'DatabaseSeeder';
        foreach ($args as $arg) {
            if (str_starts_with($arg, '--class=')) {
                $class = substr($arg, 8);
            }
        }

        try {
            $projectRoot  = realpath(__DIR__ . '/../../../');
            $seedersDir   = $projectRoot . '/database/seeders';

            // Load all seeder files so classes are available
            foreach (glob("$seedersDir/*.php") as $file) {
                require_once $file;
            }

            if (!class_exists($class)) {
                $this->error("Seeder class '$class' not found in $seedersDir");
                return 1;
            }

            $this->line("Running seeders:\n");

            $seeder = new $class();
            $seeder->run();

            $this->success("Database seeded successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }
    }
}
