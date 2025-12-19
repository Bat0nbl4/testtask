<?php

namespace middleware;

use vendor\middleware\Middleware;
use vendor\routing\Router;
use vendor\session\Session;

/**
 * Middleware that restricts access to admin-only routes
 * Use on routes that should only be accessible to users with admin role
 * Checks user role from session and redirects non-admins to 403 page
 */
abstract class IsAdminMiddleware extends Middleware
{
    /**
     * Verifies user has 'Администратор' (Administrator) role
     * Uses dot notation to access nested session data: user.role
     * Redirects to 403 (Forbidden) route if user is not an admin
     */
    public static function handle(): void
    {
        // Check if user's role is not "Администратор" (Administrator)
        // Uses loose comparison (!=) to allow type coercion if needed
        if (Session::get("user.role") != "Admin") {
            // Redirect non-admin users to 403 forbidden page
            Router::redirect(Router::route("403"));
        }

        // If user has admin role, continue with request (allow access to admin page)
    }
}