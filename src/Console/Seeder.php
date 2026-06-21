<?php

namespace Src\Console;

use Src\Database;

abstract class Seeder
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    abstract public function run(): void;

    protected function call(string ...$classes): void
    {
        foreach ($classes as $class) {
            $seeder = new $class();
            $seeder->run();
            echo "  ✅ Seeded: $class\n";
        }
    }
}
