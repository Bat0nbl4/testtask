<?php

namespace controllers\catch;

use controllers\Controller;
use vendor\rendering\View;

/**
 * Error page controller
 * Handles HTTP error responses (404, 403, etc.)
 */
class CatchController extends Controller
{
    /**
     * Display 404 Not Found error page
     */
    public function catch_404() {
        View::render("catch/404");
    }

    /**
     * Display 403 Forbidden error page
     */
    public function catch_403() {
        View::render("catch/403");
    }
}