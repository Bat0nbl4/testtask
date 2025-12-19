<?php

// Load configuration files
require_once "config/config.php";
require_once "config/server_config.php";
require_once "config/db_conn.php";

// Set custom session storage path if defined, otherwise use default
ini_set('session.save_path', __DIR__ . SESSION_STORAGE ?? "/storage/sessions");

// Load core application files
require_once "autoload.php";      // Class autoloader
require_once "routes/routes.php"; // Route definitions

// Import necessary namespaces
use vendor\session\Session;
use vendor\routing\Router;

// Clear any temporary flash messages from previous request
Session::clearFlash();

// Dispatch the current HTTP request to the appropriate controller/action
Router::resolve();