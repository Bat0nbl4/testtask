<?php

namespace vendor\helpers;

/**
 * String generation and manipulation helper class
 * Provides utilities for generating random and unique strings
 */
abstract class Str
{
    /**
     * Generates a random string of specified length
     * Uses characters: 0-9, a-z, A-Z (62 possible characters)
     * Warning: Not cryptographically secure - use for non-security purposes only
     *
     * @param int $length Desired string length (default: 1)
     * @return string Random string of specified length
     */
    public static function random(int $length = 1) {
        // Character pool for random selection
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        // Shuffle characters and take first $length characters
        return substr(str_shuffle($characters), 0, $length);
    }

    /**
     * Generates a unique identifier with optional additional randomness
     * Combines PHP's uniqid() with random characters for enhanced uniqueness
     *
     * @param int $additional_length Number of extra random characters to append
     * @param bool $more_entropy If true, adds additional entropy to uniqid()
     * @return string Unique identifier (13+ characters by default)
     */
    public static function unique_random(int $additional_length = 0, bool $more_entropy = true) {
        // Generate base unique ID (microtime-based)
        $uniqueId = uniqid('', $more_entropy);

        // Append additional random characters if requested
        if ($additional_length > 0) {
            $uniqueId .= self::random($additional_length);
        }

        return $uniqueId;
    }
}