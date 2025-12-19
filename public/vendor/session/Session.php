<?php

namespace vendor\session;

/**
 * Session management class with support for dot notation, flash messages, and nested data
 * Provides a clean interface for working with PHP sessions with additional features
 */
class Session
{
    /**
     * Starts a session if one is not already active
     * Ensures session is ready for read/write operations
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Stores a value in the session using dot notation for nested arrays
     *
     * @param string $key Dot-notation key (e.g., 'user.profile.name')
     * @param mixed $value Value to store
     */
    public static function set(string $key, mixed $value): void
    {
        self::start();
        self::setNestedValue($_SESSION, $key, $value);
    }

    /**
     * Retrieves a value from the session using dot notation
     *
     * @param string $key Dot-notation key (e.g., 'user.profile.name')
     * @param mixed $default Default value if key doesn't exist
     * @return mixed Retrieved value or default
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return self::getNestedValue($_SESSION, $key, $default);
    }

    /**
     * Returns all session data
     *
     * @param mixed $default Default value if session is empty
     * @return mixed Entire session array or default
     */
    public static function all(mixed $default = null): mixed
    {
        self::start();
        return $_SESSION ?? $default;
    }

    /**
     * Removes a value from the session using dot notation
     *
     * @param string $key Dot-notation key to remove
     */
    public static function remove(string $key): void
    {
        self::start();
        self::removeNestedKey($_SESSION, $key);
    }

    /**
     * Clears all session data (does not destroy the session itself)
     */
    public static function clear(): void
    {
        self::start();
        session_unset();
    }

    /**
     * Checks if a key exists in the session using dot notation
     *
     * @param string $key Dot-notation key to check
     * @return bool True if key exists
     */
    public static function has(string $key): bool
    {
        self::start();
        return self::hasNestedKey($_SESSION, $key);
    }

    /**
     * Stores a flash message that will be available only for the next request
     * Flash data is automatically cleared after being accessed once
     *
     * @param string $key Dot-notation key for flash data
     * @param mixed $value Value to store as flash data
     */
    public static function flash(string $key, mixed $value): void
    {
        self::start();

        // Initialize flash array if it doesn't exist
        if (!isset($_SESSION['_flash'])) {
            $_SESSION['_flash'] = [];
        }

        // Store value with dot notation support
        self::setNestedValue($_SESSION['_flash'], $key, $value);
    }

    /**
     * Retrieves a flash message and marks it for deletion
     * Flash data is only available once per storage
     *
     * @param string $key Dot-notation key for flash data
     * @param mixed $default Default value if flash key doesn't exist
     * @return mixed Flash value or default
     */
    public static function getFlash(string $key, mixed $default = null): mixed
    {
        self::start();
        $value = self::getNestedValue($_SESSION['_flash'] ?? [], $key, $default);

        // Mark this specific key for deletion (supports dot notation)
        self::markForDeletion($key);

        return $value;
    }

    /**
     * Checks if a flash message exists using dot notation
     *
     * @param string $key Dot-notation key to check
     * @return bool True if flash key exists
     */
    public static function hasFlash(string $key): bool
    {
        self::start();
        return self::hasNestedKey($_SESSION['_flash'] ?? [], $key);
    }

    /**
     * Manually removes a flash message before automatic cleanup
     *
     * @param string $key Dot-notation key to remove from flash data
     */
    public static function removeFlash(string $key): void
    {
        self::start();
        self::removeNestedKey($_SESSION["_flash"], $key);
    }

    /**
     * Marks a flash key for deletion during the next clearFlash() call
     *
     * @param string $key Dot-notation key to mark for deletion
     */
    private static function markForDeletion(string $key): void
    {
        if (!isset($_SESSION['_flash_marked'])) {
            $_SESSION['_flash_marked'] = [];
        }
        $_SESSION['_flash_marked'][] = $key;
    }

    /**
     * Clears all flash data that has been marked for deletion
     * Should be called after rendering a page (e.g., at the end of middleware)
     */
    public static function clearFlash(): void
    {
        self::start();

        // Exit if no keys are marked for deletion
        if (!isset($_SESSION['_flash_marked']) || empty($_SESSION['_flash_marked'])) {
            unset($_SESSION['_flash_marked']);
            return;
        }

        // Exit if no flash data exists
        if (!isset($_SESSION['_flash']) || !is_array($_SESSION['_flash'])) {
            unset($_SESSION['_flash_marked']);
            return;
        }

        // Remove all marked flash keys
        foreach ($_SESSION['_flash_marked'] as $key) {
            self::removeNestedKey($_SESSION['_flash'], $key);
        }

        unset($_SESSION['_flash_marked']);

        // Clean up empty flash array
        if (empty($_SESSION['_flash'])) {
            unset($_SESSION['_flash']);
        }
    }

    /**
     * Recursively checks if a dot-notation key exists in an array
     *
     * @param array $array Array to search in
     * @param string $key Dot-notation key (e.g., 'user.profile.name')
     * @return bool True if all parts of the key exist
     */
    private static function hasNestedKey(array $array, string $key): bool
    {
        $keys = explode('.', $key);
        $current = $array;

        foreach ($keys as $part) {
            if (!is_array($current) || !array_key_exists($part, $current)) {
                return false;
            }
            $current = $current[$part];
        }

        return true;
    }

    /**
     * Recursively retrieves a value using dot notation from an array
     *
     * @param array $array Array to search in
     * @param string $key Dot-notation key (e.g., 'user.profile.name')
     * @param mixed $default Default value if key not found
     * @return mixed Retrieved value or default
     */
    private static function getNestedValue(array $array, string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $current = $array;

        foreach ($keys as $part) {
            if (!is_array($current) || !array_key_exists($part, $current)) {
                return $default;
            }
            $current = $current[$part];
        }

        return $current;
    }

    /**
     * Recursively sets a value using dot notation in an array by reference
     * Creates nested arrays as needed to reach the target key
     *
     * @param array &$array Reference to the array to modify
     * @param string $key Dot-notation key (e.g., 'user.profile.name')
     * @param mixed $value Value to set
     */
    private static function setNestedValue(array &$array, string $key, mixed $value): void
    {
        $keys = explode('.', $key);
        $current = &$array;

        foreach ($keys as $i => $part) {
            if ($i === count($keys) - 1) {
                // Last part: set the value
                $current[$part] = $value;
            } else {
                // Intermediate part: ensure array exists
                if (!isset($current[$part]) || !is_array($current[$part])) {
                    $current[$part] = [];
                }
                $current = &$current[$part];
            }
        }
    }

    /**
     * Recursively removes a key using dot notation from an array by reference
     *
     * @param array &$array Reference to the array to modify
     * @param string $key Dot-notation key to remove
     */
    private static function removeNestedKey(array &$array, string $key): void
    {
        // Exit if array is invalid or empty
        if (!is_array($array) || empty($array)) {
            return;
        }

        $keys = explode('.', $key);
        $current = &$array;

        foreach ($keys as $i => $part) {
            if (!is_array($current) || !array_key_exists($part, $current)) {
                return;
            }

            if ($i === count($keys) - 1) {
                // Last part: remove the key
                unset($current[$part]);
                return;
            }

            $current = &$current[$part];
        }
    }
}