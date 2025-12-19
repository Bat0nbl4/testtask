<?php

/**
 * Recursively loads all PHP class files from a directory and its subdirectories
 *
 * @param string $directory The directory path to scan for PHP files
 */
function autoloadClasses($directory)
{
    // Get all items in the current directory
    $items = scandir($directory);

    // First pass: Load all PHP files in the current directory
    foreach ($items as $item) {
        // Skip current (.) and parent (..) directory references
        if ($item === '.' || $item === '..') {
            continue;
        }

        $path = $directory . DIRECTORY_SEPARATOR . $item;

        // If it's a PHP file, include it immediately
        if (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            require_once $path;
        }
    }

    // Second pass: Recursively process subdirectories
    foreach ($items as $item) {
        // Skip current (.) and parent (..) directory references
        if ($item === '.' || $item === '..') {
            continue;
        }

        $path = $directory . DIRECTORY_SEPARATOR . $item;

        // If it's a directory, recursively process it
        if (is_dir($path)) {
            autoloadClasses($path);
        }
    }
}

// Process each directory defined in LOAD_CLASSES_DIRS configuration
foreach (LOAD_CLASSES_DIRS as $dir) {
    autoloadClasses(__DIR__ . $dir);
}