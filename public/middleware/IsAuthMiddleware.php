<?php

namespace middleware;

use vendor\middleware\Middleware;
use vendor\routing\Router;
use vendor\session\Session;

/**
 * Middleware that protects routes requiring authentication
 * Use on routes that should only be accessible to logged-in users
 * Redirects unauthenticated users to login page
 */
abstract class IsAuthMiddleware extends Middleware
{
    /**
     * Checks if user is not authenticated and redirects to login page if true
     * Verifies absence of 'user' key in session to detect unauthenticated state
     * If no user session exists, redirects to 'login' route
     */
    public static function handle(): void
    {
        // Check if user session data is missing (user is not authenticated)
        if (!Session::has("user")) {
            // Redirect unauthenticated users to login page
            Router::redirect(Router::route("login"));
        }

        // If user session exists, continue with request (allow access to protected page)
    }
}