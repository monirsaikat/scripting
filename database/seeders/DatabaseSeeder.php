<?php

use Src\Console\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(
            AdminSeeder::class,
            UserSeeder::class,
        );
    }
}
