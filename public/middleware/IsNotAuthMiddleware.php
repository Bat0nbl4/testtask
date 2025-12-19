<?php

namespace middleware;

use vendor\middleware\Middleware;
use vendor\routing\Router;
use vendor\session\Session;

/**
 * Middleware that redirects authenticated users away from guest-only pages
 * Use on routes that should only be accessible to non-authenticated users (login, register, etc.)
 * Prevents authenticated users from accessing guest pages
 */
abstract class IsNotAuthMiddleware extends Middleware
{
    /**
     * Checks if user is authenticated and redirects to home page if true
     * Looks for 'user' key in session to determine authentication status
     * If user exists in session, redirects to 'index' route
     */
    public static function handle(): void
    {
        // Check if user session data exists (user is authenticated)
        if (Session::has("user")) {
            // Redirect authenticated users to home page
            Router::redirect(Router::route("index"));
        }

        // If no user session, continue with request (allow access to guest-only page)
    }
}