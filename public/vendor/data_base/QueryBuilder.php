<?php

namespace vendor\data_base;

use PDO;
use PDOException;

/**
 * Fluent query builder for constructing SQL queries with parameter binding
 * Supports SELECT, INSERT, UPDATE, DELETE operations with WHERE, JOIN, ORDER BY, etc.
 */
class QueryBuilder
{
    // Query configuration properties
    private string $table = '';
    private string $type = 'select';
    private array $where = [];
    private array $select = ['*'];
    private ?int $limit = null;
    private ?int $offset = null;
    private array $orderBy = [];
    private array $params = [];
    private array $data = [];
    private array $groupBy = [];
    private array $join = [];

    // SQL debugging properties
    private ?string $lastSql = null;
    private bool $sqlRequested = false;

    /**
     * Sets the table name for the query
     *
     * @param string $table Table name
     * @return self Chainable instance
     */
    public function from(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Specifies columns to select (defaults to *)
     *
     * @param array $columns Array of column names
     * @return self Chainable instance
     */
    public function select(array $columns): self
    {
        $this->select = $columns;
        return $this;
    }

    /**
     * Adds a WHERE condition with AND logic
     * Supports IN operator with array values
     *
     * @param string $column Column name
     * @param string $operator Comparison operator (=, <>, >, <, IN, etc.)
     * @param mixed $value Comparison value or array for IN operator
     * @return self Chainable instance
     */
    public function where(string $column, string $operator, $value): self
    {
        $paramName = 'param_' . count($this->params);

        if (strtoupper($operator) === 'IN' && is_array($value)) {
            // Handle IN operator with array values
            $placeholders = [];
            foreach ($value as $i => $val) {
                $arrayParamName = $paramName . '_' . $i;
                $placeholders[] = ":$arrayParamName";
                $this->params[$arrayParamName] = $val;
            }
            $this->where[] = [
                "type" => "AND",
                "condition" => "$column IN (" . implode(', ', $placeholders) . ")"
            ];
        } else {
            // Handle standard operators
            $this->where[] = [
                "type" => "AND",
                "condition" => "$column $operator :$paramName"
            ];
            $this->params[$paramName] = $value;
        }
        return $this;
    }

    /**
     * Adds a WHERE condition with OR logic
     *
     * @param string $column Column name
     * @param string $operator Comparison operator
     * @param mixed $value Comparison value or array for IN operator
     * @return self Chainable instance
     */
    public function orWhere(string $column, string $operator, $value): self
    {
        $paramName = 'param_' . count($this->params);

        if (strtoupper($operator) === 'IN' && is_array($value)) {
            // Handle IN operator with array values
            $placeholders = [];
            foreach ($value as $i => $val) {
                $arrayParamName = $paramName . '_' . $i;
                $placeholders[] = ":$arrayParamName";
                $this->params[$arrayParamName] = $val;
            }
            $this->where[] = [
                "type" => "OR",
                "condition" => "$column IN (" . implode(', ', $placeholders) . ")"
            ];
        } else {
            // Handle standard operators
            $this->where[] = [
                "type" => "OR",
                "condition" => "$column $operator :$paramName"
            ];
            $this->params[$paramName] = $value;
        }
        return $this;
    }

    /**
     * Adds a JOIN clause to the query
     *
     * @param string $table Table to join
     * @param string $first First column for join condition
     * @param string $operator Join operator (=, <>, etc.)
     * @param string $second Second column for join condition
     * @param string $type Join type (INNER, LEFT, RIGHT, etc.)
     * @return self Chainable instance
     */
    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): self
    {
        $this->join[] = "$type JOIN $table ON $first $operator $second";
        return $this;
    }

