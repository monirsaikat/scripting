<?php

namespace Src\Database;

use Src\Database;

class Schema
{
    // ══════════════════════════════════════════════════════════════════════════
    // DDL helpers
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Create a new table.
     *
     * Schema::create('users', function (Blueprint $table) {
     *     $table->id();
     *     $table->string('name');
     *     $table->timestamps();
     * });
     */
    public static function create(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        static::pdo()->exec($blueprint->toCreateSql());
    }

    /**
     * Modify an existing table (ADD / MODIFY / DROP / RENAME columns).
     *
     * Schema::table('users', function (Blueprint $table) {
     *     $table->string('avatar')->nullable()->after('email');
     *     $table->dropColumn('old_field');
     * });
     */
    public static function table(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        static::pdo()->exec($blueprint->toAlterSql());
    }

    /** Drop a table. */
    public static function drop(string $table): void
    {
        static::pdo()->exec("DROP TABLE `{$table}`");
    }

    /** Drop a table only if it exists. */
    public static function dropIfExists(string $table): void
    {
        static::pdo()->exec("DROP TABLE IF EXISTS `{$table}`");
    }

    /** Rename a table. */
    public static function rename(string $from, string $to): void
    {
        static::pdo()->exec("RENAME TABLE `{$from}` TO `{$to}`");
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Introspection helpers
    // ══════════════════════════════════════════════════════════════════════════

    /** Check whether a table exists in the current database. */
    public static function hasTable(string $table): bool
    {
        $stmt = static::pdo()->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        return $stmt->rowCount() > 0;
    }

    /** Check whether a column exists in a table. */
    public static function hasColumn(string $table, string $column): bool
    {
        $stmt = static::pdo()->prepare("SHOW COLUMNS FROM `{$table}` LIKE ?");
        $stmt->execute([$column]);
        return $stmt->rowCount() > 0;
    }

    /** Return all column names for a table. */
    public static function getColumns(string $table): array
    {
        $stmt = static::pdo()->query("SHOW COLUMNS FROM `{$table}`");
        return array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'Field');
    }

    /** Return all table names in the current database. */
    public static function getTables(): array
    {
        $stmt = static::pdo()->query("SHOW TABLES");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /** Disable foreign key checks (useful before truncate / refresh). */
    public static function disableForeignKeyChecks(): void
    {
        static::pdo()->exec("SET FOREIGN_KEY_CHECKS=0");
    }

    /** Re-enable foreign key checks. */
    public static function enableForeignKeyChecks(): void
    {
        static::pdo()->exec("SET FOREIGN_KEY_CHECKS=1");
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Internal
    // ══════════════════════════════════════════════════════════════════════════

    private static function pdo(): \PDO
    {
        return Database::getInstance()->getConnection();
    }
}
