<?php

namespace Src\Database;

class Blueprint
{
    private string $table;
    private array  $columns        = [];
    private array  $drops          = [];
    private array  $renames        = [];
    private array  $compositeIndexes = [];
    private array  $foreignKeys    = [];
    private string $engine         = 'InnoDB';
    private string $charset        = 'utf8mb4';
    private string $collation      = 'utf8mb4_unicode_ci';

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Integer types
    // ══════════════════════════════════════════════════════════════════════════

    /** Auto-increment unsigned INT primary key. */
    public function id(string $name = 'id'): ColumnDefinition
    {
        return $this->column($name, 'INT')
            ->unsigned()
            ->autoIncrement()
            ->primary();
    }

    /** Auto-increment unsigned BIGINT primary key. */
    public function bigId(string $name = 'id'): ColumnDefinition
    {
        return $this->column($name, 'BIGINT')
            ->unsigned()
            ->autoIncrement()
            ->primary();
    }

    public function integer(string $name): ColumnDefinition
    {
        return $this->column($name, 'INT');
    }

    public function tinyInteger(string $name): ColumnDefinition
    {
        return $this->column($name, 'TINYINT');
    }

    public function smallInteger(string $name): ColumnDefinition
    {
        return $this->column($name, 'SMALLINT');
    }

    public function mediumInteger(string $name): ColumnDefinition
    {
        return $this->column($name, 'MEDIUMINT');
    }

    public function bigInteger(string $name): ColumnDefinition
    {
        return $this->column($name, 'BIGINT');
    }

