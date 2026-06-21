<?php

namespace Src\Console\Commands;

use Src\Console\Command;

class MigrateCommand extends Command
{
    protected string $name = 'migrate';
    protected string $description = 'Run pending migrations';

    public function handle(array $args): int
    {
        try {
            $projectRoot = realpath(__DIR__ . '/../../../');
            $migrationsDir = $projectRoot . '/database/migrations';

            if (!is_dir($migrationsDir)) {
                $this->warn('No migrations directory found');
                return 0;
            }

            $this->ensureMigrationsTable();

            $pendingMigrations = $this->getPendingMigrations($migrationsDir);

            if (empty($pendingMigrations)) {
                $this->info('No pending migrations');
                return 0;
            }

            $this->line("\nRunning migrations:\n");

            $pdo = $this->db->getConnection();
            $batch = $this->getNextBatch();

            foreach ($pendingMigrations as $migration) {
                $this->runMigration($migration, $pdo, $batch, 'up');
            }

            $this->success("All migrations completed successfully!");
            return 0;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }
    }

    protected function runMigration(string $filepath, $pdo, int $batch, string $direction): void
    {
        $filename = basename($filepath);
        $classname = $this->getClassNameFromFile($filepath);

        require_once $filepath;

        $migration = new $classname();

        try {
            if ($direction === 'up') {
                $migration->up();
                $this->recordMigration($filename, $batch);
                echo "  ✅ $filename\n";
            } else {
                $migration->down();
                $this->removeMigrationRecord($filename);
                echo "  ✅ Rolled back: $filename\n";
            }
        } catch (\Exception $e) {
            throw new \Exception("Failed to run migration $filename: " . $e->getMessage());
        }
    }

    protected function getPendingMigrations(string $dir): array
    {
        $files = array_filter(
            glob("$dir/*.php"),
            fn($f) => is_file($f)
        );

        sort($files);

        $executed = $this->getExecutedMigrations();

        return array_filter($files, fn($f) => !in_array(basename($f), $executed));
    }

    protected function getExecutedMigrations(): array
    {
        try {
            $pdo = $this->db->getConnection();
            $stmt = $pdo->query("SELECT migration FROM migrations");
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();
            return array_column($rows, 'migration');
        } catch (\Exception) {
            return [];
        }
    }

    protected function recordMigration(string $migration, int $batch): void
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute([$migration, $batch]);
    }

    protected function removeMigrationRecord(string $migration): void
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("DELETE FROM migrations WHERE migration = ?");
        $stmt->execute([$migration]);
    }

    protected function ensureMigrationsTable(): void
    {
        $pdo = $this->db->getConnection();

        try {
            $pdo->query("SELECT 1 FROM migrations LIMIT 1");
        } catch (\Exception) {
            try {
                $pdo->exec(
                    "CREATE TABLE migrations (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        migration VARCHAR(255) NOT NULL UNIQUE,
                        batch INT NOT NULL,
                        executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    )"
                );
                $this->info("Created migrations table");
            } catch (\Exception $e) {
                throw new \Exception("Failed to create migrations table: " . $e->getMessage());
            }
        }
    }

    protected function getNextBatch(): int
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->query("SELECT MAX(batch) as batch FROM migrations");
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        return ($result['batch'] ?? 0) + 1;
    }

    protected function getClassNameFromFile(string $filepath): string
    {
        $content = file_get_contents($filepath);
        preg_match('/class\s+(\w+)/', $content, $matches);
        return $matches[1] ?? '';
    }
}

