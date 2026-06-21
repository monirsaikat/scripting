<?php

namespace Src;

use PDO;
use PDOException;
use Exception;
use Src\Exceptions\AppException;

class Database
{
    private $pdo;
    private static $instance;

    private $query;
    private $params;

    protected $perPage = 15;

    protected $table;

    protected $page = 1;

    private function __construct()
    {
        $this->page = @$_GET['page'] ?? 1;

        try {
            switch (app()->getConfig('db')['schema']) {
                case 'mysql':
                    $this->setupMysql();
                default:
                    $this->setupMysql();
            }


            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            $this->query = '';
            $this->params = [];
        } catch (PDOException $e) {
            throw new AppException('Database connection failed: ' . $e->getMessage());
        }
    }

    private function setupMysql()
    {
        $this->pdo = new PDO(
            'mysql:host=' . app()->getConfig('db')['host'] . ';dbname=' . app()->getConfig('db')['dbname'],
            app()->getConfig('db')['username'],
            app()->getConfig('db')['password']
        );

        $tz = timezoneToOffset(
            app()->getConfig('app')['timezone']
        );
        $this->pdo->exec("SET time_zone = '$tz'");
    }

    /**
     * Get the singleton instance.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    /**
     * Initialize query with a base select statement.
     *
     * @param string $table
     * @param array $columns
     * @return self
     */
    public function from(string $table, array $columns = ['*']): self
    {
        $this->table = $table;

        $columnsList = implode(',', $columns);
        $this->query = "SELECT $columnsList FROM $table";
        return $this;
    }

    /**
     * Initialize query with a base select statement.
     *
     * @param string $table
     * @param array $columns
     * @return self
     */
    public function orderBy(string $columnName = 'id', string $type = 'ASC'): self
    {
        $this->query .= " ORDER BY $columnName $type";
        return $this;
    }

    /**
     * Get the latest data @alias of orderBy:desc
     */
    public function latest($columnName = 'id')
    {
        return $this->orderBy($columnName, 'desc');
    }

    /**
     * Get the oldest data @alias of orderBy:asc
     */
    public function oldest($columnName = 'id')
    {
        return $this->orderBy($columnName, 'asc');
    }

    /**
     * Add LIMIT clause.
     *
     * @param int $limit
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->query .= " LIMIT $limit"; // Directly interpolate the limit value
        return $this;
    }

    /**
     * Add OFFSET clause.
     *
     * @param int $offset
     * @return self
     */
    public function offset(int $offset): self
    {
        $this->query .= " OFFSET $offset"; // Directly interpolate the offset value
        return $this;
    }

    /**
     * Add SKIP clause (same as OFFSET in SQL).
     *
     * @param int $skip
     * @return self
     */
    public function skip(int $skip): self
    {
        return $this->offset($skip);
    }

    public function where(string $column, string $operator = '=', $value = null): self
    {
        if (strtolower($operator) === 'like' && $value !== null && !str_contains($value, '%')) {
            $value = "%{$value}%";
        }

        $prefix = str_contains($this->query, 'WHERE') ? ' AND' : ' WHERE';

        $this->query .= "$prefix $column $operator :$column";
        $this->params = array_merge($this->params, [$column => $value]);

        return $this;
    }

    public function whereLike(string $column, $value)
    {
        return $this->where($column, 'like', $value);
    }

