<?php

namespace Src\Console\Commands;

use Exception;

class MigrateRollbackCommand extends MigrateCommand
{
    protected string $name = 'migrate:rollback';
    protected string $description = 'Rollback the last batch of migrations';

    public function handle(array $args): int
    {
        try {
            $this->ensureMigrationsTable();

            $lastBatch = $this->getLastBatch();

            if ($lastBatch === null) {
                $this->info('No migrations to rollback');
                return 0;
            }

            $migrationsDir = __DIR__ . '/../../..database/migrations';
            $migrationsInBatch = $this->getMigrationsInBatch($lastBatch);

            if (empty($migrationsInBatch)) {
                $this->info('No migrations found for rollback');
                return 0;
            }

            $this->line("\nRolling back batch $lastBatch:\n");

            $pdo = $this->db->getConnection();

            foreach (array_reverse($migrationsInBatch) as $migration) {
                $filepath = $migrationsDir . '/' . $migration;
                if (file_exists($filepath)) {
                    $this->runMigration($filepath, $pdo, $lastBatch, 'down');
                }
            }

            $this->success("Rollback completed successfully!");
            return 0;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }
    }

    protected function getLastBatch(): ?int
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->query("SELECT MAX(batch) as batch FROM migrations");
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        return $result['batch'] ?? null;
    }

    protected function getMigrationsInBatch(int $batch): array
    {
        $pdo = $this->db->getConnection();
        $stmt = $pdo->prepare("SELECT migration FROM migrations WHERE batch = ? ORDER BY id DESC");
        $stmt->execute([$batch]);
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        return array_column($rows, 'migration');
    }
}
