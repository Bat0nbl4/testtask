<?php

namespace controllers\user;

use controllers\Controller;
use vendor\data_base\DB;
use vendor\routing\Router;
use vendor\session\Session;

/**
 * User action controller - handles user authentication actions
 * Processes form submissions and authentication logic
 */
class UserActionController extends Controller
{
    /**
     * Authenticate user login
     * Validates credentials and sets session on success
     */
    public function auth() {
        $isValidated = true;

        // Validate required fields
        if (empty($_POST["login"])) {
            Session::flash("input_errors.login", "Это поле обязательно для заполнения!");
            $isValidated = false;
        }
        if (empty($_POST["password"])) {
            Session::flash("input_errors.password", "Это поле обязательно для заполнения!");
            $isValidated = false;
        }

        // Check credentials if validation passed
        if ($isValidated) {
            $user = DB::query()
                ->from("user")
                ->select(["*"])
                ->where("login", "=", $_POST["login"])
                ->first();

            // Verify password hash
            if (!$user or !\Hash::verifyPassword($_POST["password"], $user["password"])) {
                Session::flash("input_errors.password", "Неверный логин или пароль.");
                $isValidated = false;
            }
        }

        // Redirect with errors if validation failed
        if (!$isValidated) {
            Session::flash("old_input", $_POST);
            Session::removeFlash("old_input.password"); // Don't persist password
            Session::remove("user");
            Router::redirect(Router::route("index"));
            return;
        }

        // Clear session and set user data (remove password from session)
        Session::clear();
        Session::set("user", $user);
        Session::remove("user.password");

        Router::redirect(Router::route("index"));
    }

    /**
     * Logout user and clear session
     */
    public function logout() {
        Session::clear();
        Router::redirect(Router::route("index"));
    }
}