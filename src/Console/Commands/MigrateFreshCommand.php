<?php

namespace Src\Console\Commands;

use PDO;

class MigrateFreshCommand extends MigrateCommand
{
    protected string $name        = 'migrate:fresh';
    protected string $description = 'Drop all tables and re-run all migrations';

    public function handle(array $args): int
    {
        $seed = in_array('--seed', $args, true);

        try {
            $pdo    = $this->db->getConnection();
            $dbName = $pdo->query("SELECT DATABASE()")->fetchColumn();

            // ── Drop all tables ───────────────────────────
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

            $tables = $pdo->query(
                "SELECT TABLE_NAME FROM information_schema.TABLES
                 WHERE TABLE_SCHEMA = " . $pdo->quote($dbName)
            )->fetchAll(PDO::FETCH_COLUMN);

            if ($tables) {
                $this->line("\nDropping " . count($tables) . " table(s):\n");
                foreach ($tables as $table) {
                    $pdo->exec("DROP TABLE IF EXISTS `$table`");
                    echo "  🗑  $table\n";
                }
            }

            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

            // ── Run all migrations ────────────────────────
            $this->line("\nRunning migrations:\n");

            $projectRoot   = realpath(__DIR__ . '/../../../');
            $migrationsDir = $projectRoot . '/database/migrations';

            if (!is_dir($migrationsDir)) {
                $this->warn('No migrations directory found');
                return 0;
            }

            $this->ensureMigrationsTable();

            $files = array_filter(glob("$migrationsDir/*.php"), 'is_file');
            sort($files);

            foreach ($files as $file) {
                $this->runMigration($file, $pdo, 1, 'up');
            }

            $this->success("Database wiped and migrated successfully!");

            // ── Optional seed ─────────────────────────────
            if ($seed) {
                $this->line('');
                $seeder = new DbSeedCommand();
                $seeder->handle([]);
            }

            return 0;
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }
    }
}
