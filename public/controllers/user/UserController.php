<?php

namespace controllers\user;

use controllers\Controller;
use vendor\rendering\View;

/**
 * User view controller - displays user-related pages
 */
class UserController extends Controller
{
    /**
     * Display login page
     */
    public function login() {
        View::render("user/login");
    }
}