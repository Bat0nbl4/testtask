<?php

namespace vendor\routing;

/**
 * Router class for handling HTTP request routing, middleware, and URL generation
 * Supports route grouping, named routes, middleware, and controller method parameter binding
 */
class Router
{
    // Stores all registered routes with their configurations
    protected static $routes = [];

    // Stack for nested route groups (prefixes and middleware)
    protected static $groupStack = [];

    /**
     * Register a GET route
     *
     * @param string $path URL path
     * @param array $callback [ControllerClass, methodName]
     * @param string $name Route identifier for URL generation
     * @param array $middleware Middleware classes to apply
     */
    public static function get(string $path, array $callback, string $name, array $middleware = []): void
    {
        self::addRoute('GET', $path, $callback, $name, $middleware);
    }

    /**
     * Register a POST route
     *
     * @param string $path URL path
     * @param array $callback [ControllerClass, methodName]
     * @param string $name Route identifier for URL generation
     * @param array $middleware Middleware classes to apply
     */
    public static function post(string $path, array $callback, string $name, array $middleware = []): void
    {
        self::addRoute('POST', $path, $callback, $name, $middleware);
    }

    /**
     * Create a route group with common prefix and/or middleware
     *
     * @param string $prefix URL prefix for all routes in the group
     * @param callable $callback Function containing route definitions
     * @param array $middleware Middleware applied to all routes in group
     */
    public static function group(string $prefix, callable $callback, array $middleware = []): void
    {
        // Push group configuration onto stack
        self::$groupStack[] = [
            'prefix' => $prefix,
            'middleware' => $middleware,
        ];

        // Execute callback to define routes within the group
        $callback();

        // Remove group from stack after callback execution
        array_pop(self::$groupStack);
    }

    /**
     * Internal method to add a route with all configurations
     *
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $path URL path
     * @param array $callback [ControllerClass, methodName]
     * @param string $name Route identifier
     * @param array $routeMiddleware Route-specific middleware
     */
    protected static function addRoute(string $method, string $path, array $callback, string $name, array $routeMiddleware = []): void
    {
        // Apply group prefixes to the path
        $path = self::applyGroupPrefix($path);

        // Prepend base path if configured
        if (defined('USE_BASE_PATH') && USE_BASE_PATH) {
            $path = BASE_PATH . $path;
        }

        // Collect middleware from all parent groups
        $groupMiddleware = [];
        foreach (self::$groupStack as $group) {
            $groupMiddleware = array_merge($groupMiddleware, $group['middleware']);
        }

        // Merge group middleware with route-specific middleware
        $middleware = array_merge($groupMiddleware, $routeMiddleware);

        // Store the route with all its configurations
        self::$routes[$name] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
            'middleware' => $middleware,
        ];
    }

    /**
     * Apply accumulated group prefixes to a route path
     *
     * @param string $path Original route path
     * @return string Path with all group prefixes applied
     */
    protected static function applyGroupPrefix(string $path): string
    {
        $fullPrefix = '';
        foreach (self::$groupStack as $group) {
            $fullPrefix .= $group['prefix'];
        }
        return $fullPrefix . $path;
    }

    /**
     * Generate a URL for a named route with optional parameters
     *
     * @param string $name Route name
     * @param array $params Query parameters
     * @return string Full URL
     * @throws \Exception If route name is not found
     */
    public static function route(string $name, array $params = []): string
    {
        if (!isset(self::$routes[$name])) {
            http_response_code(404);
            throw new \Exception("Route '{$name}' not found.");
        }

        // Build full URL with protocol, domain, and path
        $path = BASE_METHOD."://".APP_DOMEN.self::$routes[$name]['path'];
        $query = http_build_query($params);

        // Append query string if parameters provided
        return $path . ($query ? '?'.$query : '');
    }

    /**
     * Perform an HTTP redirect
     *
     * @param string $url Target URL
     * @param int $statusCode HTTP status code (default: 302 Found)
     */
    public static function redirect(string $url, int $statusCode = 302): void
    {
        header("Location: $url", true, $statusCode);
        exit;
    }

    /**
     * Returns the URL of the previous page (HTTP Referer)
     * If Referer is not set, returns a fallback URL
     *
     * @param string $fallbackUrl URL to return if Referer is not found
     * @return string Previous page URL or fallback
     */
    public static function back(string $fallbackUrl = '/'): string
    {
        return $_SERVER['HTTP_REFERER'] ?? $fallbackUrl;
    }

    /**
     * Main routing resolution method
     * Matches current request against registered routes and executes the appropriate controller
     */
    public static function resolve(): void
    {
        // Extract path from request URI (remove query string)
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Iterate through all registered routes to find a match
        foreach (self::$routes as $route) {
            // Skip if method or path doesn't match
            if ($route['method'] !== $method || $route['path'] !== $uri) {
                continue;
            }

            // Execute all middleware associated with the route
            foreach ($route['middleware'] as $middlewareClass) {
                $middlewareClass::handle();
            }

            // Extract controller class and method from callback
            [$class, $methodName] = $route['callback'];
            $controller = new $class();

            // Use Reflection to get controller method parameters for automatic binding
            $reflectionMethod = new \ReflectionMethod($class, $methodName);
            $parameters = $reflectionMethod->getParameters();
            $args = [];

            // Build arguments array by matching request parameters to method parameters
            foreach ($parameters as $param) {
                $paramName = $param->getName();
                $value = $_GET[$paramName] ?? null;

                // Type casting if parameter has a type hint
                if ($value !== null && $param->getType()) {
                    $type = $param->getType()->getName();
                    settype($value, $type);
                }

                // Use parameter value, default value, or null
                $args[] = $value ?? ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : null);
            }

            // Execute controller method with resolved arguments
            $controller->$methodName(...$args);
            return;
        }

        // If no route matches, redirect to 404 error route
        self::redirect(self::route(catchRoutes["404"]));
    }
}