    /**
     * Adds a LEFT JOIN clause (convenience method)
     *
     * @param string $table Table to join
     * @param string $first First column for join condition
     * @param string $operator Join operator
     * @param string $second Second column for join condition
     * @return self Chainable instance
     */
    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    /**
     * Adds ORDER BY clause
     *
     * @param string $column Column to order by
     * @param string $direction Sort direction (ASC or DESC)
     * @return self Chainable instance
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy[] = "$column $direction";
        return $this;
    }

    /**
     * Sets LIMIT clause for query results
     *
     * @param int $limit Maximum number of rows to return
     * @return self Chainable instance
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Sets OFFSET clause for query results
     *
     * @param int $offset Number of rows to skip
     * @return self Chainable instance
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Sets data for INSERT or UPDATE operations
     *
     * @param array $data Associative array of column => value pairs
     * @return self Chainable instance
     */
    public function set(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Executes a SELECT query and returns all results
     *
     * @return array Query results as associative arrays
     */
    public function get(): array
    {
        $this->type = 'select';
        $sql = $this->buildSelectQuery();

        // Return early if only SQL is requested (debug mode)
        if ($this->sqlRequested) {
            $this->lastSql = $this->interpolateQuery($sql);
            return [];
        }

        // Prepare and execute query with parameter binding
        $stmt = DB::getPdo()->prepare($sql);

        foreach ($this->params as $param => $value) {
            $stmt->bindValue(":$param", $value);
        }

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die("<b>Fatal query error:</b> $e");
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Executes a SELECT query and returns only the first result
     *
     * @return array|null First row as associative array or null if no results
     */
    public function first(): ?array
    {
        $this->limit(1);
        $results = $this->get();

        if ($this->sqlRequested) {
            return null;
        }

        return $results[0] ?? null;
    }

    /**
     * Adds GROUP BY clause to query
     *
     * @param array $columns Columns to group by
     * @return self Chainable instance
     */
    public function groupBy(array $columns): self
    {
        $this->groupBy = array_merge($this->groupBy, $columns);
        return $this;
    }

    /**
     * Executes an INSERT query
     *
     * @param array $data Optional data to insert (overrides set() data)
     * @return int|string Last insert ID or SQL string in debug mode
     */
    public function insert(array $data = []): int|string
    {
        $this->type = 'insert';
        if (!empty($data)) {
            $this->data = $data;
        }

        $sql = $this->buildInsertQuery();

        if ($this->sqlRequested) {
            $this->lastSql = $this->interpolateQuery($sql);
            return $this->lastSql;
        }

        $stmt = DB::getPdo()->prepare($sql);

        // Bind data values for insertion
        foreach ($this->data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        try {
            if ($stmt->execute()) {
                $lastInsertId = DB::getPdo()->lastInsertId();
                return (int)$lastInsertId;
            }
            return false;
        } catch (PDOException $e) {
            die("<b>Fatal query error:</b> $e");
        }
    }

    /**
     * Executes an UPDATE query
     *
     * @param array $data Optional data to update (overrides set() data)
     * @return int|string Number of affected rows or SQL string in debug mode
     */
    public function update(array $data = []): int|string
    {
        $this->type = 'update';
        if (!empty($data)) {
            $this->data = $data;
        }

        $sql = $this->buildUpdateQuery();

        if ($this->sqlRequested) {
            $this->lastSql = $this->interpolateQuery($sql);
            return $this->lastSql;
        }

        $stmt = DB::getPdo()->prepare($sql);

        // Bind SET clause values with 'set_' prefix
        foreach ($this->data as $key => $value) {
            $stmt->bindValue(":set_$key", $value);
        }

        // Bind WHERE clause values
        foreach ($this->params as $param => $value) {
            $stmt->bindValue(":$param", $value);
        }

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die("<b>Fatal query error:</b> $e");
        }

        return $stmt->rowCount();
    }

    /**
     * Executes a DELETE query
     *
     * @return int|string Number of affected rows or SQL string in debug mode
     */
    public function delete(): int|string
    {
        $this->type = 'delete';
        $sql = $this->buildDeleteQuery();

        if ($this->sqlRequested) {
            $this->lastSql = $this->interpolateQuery($sql);
            return $this->lastSql;
        }

        $stmt = DB::getPdo()->prepare($sql);

        foreach ($this->params as $param => $value) {
            $stmt->bindValue(":$param", $value);
        }

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            die("<b>Fatal query error:</b> $e");
        }

        return $stmt->rowCount();
    }

    /**
     * Enables SQL debug mode - subsequent query will return SQL string instead of executing
     *
     * @return self Chainable instance
     */
    public function sql(): self
    {
        $this->sqlRequested = true;
        return $this;
    }

    /**
     * Returns the last generated SQL string (debug mode only)
     *
     * @return string|null Generated SQL or null if not in debug mode
     */
    public function getLastSql(): ?string
    {
        return $this->lastSql;
    }

    /**
     * Builds SELECT query SQL from current configuration
     *
     * @return string Complete SELECT SQL statement
     */
    private function buildSelectQuery(): string
    {
        $sql = "SELECT " . implode(', ', $this->select) . " FROM " . $this->table;

        if (!empty($this->join)) {
            $sql .= " " . implode(' ', $this->join);
        }

        if (!empty($this->where)) {
            $sql .= " WHERE " . $this->buildWhereClause();
        }

        if (!empty($this->groupBy)) {
            $sql .= " GROUP BY " . implode(', ', $this->groupBy);
        }

        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(', ', $this->orderBy);
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT " . $this->limit;
        }

        if ($this->offset !== null) {
            $sql .= " OFFSET " . $this->offset;
        }

        return $sql;
    }

