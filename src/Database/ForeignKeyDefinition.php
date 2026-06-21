<?php

namespace Src\Database;

class ForeignKeyDefinition
{
    private string  $column;
    private string  $ownerTable;
    private ?string $refTable   = null;
    private ?string $refColumn  = null;
    private string  $onDelete   = 'RESTRICT';
    private string  $onUpdate   = 'RESTRICT';
    private ?string $customName = null;

    public function __construct(string $column, string $ownerTable)
    {
        $this->column     = $column;
        $this->ownerTable = $ownerTable;
    }

    public function references(string $column): static
    {
        $this->refColumn = $column;
        return $this;
    }

    public function on(string $table): static
    {
        $this->refTable = $table;
        return $this;
    }

    public function onDelete(string $action): static
    {
        $this->onDelete = strtoupper($action);
        return $this;
    }

    public function onUpdate(string $action): static
    {
        $this->onUpdate = strtoupper($action);
        return $this;
    }

    public function name(string $name): static
    {
        $this->customName = $name;
        return $this;
    }

    public function cascadeOnDelete(): static { return $this->onDelete('CASCADE'); }
    public function nullOnDelete(): static    { return $this->onDelete('SET NULL'); }
    public function restrictOnDelete(): static { return $this->onDelete('RESTRICT'); }

    public function toSql(): string
    {
        $name = $this->customName
            ?? "fk_{$this->ownerTable}_{$this->column}";

        return sprintf(
            'CONSTRAINT `%s` FOREIGN KEY (`%s`) REFERENCES `%s` (`%s`) ON DELETE %s ON UPDATE %s',
            $name,
            $this->column,
            $this->refTable,
            $this->refColumn,
            $this->onDelete,
            $this->onUpdate
        );
    }
}
