<?php

namespace Src\Console\Commands;

use Src\Console\Command;

class MakeMigrationCommand extends Command
{
    protected string $name = 'make:migration';
    protected string $description = 'Create a new migration file';

    public function handle(array $args): int
    {
        if (empty($args)) {
            $this->error('Migration name is required');
            $this->info('Usage: php bin/artisan make:migration <name>');
            return 1;
        }

        $migrationName = $args[0];

        if (!preg_match('/^[a-z0-9_]+$/', $migrationName)) {
            $this->error('Migration name must contain only lowercase letters, numbers, and underscores');
            return 1;
        }

        $projectRoot = realpath(__DIR__ . '/../../../');
        $migrationsDir = $projectRoot . '/database/migrations';
        if (!is_dir($migrationsDir)) {
            mkdir($migrationsDir, 0755, true);
        }

        $timestamp = date('Y_m_d_His');
        $filename = "{$timestamp}_{$migrationName}.php";
        $filepath = $migrationsDir . '/' . $filename;

        $stub = $this->getStub($migrationName);
        file_put_contents($filepath, $stub);

        $this->success("Migration created: $filename");
        $this->info("File: $filepath");

        return 0;
    }

    private function getStub(string $name): string
    {
        $className = $this->studlyCase($name);
        $table     = $this->guessTableName($name);

        return <<<PHP
<?php

use Src\Database\Schema;
use Src\Database\Blueprint;

class $className
{
    public function up(): void
    {
        Schema::create('$table', function (Blueprint \$table) {
            \$table->id();
            // \$table->string('name');
            // \$table->string('email')->unique();
            // \$table->text('body')->nullable();
            // \$table->boolean('active')->default(true);
            // \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('$table');
    }
}
PHP;
    }

    private function guessTableName(string $migrationName): string
    {
        // create_posts_table  → posts
        // add_column_to_users → users
        if (preg_match('/^create_(.+?)_table$/', $migrationName, $m)) {
            return $m[1];
        }
        if (preg_match('/(?:to|from|in)_(.+?)(?:_table)?$/', $migrationName, $m)) {
            return $m[1];
        }
        return 'table_name';
    }

    private function studlyCase(string $str): string
    {
        return str_replace(
            ' ',
            '',
            ucwords(str_replace('_', ' ', $str))
        );
    }
}