    /**
     * Execute the query and return the results.
     *
     * @return array
     */
    public function get(): array
    {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->params);
        return $stmt->fetchAll();
    }

    public function paginate($perPage = null)
    {
        if (!$this->query) {
            throw new Exception("No query has been initialized. Use 'from()' before calling paginate().");
        }

        if ($perPage) {
            $this->perPage = $perPage;
        }

        $countQuery = preg_replace('/SELECT .* FROM/', 'SELECT COUNT(*) FROM', $this->query, 1);
        $stmtTotal  = $this->pdo->prepare($countQuery);
        $stmtTotal->execute($this->params);
        $total = $stmtTotal->fetchColumn();

        // Append pagination logic
        $this->query .= ' LIMIT ' . $this->perPage . ' OFFSET ' . max(($this->page - 1) * $this->perPage, 0);

        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->params);
        $data = $stmt->fetchAll();

        $paginationLinks = $this->generatePaginationLinks($total, $this->perPage);

        return (object)[
            'total'    => $total,
            'pages'    => (int)ceil($total / $this->perPage),
            'per_page' => $this->perPage,
            'data'     => $data,
            'links'    => $paginationLinks
        ];
    }


    // public function paginate()
    // {
    //     $query = 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . $this->table;

    //     $query .= ' LIMIT ' . $this->perPage . ' OFFSET ' . max($this->page - 1, 0);

    //     $stmt = $this->pdo->prepare($query);
    //     $stmt->execute($this->params);

    //     $data = $stmt->fetchAll();

    //     $stmtTotal = $this->pdo->query('SELECT FOUND_ROWS()');
    //     $total = $stmtTotal->fetchColumn();

    //     $paginationLinks = $this->generatePaginationLinks($total);


    //     return (object)[
    //         'total'    => $total,
    //         'pages'    => (int)ceil($total / $this->perPage),
    //         'per_page' => $this->perPage,
    //         'data'     => $data,
    //         'links'    => $paginationLinks
    //     ];
    // }

    // bootstrap, tailwind, bulma,
    private function generatePaginationLinks($total, $perPage)
    {
        $totalPages      = (int)ceil($total / $this->perPage);
        $currentPage     = $this->page;
        $paginationLinks = '<div class="pagination">';

        if ($currentPage > 1) {
            $paginationLinks .= '<a href="?page=' . ($currentPage - 1) . '" class="page-link">« Previous</a>';
        } else {
            $paginationLinks .= '<span class="page-link disabled">« Previous</span>';
        }

        $range     = 2;
        $startPage = max(1, $currentPage - $range);
        $endPage   = min($totalPages, $currentPage + $range);

        if ($startPage > 1) {
            $paginationLinks .= '<a href="?page=1" class="page-link">1</a>';
            $paginationLinks .= '<span class="page-link dots">...</span>';
        }

        for ($page = $startPage; $page <= $endPage; $page++) {
            if ($page == $currentPage) {
                $paginationLinks .= '<span class="page-link active">' . $page . '</span>';
            } else {
                $paginationLinks .= '<a href="?page=' . $page . '" class="page-link">' . $page . '</a>';
            }
        }

        if ($endPage < $totalPages) {
            $paginationLinks .= '<span class="page-link dots">...</span>';
            $paginationLinks .= '<a href="?page=' . $totalPages . '" class="page-link">' . $totalPages . '</a>';
        }

        if ($currentPage < $totalPages) {
            $paginationLinks .= '<a href="?page=' . ($currentPage + 1) . '" class="page-link">Next »</a>';
        } else {
            $paginationLinks .= '<span class="page-link disabled">Next »</span>';
        }

        $paginationLinks .= '</div>';

        return "<div class='d-flex justify-content-between align-items-center w-100'><div>Showing " . $perPage . " items of " . $total . "</div>" . $paginationLinks . "</div>";
    }

    public function first()
    {
        return $this->get()[0] ?? null;
    }

    /**
     * Count the number of rows matching the query.
     *
     * @return int
     */
    public function count(): int
    {
        $stmt = $this->pdo->prepare($this->query);
        $stmt->execute($this->params);
        return $stmt->rowCount();
    }

    /**
     * Insert a record into a table.
     *
     * @param string $table
     * @param array $data
     * @return bool
     */
    public function insert(string $table, array $data): bool
    {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(',:', array_keys($data));
        $this->query = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        // Bind values dynamically
        $this->params = array_combine(
            array_map(fn($key) => ':' . $key, array_keys($data)),
            array_values($data)
        );

        $stmt = $this->pdo->prepare($this->query);
        return $stmt->execute($this->params);
    }

    /**
     * Update records in a table.
     *
     * @param string $table
     * @param array $data
     * @param array $conditions
     * @return bool
     */
    public function update(string $table, array $data, array $conditions): bool
    {
        $setClause = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($data)));

        $whereClause = implode(' AND ', array_map(fn($col) => "$col = :where_$col", array_keys($conditions)));

        $this->query = "UPDATE $table SET $setClause WHERE $whereClause";

        $this->params = array_merge(
            array_combine(array_map(fn($col) => ":$col", array_keys($data)), array_values($data)),
            array_combine(array_map(fn($col) => ":where_$col", array_keys($conditions)), array_values($conditions))
        );

        try {
            $stmt = $this->pdo->prepare($this->query);
            return $stmt->execute($this->params);
        } catch (PDOException $e) {
            $this->handleQueryError($e);
        }
    }


    private function handleQueryError(PDOException $e)
    {
        throw new AppException(
            'Database Query Error: ' . $e->getMessage(),
            500,
            $e,
            500,
            [
                'query' => $this->query,
                'params' => $this->params
            ]
        );
    }

    /**
     * Delete records from a table.
     *
     * @param string $table
     * @param string $condition
     * @param array $params
     * @return bool
     */
    public function delete(string $table, array $conditions): bool
    {
        if (empty($conditions)) {
            throw new Exception("Delete conditions cannot be empty.");
        }

        // Build WHERE clause dynamically
        $whereClause = implode(' AND ', array_map(fn($col) => "$col = :$col", array_keys($conditions)));

        $this->query = "DELETE FROM $table WHERE $whereClause";
        $this->params = $conditions;

        $stmt = $this->pdo->prepare($this->query);
        return $stmt->execute($this->params);
    }
}
