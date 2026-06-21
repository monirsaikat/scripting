<?php

namespace Src\Console;

use Src\Database;

abstract class Command
{
    protected string $name = '';
    protected string $description = '';
    protected array $arguments = [];
    protected array $options = [];
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    abstract public function handle(array $args): int;

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function info(string $message): void
    {
        echo "ℹ️  $message\n";
    }

    public function success(string $message): void
    {
        echo "✅ $message\n";
    }

    public function error(string $message): void
    {
        echo "❌ $message\n";
    }

    public function warn(string $message): void
    {
        echo "⚠️  $message\n";
    }

    public function line(string $message = ''): void
    {
        echo "$message\n";
    }

    public function table(array $headers, array $rows): void
    {
        if (empty($rows)) {
            $this->info('No data to display');
            return;
        }

        $widths = array_fill(0, count($headers), 0);
        foreach ($headers as $i => $header) {
            $widths[$i] = strlen((string)$header);
        }

        foreach ($rows as $row) {
            foreach ($row as $i => $cell) {
                $widths[$i] = max($widths[$i], strlen((string)$cell));
            }
        }

        $this->printRow($headers, $widths);
        echo str_repeat('-', array_sum($widths) + count($widths) * 3 + 1) . "\n";

        foreach ($rows as $row) {
            $this->printRow($row, $widths);
        }
    }

    private function printRow(array $row, array $widths): void
    {
        echo '| ';
        foreach ($row as $i => $cell) {
            echo str_pad((string)$cell, $widths[$i]) . ' | ';
        }
        echo "\n";
    }
}
