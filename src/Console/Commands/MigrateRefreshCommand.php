<?php

namespace Src\Console\Commands;

class MigrateRefreshCommand extends MigrateCommand
{
    protected string $name = 'migrate:refresh';
    protected string $description = 'Reset and re-run all migrations';

    public function handle(array $args): int
    {
        $seed = in_array('--seed', $args, true);

        try {
            $this->ensureMigrationsTable();

            $projectRoot = realpath(__DIR__ . '/../../../');
            $migrationsDir = $projectRoot . '/database/migrations';

            if (!is_dir($migrationsDir)) {
                $this->warn('No migrations directory found');
                return 0;
            }

            $this->line("\nRolling back all migrations:\n");

            $pdo = $this->db->getConnection();
            $allMigrations = $this->getAllMigrations();

            foreach (array_reverse($allMigrations) as $migration) {
                $filepath = $migrationsDir . '/' . $migration;
                if (file_exists($filepath)) {
                    $this->runMigration($filepath, $pdo, 1, 'down');
                }
            }

            $this->line("\nRunning all migrations:\n");

            $allFiles = array_filter(
                glob("$migrationsDir/*.php"),
                fn($f) => is_file($f)
            );
            sort($allFiles);

            $batch = 1;
            foreach ($allFiles as $filepath) {
                $this->runMigration($filepath, $pdo, $batch, 'up');
            }

            $this->success("Database refresh completed successfully!");

            if ($seed) {
                $this->line('');
                (new DbSeedCommand())->handle([]);
            }

            return 0;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }
    }

    protected function getAllMigrations(): array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->query("SELECT migration FROM migrations ORDER BY batch DESC");
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        return array_column($rows, 'migration');
    }
}
