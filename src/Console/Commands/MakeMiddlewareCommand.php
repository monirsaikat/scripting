<?php

namespace Src\Console\Commands;

use Src\Console\Command;

class MakeMiddlewareCommand extends Command
{
    protected string $name = 'make:middleware';
    protected string $description = 'Create a new middleware class';

    public function handle(array $args): int
    {
        if (empty($args)) {
            $this->error('Middleware name is required');
            $this->info('Usage: php bin/artisan make:middleware AuthMiddleware');
            return 1;
        }

        $name = ucfirst($args[0]);
        $dir  = realpath(__DIR__ . '/../../../') . '/src/Middleware';
        $file = "$dir/{$name}.php";

        if (file_exists($file)) {
            $this->error("Middleware $name already exists");
            return 1;
        }

        file_put_contents($file, $this->stub($name));
        $this->success("Middleware created: src/Middleware/{$name}.php");

        return 0;
    }

    private function stub(string $name): string
    {
        return <<<PHP
<?php

namespace Src\Middleware;

class $name
{
    public function handle(): void
    {
        // Add your middleware logic here.
        // Call next() or redirect as needed.
    }
}
PHP;
    }
}
