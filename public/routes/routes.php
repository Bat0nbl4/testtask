<?php

use vendor\routing\Router;

/**
 * Application route definitions
 * This file defines all HTTP routes and their corresponding controllers
 * Routes are organized in logical groups with shared middleware and prefixes
 */

// Main page route - requires authentication
Router::get("/", [\controllers\main\MainController::class, "index"], "index", [\middleware\IsAuthMiddleware::class]);

// User-related routes group
Router::group("/user", function () {
    // Logout route - requires authentication
    Router::get("/logout", [\controllers\user\UserActionController::class, "logout"], "logout", [\middleware\IsAuthMiddleware::class]);

    // Guest-only routes group (login/auth) - requires NOT being authenticated
    Router::group("", function () {
        // Login page - accessible only to guests
        Router::get("/login", [\controllers\user\UserController::class, "login"], "login");

        // Authentication action - processes login form
        Router::post("/auth", [\controllers\user\UserActionController::class, "auth"], "auth");
    }, [\middleware\IsNotAuthMiddleware::class]);
});

// Admin panel routes group
// All routes under /admin prefix
Router::group("/admin", function () {
    // User management sub-group
    // All routes under /admin/user prefix
    Router::group("/user", function () {
        // User listing page
        Router::get("/list", [\controllers\admin\AdminController::class, "list"], "admin.user.list");

        // User edit page
        Router::get("/edit", [\controllers\admin\AdminController::class, "edit"], "admin.user.edit");

        // User deletion action
        Router::get("/delete", [\controllers\admin\AdminActionController::class, "delete"], "admin.user.delete");

        // User creation page
        Router::get("/create", [\controllers\admin\AdminController::class, "create"], "admin.user.create");

        // User store action (create new user)
        Router::post("/store", [\controllers\admin\AdminActionController::class, "store"], "admin.user.store");

        // Update user data action
        Router::post("/put/data", [\controllers\admin\AdminActionController::class, "put_data"], "admin.user.put.data");

        // Update user password action
        Router::post("/put/password", [\controllers\admin\AdminActionController::class, "put_password"], "admin.user.put.password");

        // Generate test user action
        Router::get("/generate", [\controllers\admin\AdminActionController::class, "generate_user"], "admin.user.generate");
    });
    // Note: Admin routes would typically have IsAdminMiddleware applied globally or per-route
});

// Error handling routes group
Router::group("/catch", function () {
    // 404 Not Found error page
    Router::get("/404", [\controllers\catch\CatchController::class, "catch_404"], "404");

    // 403 Forbidden error page
    Router::get("/403", [\controllers\catch\CatchController::class, "catch_403"], "403");
});

/* --- ROUTE DEFINITION EXAMPLES ---

// Example of complex user management with different middleware groups
Router::group("/user", function () {
    // Authenticated user routes group
    Router::group("", function () {
        Router::get("/logout", [\controllers\user\UserActionController::class, "logout"], "logout");
        Router::get("/lk", [\controllers\user\UserController::class, "lk"], "lk");
    }, [\middleware\UserAuthMiddleware::class]);

    // Guest-only routes group
    Router::group("", function () {
        Router::get("/reg", [\controllers\user\UserController::class, "reg"], "reg");
        Router::post("/reg_action", [\controllers\user\UserActionController::class, "reg"], "reg_action");
        Router::get("/login", [\controllers\user\UserController::class, "login"], "login");
        Router::post("/login_action", [\controllers\user\UserActionController::class, "login"], "login_action");
    }, [\middleware\UserNotAuthMiddleware::class]);
});

// Route naming patterns:
// - Simple: "login", "logout"
// - Grouped: "admin.user.list", "admin.user.edit"
// - Action-based: "auth", "store", "delete"

// HTTP method usage:
// - GET: Display pages, simple actions
// - POST: Form submissions, data modifications

// Middleware application:
// - Per route: [\middleware\IsAuthMiddleware::class]
// - Per group: third parameter of Router::group()
// - Nested groups inherit parent middleware

*/