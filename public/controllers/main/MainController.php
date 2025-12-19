<?php

namespace controllers\main;

use controllers\Controller;
use vendor\rendering\View;
use vendor\session\Session;

/**
 * Main site controller handling primary application pages
 * Responsible for rendering the main application interface
 */
class MainController extends Controller
{
    /**
     * Displays the main application index/dashboard page
     * This is the primary entry point for authenticated users
     * Renders the main template with dashboard content
     *
     * Route: GET /
     * Route name: "index"
     * Middleware: IsAuthMiddleware (requires authentication)
     */
    public function index() {
        // Render the 'index' view template
        // View::render() will automatically use the default template and inject content
        View::render("index");
    }
}