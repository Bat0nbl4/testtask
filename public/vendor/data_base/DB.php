<?php

namespace vendor\data_base;

use PDO;
use PDOException;

/**
 * Database connection manager and query entry point
 * Implements singleton pattern for PDO connection and provides QueryBuilder factory
 */
class DB
{
    // Singleton PDO instance
    protected static ?PDO $pdo = null;

    /**
     * Establishes a database connection if not already connected
     * Configures PDO with error reporting and default fetch mode
     */
    public static function connect(): void
    {
        if (self::$pdo === null) {
            // Build Data Source Name (DSN) from configuration constants
            $dsn = DB_DRIVER.":host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8";

            try {
                // Create new PDO instance
                self::$pdo = new PDO($dsn, DB_USER, DB_PASSWORD);

                // Configure PDO to throw exceptions on errors
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Set default fetch mode to associative arrays
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Terminate script if connection fails
                die("Database connection failed: " . $e->getMessage());
            }
        }
    }

    /**
     * Returns the PDO instance, establishing connection if needed
     *
     * @return PDO Active database connection
     */
    public static function getPdo(): PDO
    {
        self::connect();
        return self::$pdo;
    }

    /**
     * Creates and returns a new QueryBuilder instance
     * This is the primary entry point for building database queries
     *
     * @return QueryBuilder New query builder instance
     */
    public static function query(): QueryBuilder
    {
        return new QueryBuilder();
    }

    /**
     * Executes a raw SQL query directly
     * Use with caution - no parameter binding or escaping
     *
     * @param string $query Raw SQL query to execute
     * @return array Query results as associative arrays
     */
    public static function manualQuery(string $query): array
    {
        try {
            $stmt = self::getPdo()->query($query);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }
}