    /**
     * Builds INSERT query SQL from current data
     *
     * @return string Complete INSERT SQL statement
     */
    private function buildInsertQuery(): string
    {
        $columns = implode(', ', array_keys($this->data));
        $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($this->data)));

        return "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
    }

    /**
     * Builds UPDATE query SQL from current data and conditions
     *
     * @return string Complete UPDATE SQL statement
     */
    private function buildUpdateQuery(): string
    {
        $setParts = [];
        foreach ($this->data as $column => $value) {
            $setParts[] = "$column = :set_$column";
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts);

        if (!empty($this->where)) {
            $sql .= " WHERE " . $this->buildWhereClause();
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT " . $this->limit;
        }

        return $sql;
    }

    /**
     * Builds DELETE query SQL from current conditions
     *
     * @return string Complete DELETE SQL statement
     */
    private function buildDeleteQuery(): string
    {
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->where)) {
            $sql .= " WHERE " . $this->buildWhereClause();
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT " . $this->limit;
        }

        return $sql;
    }

    /**
     * Builds WHERE clause from conditions array
     *
     * @return string WHERE clause SQL
     */
    private function buildWhereClause(): string
    {
        $whereParts = [];
        $firstCondition = true;

        foreach ($this->where as $condition) {
            if ($firstCondition) {
                $whereParts[] = $condition['condition'];
                $firstCondition = false;
            } else {
                $whereParts[] = $condition['type'] . ' ' . $condition['condition'];
            }
        }

        return implode(' ', $whereParts);
    }

    /**
     * Replaces parameter placeholders in SQL with actual values for debugging
     *
     * @param string $query SQL query with placeholders
     * @return string SQL query with values interpolated
     */
    private function interpolateQuery(string $query): string
    {
        $params = $this->getAllParams();

        if (empty($params)) {
            return $query;
        }

        $keys = [];
        $values = [];

        foreach ($params as $key => $value) {
            $searchKey = ':' . $key;

            if (strpos($query, $searchKey) !== false) {
                $keys[] = $searchKey;
                $values[] = $this->quoteValue($value);
            }
        }

        return str_replace($keys, $values, $query);
    }

    /**
     * Combines all parameters (WHERE and SET data) for SQL interpolation
     *
     * @return array All query parameters
     */
    private function getAllParams(): array
    {
        $params = $this->params;

        if (in_array($this->type, ['update', 'insert']) && !empty($this->data)) {
            foreach ($this->data as $key => $value) {
                if ($this->type === 'update') {
                    $paramKey = 'set_' . $key;
                } else {
                    $paramKey = $key;
                }
                $params[$paramKey] = $value;
            }
        }

        return $params;
    }

    /**
     * Properly quotes a value for SQL interpolation based on type
     *
     * @param mixed $value Value to quote
     * @return string SQL-safe quoted value
     */
    private function quoteValue($value): string
    {
        if (is_string($value)) {
            return "'" . addslashes($value) . "'";
        } elseif (is_bool($value)) {
            return $value ? '1' : '0';
        } elseif (is_null($value)) {
            return 'NULL';
        } elseif (is_array($value)) {
            return "(" . implode(', ', array_map([$this, 'quoteValue'], $value)) . ")";
        }
        return (string)$value;
    }
}