    /** Unsigned BIGINT — for storing foreign key IDs. */
    public function foreignId(string $name): ColumnDefinition
    {
        return $this->column($name, 'BIGINT')->unsigned();
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Decimal / float types
    // ══════════════════════════════════════════════════════════════════════════

    public function float(string $name, int $precision = 8, int $scale = 2): ColumnDefinition
    {
        return $this->column($name, "FLOAT({$precision},{$scale})");
    }

    public function double(string $name, int $precision = 15, int $scale = 8): ColumnDefinition
    {
        return $this->column($name, "DOUBLE({$precision},{$scale})");
    }

    public function decimal(string $name, int $precision = 8, int $scale = 2): ColumnDefinition
    {
        return $this->column($name, "DECIMAL({$precision},{$scale})");
    }

    // ══════════════════════════════════════════════════════════════════════════
    // String types
    // ══════════════════════════════════════════════════════════════════════════

    public function string(string $name, int $length = 255): ColumnDefinition
    {
        return $this->column($name, "VARCHAR({$length})");
    }

    public function char(string $name, int $length = 1): ColumnDefinition
    {
        return $this->column($name, "CHAR({$length})");
    }

    public function text(string $name): ColumnDefinition
    {
        return $this->column($name, 'TEXT');
    }

    public function mediumText(string $name): ColumnDefinition
    {
        return $this->column($name, 'MEDIUMTEXT');
    }

    public function longText(string $name): ColumnDefinition
    {
        return $this->column($name, 'LONGTEXT');
    }

    public function enum(string $name, array $values): ColumnDefinition
    {
        $list = implode(', ', array_map(
            fn($v) => "'" . addslashes($v) . "'",
            $values
        ));
        return $this->column($name, "ENUM({$list})");
    }

    public function set(string $name, array $values): ColumnDefinition
    {
        $list = implode(', ', array_map(
            fn($v) => "'" . addslashes($v) . "'",
            $values
        ));
        return $this->column($name, "SET({$list})");
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Boolean / binary / JSON
    // ══════════════════════════════════════════════════════════════════════════

    public function boolean(string $name): ColumnDefinition
    {
        return $this->column($name, 'TINYINT(1)');
    }

    public function json(string $name): ColumnDefinition
    {
        return $this->column($name, 'JSON');
    }

    public function binary(string $name): ColumnDefinition
    {
        return $this->column($name, 'BLOB');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Date / time types
    // ══════════════════════════════════════════════════════════════════════════

    public function date(string $name): ColumnDefinition
    {
        return $this->column($name, 'DATE');
    }

    public function time(string $name): ColumnDefinition
    {
        return $this->column($name, 'TIME');
    }

    public function dateTime(string $name): ColumnDefinition
    {
        return $this->column($name, 'DATETIME');
    }

    public function timestamp(string $name): ColumnDefinition
    {
        return $this->column($name, 'TIMESTAMP');
    }

    public function year(string $name): ColumnDefinition
    {
        return $this->column($name, 'YEAR');
    }

    /**
     * Add created_at and updated_at TIMESTAMP columns.
     */
    public function timestamps(): void
    {
        $this->column('created_at', 'TIMESTAMP')
            ->nullable()
            ->useCurrent();

        $this->column('updated_at', 'TIMESTAMP')
            ->nullable()
            ->useCurrent()
            ->useCurrentOnUpdate();
    }

    /**
     * Add a nullable deleted_at TIMESTAMP column for soft deletes.
     */
    public function softDeletes(string $column = 'deleted_at'): ColumnDefinition
    {
        return $this->column($column, 'TIMESTAMP')->nullable();
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Indexes
    // ══════════════════════════════════════════════════════════════════════════

    /** Add a composite PRIMARY KEY. */
    public function primary(array $columns, ?string $name = null): void
    {
        $cols = '`' . implode('`, `', $columns) . '`';
        $this->compositeIndexes[] = "PRIMARY KEY ({$cols})";
    }

    /** Add a composite UNIQUE KEY. */
    public function unique(array $columns, ?string $name = null): void
    {
        $name ??= $this->table . '_' . implode('_', $columns) . '_unique';
        $cols  = '`' . implode('`, `', $columns) . '`';
        $this->compositeIndexes[] = "UNIQUE KEY `{$name}` ({$cols})";
    }

    /** Add a composite index. */
    public function index(array $columns, ?string $name = null): void
    {
        $name ??= $this->table . '_' . implode('_', $columns) . '_index';
        $cols  = '`' . implode('`, `', $columns) . '`';
        $this->compositeIndexes[] = "KEY `{$name}` ({$cols})";
    }

    /** Add a FULLTEXT index. */
    public function fulltext(array $columns, ?string $name = null): void
    {
        $name ??= $this->table . '_' . implode('_', $columns) . '_fulltext';
        $cols  = '`' . implode('`, `', $columns) . '`';
        $this->compositeIndexes[] = "FULLTEXT KEY `{$name}` ({$cols})";
    }

    /** Define a foreign key constraint. Returns a fluent ForeignKeyDefinition. */
    public function foreign(string $column): ForeignKeyDefinition
    {
        $fk = new ForeignKeyDefinition($column, $this->table);
        $this->foreignKeys[] = $fk;
        return $fk;
    }

    // ══════════════════════════════════════════════════════════════════════════
    // ALTER TABLE operations
    // ══════════════════════════════════════════════════════════════════════════

    public function dropColumn(string|array $columns): void
    {
        foreach ((array) $columns as $col) {
            $this->drops[] = $col;
        }
    }

    public function renameColumn(string $from, string $to): void
    {
        $this->renames[] = [$from, $to];
    }

    public function dropForeign(string $name): void
    {
        $this->drops[] = "__fk__{$name}"; // sentinel prefix handled in toAlterSql
    }

    public function dropUnique(string $name): void
    {
        $this->drops[] = "__uk__{$name}";
    }

    public function dropIndex(string $name): void
    {
        $this->drops[] = "__idx__{$name}";
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Table options
    // ══════════════════════════════════════════════════════════════════════════

    public function engine(string $engine): static
    {
        $this->engine = $engine;
        return $this;
    }

    public function charset(string $charset): static
    {
        $this->charset = $charset;
        return $this;
    }

    public function collation(string $collation): static
    {
        $this->collation = $collation;
        return $this;
    }

    // ══════════════════════════════════════════════════════════════════════════
    // SQL generation
    // ══════════════════════════════════════════════════════════════════════════

    public function toCreateSql(): string
    {
        $lines   = [];
        $pkCols  = [];

        foreach ($this->columns as $col) {
            $lines[] = '    ' . $col->toSql();
            if ($col->isMarkedPrimary()) {
                $pkCols[] = $col->getName();
            }
        }

        if (!empty($pkCols)) {
            $pk      = '`' . implode('`, `', $pkCols) . '`';
            $lines[] = "    PRIMARY KEY ({$pk})";
        }

        foreach ($this->columns as $col) {
            if ($col->isMarkedUnique()) {
                $uName   = $this->table . '_' . $col->getName() . '_unique';
                $lines[] = "    UNIQUE KEY `{$uName}` (`{$col->getName()}`)";
            }
        }

        foreach ($this->compositeIndexes as $idx) {
            $lines[] = "    {$idx}";
        }

        foreach ($this->foreignKeys as $fk) {
            $lines[] = '    ' . $fk->toSql();
        }

        $body = implode(",\n", $lines);

        return sprintf(
            "CREATE TABLE `%s` (\n%s\n) ENGINE=%s DEFAULT CHARSET=%s COLLATE=%s",
            $this->table,
            $body,
            $this->engine,
            $this->charset,
            $this->collation
        );
    }

    public function toAlterSql(): string
    {
        $parts = [];

        foreach ($this->columns as $col) {
            $parts[] = $col->toAlterSql();

            if ($col->isMarkedUnique()) {
                $uName   = $this->table . '_' . $col->getName() . '_unique';
                $parts[] = "ADD UNIQUE KEY `{$uName}` (`{$col->getName()}`)";
            }
        }

        foreach ($this->drops as $item) {
            if (str_starts_with($item, '__fk__')) {
                $parts[] = 'DROP FOREIGN KEY `' . substr($item, 6) . '`';
            } elseif (str_starts_with($item, '__uk__')) {
                $parts[] = 'DROP KEY `' . substr($item, 6) . '`';
            } elseif (str_starts_with($item, '__idx__')) {
                $parts[] = 'DROP KEY `' . substr($item, 7) . '`';
            } else {
                $parts[] = "DROP COLUMN `{$item}`";
            }
        }

        foreach ($this->renames as [$from, $to]) {
            $parts[] = "RENAME COLUMN `{$from}` TO `{$to}`";
        }

        foreach ($this->compositeIndexes as $idx) {
            $parts[] = "ADD {$idx}";
        }

        foreach ($this->foreignKeys as $fk) {
            $parts[] = 'ADD ' . $fk->toSql();
        }

        return "ALTER TABLE `{$this->table}` " . implode(', ', $parts);
    }

    // ══════════════════════════════════════════════════════════════════════════
    // Private
    // ══════════════════════════════════════════════════════════════════════════

    private function column(string $name, string $type): ColumnDefinition
    {
        $col = new ColumnDefinition($name, $type);
        $this->columns[] = $col;
        return $col;
    }

    public function getColumns(): array { return $this->columns; }
    public function getTable(): string  { return $this->table; }
}
