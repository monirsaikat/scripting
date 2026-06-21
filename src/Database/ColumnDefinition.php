<?php

namespace Src\Database;

class ColumnDefinition
{
    private string  $name;
    private string  $type;

    private bool    $nullable       = false;
    private bool    $unsigned       = false;
    private bool    $autoInc        = false;
    private bool    $markedPrimary  = false;
    private bool    $markedUnique   = false;
    private bool    $hasDefault     = false;
    private mixed   $default        = null;
    private bool    $rawDefault     = false;   // value is a SQL keyword, not a string
    private bool    $onUpdate       = false;
    private string  $onUpdateVal    = 'CURRENT_TIMESTAMP';
    private ?string $afterColumn    = null;
    private bool    $firstPosition  = false;
    private ?string $commentText    = null;
    private bool    $modifyMode     = false;   // ALTER TABLE MODIFY instead of ADD

    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    // ── Modifiers ─────────────────────────────────────────────────────────────

    public function nullable(): static
    {
        $this->nullable = true;
        return $this;
    }

    public function unsigned(): static
    {
        $this->unsigned = true;
        return $this;
    }

    public function autoIncrement(): static
    {
        $this->autoInc = true;
        return $this;
    }

    public function primary(): static
    {
        $this->markedPrimary = true;
        return $this;
    }

    public function unique(): static
    {
        $this->markedUnique = true;
        return $this;
    }

    public function default(mixed $value): static
    {
        $this->hasDefault = true;
        $this->default    = $value;
        $this->rawDefault = false;
        return $this;
    }

    public function useCurrent(): static
    {
        $this->hasDefault = true;
        $this->default    = 'CURRENT_TIMESTAMP';
        $this->rawDefault = true;
        return $this;
    }

    public function useCurrentOnUpdate(): static
    {
        $this->onUpdate = true;
        return $this;
    }

    public function after(string $column): static
    {
        $this->afterColumn = $column;
        return $this;
    }

    public function first(): static
    {
        $this->firstPosition = true;
        return $this;
    }

    public function comment(string $text): static
    {
        $this->commentText = $text;
        return $this;
    }

    /** Mark this column as MODIFY COLUMN instead of ADD COLUMN in ALTER TABLE. */
    public function change(): static
    {
        $this->modifyMode = true;
        return $this;
    }

    // ── Getters (used by Blueprint) ───────────────────────────────────────────

    public function getName(): string  { return $this->name; }
    public function isMarkedPrimary(): bool { return $this->markedPrimary; }
    public function isMarkedUnique(): bool  { return $this->markedUnique; }
    public function isChangeMode(): bool    { return $this->modifyMode; }

    // ── SQL generation ────────────────────────────────────────────────────────

    /** Column definition fragment used inside CREATE TABLE or ALTER TABLE. */
    public function toSql(): string
    {
        $parts = ["`{$this->name}` {$this->type}"];

        if ($this->unsigned) {
            $parts[] = 'UNSIGNED';
        }

        $parts[] = $this->nullable ? 'NULL' : 'NOT NULL';

        if ($this->autoInc) {
            $parts[] = 'AUTO_INCREMENT';
        }

        if ($this->hasDefault) {
            $val = $this->rawDefault
                ? $this->default
                : (is_null($this->default)
                    ? 'NULL'
                    : (is_string($this->default)
                        ? "'" . addslashes((string) $this->default) . "'"
                        : (string) $this->default));
            $parts[] = "DEFAULT {$val}";
        }

        if ($this->onUpdate) {
            $parts[] = "ON UPDATE {$this->onUpdateVal}";
        }

        if ($this->commentText !== null) {
            $parts[] = "COMMENT '" . addslashes($this->commentText) . "'";
        }

        return implode(' ', $parts);
    }

    /** Full ALTER TABLE clause fragment (ADD COLUMN / MODIFY COLUMN). */
    public function toAlterSql(): string
    {
        $action = $this->modifyMode ? 'MODIFY COLUMN' : 'ADD COLUMN';
        $sql    = "{$action} " . $this->toSql();

        if ($this->firstPosition) {
            $sql .= ' FIRST';
        } elseif ($this->afterColumn !== null) {
            $sql .= " AFTER `{$this->afterColumn}`";
        }

        return $sql;
    }
}
