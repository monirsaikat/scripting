<?php

namespace Src\Console\Commands;

use Src\Console\Command;

class MakeModelCommand extends Command
{
    protected string $name = 'make:model';
    protected string $description = 'Create a new model class';

    public function handle(array $args): int
    {
        if (empty($args)) {
            $this->error('Model name is required');
            $this->info('Usage: php bin/artisan make:model Post');
            return 1;
        }

        $name = ucfirst($args[0]);
        $dir  = realpath(__DIR__ . '/../../../') . '/src/Models';
        $file = "$dir/{$name}.php";

        if (file_exists($file)) {
            $this->error("Model $name already exists");
            return 1;
        }

        $withMigration = in_array('-m', $args) || in_array('--migration', $args);

        file_put_contents($file, $this->stub($name));
        $this->success("Model created: src/Models/{$name}.php");

        if ($withMigration) {
            $table     = strtolower($name) . 's';
            $timestamp = date('Y_m_d_His');
            $migFile   = realpath(__DIR__ . '/../../../') . "/database/migrations/{$timestamp}_create_{$table}_table.php";
            $className = 'Create' . $name . 'sTable';

            file_put_contents($migFile, $this->migrationStub($className, $table));
            $this->success("Migration created: database/migrations/{$timestamp}_create_{$table}_table.php");
        }

        return 0;
    }

    private function stub(string $name): string
    {
        $table = strtolower($name) . 's';

        return <<<PHP
<?php

namespace Src\Models;

class $name extends Model
{
    protected \$table    = '$table';
    protected \$fillable = [];
    protected \$hidden   = [];
}
PHP;
    }

    private function migrationStub(string $className, string $table): string
    {
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
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('$table');
    }
}
PHP;
    }
}
