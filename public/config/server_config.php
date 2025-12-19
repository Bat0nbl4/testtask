<?php

/**
 * Server and autoload configuration
 * Sets up server paths and defines directories for class autoloading
 */

// Set the base directory for the application (one level above config directory)
$_SERVER['BASE_DIR'] = dirname(__DIR__);

/**
 * Directories to scan for PHP class files during autoloading
 * Classes in these directories will be automatically loaded when needed
 * Order: Core components first, then application-specific classes
 */
const LOAD_CLASSES_DIRS = [
    // Core framework components
    "/vendor/data_base",      // Database and QueryBuilder
    "/vendor/helpers",        // Helper classes (Date, Str, etc.)
    "/vendor/routing",        // Router
    "/vendor/rendering",      // View rendering
    "/vendor/security",       // Security utilities
    "/vendor/session",        // Session management
    "/vendor/middleware",     // Middleware base classes

    // Application controllers
    "/controllers",           // Base and namespaced controllers

    // Application middleware
    "/middleware"            // Route-specific middleware
];

// Optional: Additional server settings
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);