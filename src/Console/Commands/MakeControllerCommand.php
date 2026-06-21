<?php

namespace Src\Console\Commands;

use Src\Console\Command;

class MakeControllerCommand extends Command
{
    protected string $name = 'make:controller';
    protected string $description = 'Create a new controller class';

    public function handle(array $args): int
    {
        if (empty($args)) {
            $this->error('Controller name is required');
            $this->info('Usage: php bin/artisan make:controller UserController');
            return 1;
        }

        $name = $args[0];
        $name = str_ends_with($name, 'Controller') ? $name : $name . 'Controller';

        $dir  = realpath(__DIR__ . '/../../../') . '/src/Controllers';
        $file = "$dir/{$name}.php";

        if (file_exists($file)) {
            $this->error("Controller $name already exists");
            return 1;
        }

        $resource = in_array('--resource', $args) || in_array('-r', $args);

        file_put_contents($file, $resource ? $this->resourceStub($name) : $this->basicStub($name));

        $this->success("Controller created: src/Controllers/{$name}.php");
        return 0;
    }

    private function basicStub(string $name): string
    {
        return <<<PHP
<?php

namespace Src\Controllers;

use Src\Attributes\Route;

class $name extends Controller
{
    #[Route('GET', '/example')]
    public function index()
    {
        \$this->render('example/index', []);
    }
}
PHP;
    }

    private function resourceStub(string $name): string
    {
        $base = strtolower(str_replace('Controller', '', $name));

        return <<<PHP
<?php

namespace Src\Controllers;

use Src\Attributes\Route;

class $name extends Controller
{
    #[Route('GET', '/{$base}s', '{$base}.index')]
    public function index()
    {
        \$items = \$this->db()->from('{$base}s')->latest()->paginate(15);
        \$this->render('{$base}s/index', compact('items'));
    }

    #[Route('POST', '/{$base}s', '{$base}.store')]
    public function store()
    {
        // Validate and insert
        redirect('/{$base}s');
    }

    #[Route('GET', '/{$base}s/{id}', '{$base}.show')]
    public function show(\$id)
    {
        \$item = \$this->db()->from('{$base}s')->where('id', '=', \$id)->first();
        \$this->render('{$base}s/show', compact('item'));
    }

    #[Route('POST', '/{$base}s/{id}/update', '{$base}.update')]
    public function update(\$id)
    {
        // Validate and update
        redirect('/{$base}s');
    }

    #[Route('POST', '/{$base}s/{id}/delete', '{$base}.delete')]
    public function delete(\$id)
    {
        \$this->db()->delete('{$base}s', ['id' => \$id]);
        redirect('/{$base}s');
    }
}
PHP;
    }
}
