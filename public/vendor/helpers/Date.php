<?php

namespace vendor\helpers;

/**
 * Date manipulation and formatting helper class
 * Provides utilities for date parsing, formatting, and generation
 */
abstract class Date
{
    /**
     * Internal method to parse a date string into DateTime object
     * Returns null if date string is invalid
     *
     * @param string $dateString Date string in any format parsable by DateTime
     * @return \DateTime|null Valid DateTime object or null on parse failure
     */
    private static function update_date(string $dateString) : ?\DateTime {
        try {
            $date = new \DateTime($dateString);
        } catch (\Exception $e) {
            // Return null instead of throwing exception for graceful handling
            return null;
        }
        return $date;
    }

    /**
     * Gets the day name in Russian for a given date
     * Returns full Russian name of the weekday (Понедельник, Вторник, etc.)
     *
     * @param string $dateString Date string to parse
     * @return string|null Russian day name or null if date is invalid
     */
    public static function day_name(string $dateString) : ?string {
        $date = self::update_date($dateString);
        if ($date === null) {
            return null;
        }

        // 'N' format returns ISO-8601 day number (1=Monday to 7=Sunday)
        $dayNumber = $date->format('N');

        $days = [
            1 => 'Понедельник',
            2 => 'Вторник',
            3 => 'Среда',
            4 => 'Четверг',
            5 => 'Пятница',
            6 => 'Суббота',
            7 => 'Воскресенье'
        ];

        return $days[$dayNumber] ?? null;
    }

    /**
     * Formats a date string to time-only format (HH:MM)
     * Example: "2023-12-25 14:30:00" → "14:30"
     *
     * @param string $dateString Date string to format
     * @return string|null Time in "H:i" format or null if date is invalid
     */
    public static function normal_time(string $dateString) : ?string {
        $date = self::update_date($dateString);
        return $date ? $date->format("H:i") : null;
    }

    /**
     * Formats a date string to standard date format (DD.MM.YYYY)
     * Example: "2023-12-25 14:30:00" → "25.12.2023"
     *
     * @param string $dateString Date string to format
     * @return string|null Date in "d.m.Y" format or null if date is invalid
     */
    public static function normal_date(string $dateString) : ?string {
        $date = self::update_date($dateString);
        return $date ? $date->format("d.m.Y") : null;
    }

    /**
     * Generates a random date between two given dates
     * Inclusive range - both start and end dates can be selected
     *
     * @param string $start_date Start date boundary (inclusive)
     * @param string $end_date End date boundary (inclusive)
     * @return string Random date in 'Y-m-d' format
     * @throws \Exception If date strings cannot be parsed
     */
    public static function randomDate(string $start_date, string $end_date) : string {
        $min = strtotime($start_date);
        $max = strtotime($end_date);

        // Validate that dates were parsed correctly
        if ($min === false || $max === false) {
            throw new \Exception("Invalid date format provided");
        }

        // Ensure min is less than max
        if ($min > $max) {
            list($min, $max) = [$max, $min];
        }

        // Generate random timestamp between boundaries
        $val = rand($min, $max);
        return date('Y-m-d', $val);
    }
}