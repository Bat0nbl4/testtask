<?php

/**
 * Main application configuration file
 * Contains core settings for the application
 */

// Protocol used for generating URLs (http/https)
const BASE_METHOD = "https";

// Application domain (used for URL generation)
const APP_DOMEN = "testtask";

// Directory for storing session files
const SESSION_STORAGE = "/storage/sessions";

// Base URL path for the application (if deployed in subdirectory)
const BASE_PATH = "/public";

// Whether to use BASE_PATH in generated URLs
const USE_BASE_PATH = false;

// Base directory for application resources (CSS, JS, images, etc.)
const RESOURCE_PATH = "/resources";

// Directory for reusable view components
const COMPONENTS_DIR = RESOURCE_PATH."/render/components";

// Directory for layout templates
const TEMPLATES_DIR = RESOURCE_PATH."/render/templates";

// Default template file (without .php extension)
const BASE_TEMPLATE = "app";

// Directory for view files
const VIEW_DIR = RESOURCE_PATH."/render/views";

// Route names for error pages (used in Router redirects)
const catchRoutes = [
    "404" => "404",    // Route name for 404 Not Found page
    "403" => "403"     // Route name for 403 Forbidden page
];