<?php

namespace vendor\helpers;

/**
 * Array recursion helper class
 * Provides methods for recursive array operations with depth control
 */
abstract class Array_r
{
    /**
     * Recursively searches for a value in a nested array
     * Supports both loose (==) and strict (===) comparison
     * Allows limiting search depth to prevent infinite recursion
     *
     * @param mixed $needle Value to search for
     * @param array $haystack Array to search within
     * @param bool $strict Use strict comparison (===) if true, loose (==) if false
     * @param int $depth Maximum recursion depth (0 = no recursion, -1 = unlimited)
     * @return bool True if value found, false otherwise
     */
    public static function has($needle, $haystack, $strict = false, $depth = 1): bool {
        // Allow unlimited recursion if depth is -1
        if ($depth === -1 || $depth > 0) {
            foreach ($haystack as $item) {
                // Check current item
                if ($strict ? $item === $needle : $item == $needle) {
                    return true;
                }

                // Recursively search nested arrays if depth allows
                if (is_array($item) && self::has($needle, $item, $strict, $depth - 1)) {
                    return true;
                }
            }
        }
        return false;
    }
}