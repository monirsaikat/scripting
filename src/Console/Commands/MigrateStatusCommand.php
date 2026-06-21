<?php

namespace Src\Console\Commands;

use Src\Console\Command;

class MigrateStatusCommand extends Command
{
    protected string $name = 'migrate:status';
    protected string $description = 'Show the status of each migration';

    public function handle(array $args): int
    {
        $migrationsDir = realpath(__DIR__ . '/../../../') . '/database/migrations';

        if (!is_dir($migrationsDir)) {
            $this->warn('No migrations directory found');
            return 0;
        }

        $files = array_filter(glob("$migrationsDir/*.php"), 'is_file');
        sort($files);

        if (empty($files)) {
            $this->info('No migration files found');
            return 0;
        }

        $executed = $this->getExecutedMigrations();

        $rows = [];
        foreach ($files as $file) {
            $name  = basename($file);
            $ran   = in_array($name, array_column($executed, 'migration'));
            $batch = '';

            if ($ran) {
                foreach ($executed as $row) {
                    if ($row['migration'] === $name) {
                        $batch = $row['batch'];
                        break;
                    }
                }
            }

            $rows[] = [
                $ran ? '✅ Ran' : '⏳ Pending',
                $name,
                $batch ?: '-',
            ];
        }

        $this->line('');
        $this->table(['Status', 'Migration', 'Batch'], $rows);
        $this->line('');

        return 0;
    }

    private function getExecutedMigrations(): array
    {
        try {
            $pdo  = $this->db->getConnection();
            $stmt = $pdo->query("SELECT migration, batch FROM migrations ORDER BY batch, id");
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
        } catch (\Exception) {
            return [];
        }
    }
}
