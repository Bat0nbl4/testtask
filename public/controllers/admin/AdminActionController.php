<?php

namespace controllers\admin;

use controllers\Controller;
use vendor\data_base\DB;
use vendor\helpers\Date;
use vendor\helpers\Str;
use vendor\routing\Router;
use vendor\session\Session;

/**
 * Admin action controller - handles user management actions (CRUD operations)
 */
class AdminActionController extends Controller
{
    /**
     * Validate password and password confirmation
     */
    private function validate_password() : bool {
        $isValidated = true;

        if (empty($_POST["password"])) {
            Session::flash("input_errors.password", "This field is required!");
            $isValidated = false;
        } elseif (strlen($_POST["password"]) < 8) {
            Session::flash("input_errors.password", "The minimum password length is 8 characters.");
            $isValidated = false;
        }
        if ($_POST["password"] != $_POST["password_confirmation"]) {
            Session::flash("input_errors.password_confirmation", "The passwords don't match.");
            $isValidated = false;
        }

        return $isValidated;
    }

    /**
     * Validate user data (excluding password)
     * @param string|null $ignore_login Login to exclude from uniqueness check (for updates)
     */
    private function validate_data(string $ignore_login = null) : bool {
        $isValidated = true;

        // Required field validations
        if (empty($_POST["name"])) {
            Session::flash("input_errors.name", "This field is required!");
            $isValidated = false;
        }
        if (empty($_POST["surname"])) {
            Session::flash("input_errors.surname", "This field is required!");
            $isValidated = false;
        }
        if (empty($_POST["birthday"])) {
            Session::flash("input_errors.birthday", "This field is required!");
            $isValidated = false;
        }

        // Gender validation (M/W)
        if (empty($_POST["gender"])) {
            Session::flash("input_errors.gender", "This field is required!");
            $isValidated = false;
        } elseif (!in_array($_POST["gender"], ["M", "W"])) {
            Session::flash("input_errors.gender", "This value is unacceptable!");
            $isValidated = false;
        }

        // Role validation (None/Admin)
        if (empty($_POST["role"])) {
            Session::flash("input_errors.role", "This field is required!");
            $isValidated = false;
        } elseif (!in_array($_POST["role"], ["None", "Admin"])) {
            Session::flash("input_errors.role", "This value is unacceptable!");
            $isValidated = false;
        }

        // Login validation and uniqueness check
        if (empty($_POST["login"])) {
            Session::flash("input_errors.login", "This field is required!");
            $isValidated = false;
        } elseif ($ignore_login != $_POST["login"] and DB::query()->from("user")->where("login", "=", $_POST["login"])->first()) {
            Session::flash("input_errors.login", "Such a login already exists.");
            $isValidated = false;
        }

        return $isValidated;
    }

    /**
     * Create new user (store action)
     */
    public function store() {
        if ($this->validate_data() && $this->validate_password()) {
            $user_id = DB::query()
                ->from("user")
                ->set([
                    "login" => $_POST["login"],
                    "name" => $_POST["name"],
                    "surname" => $_POST["surname"],
                    "patronymic" => $_POST["patronymic"],
                    "gender" => $_POST["gender"],
                    "birthday" => $_POST["birthday"],
                    "role" => $_POST["role"],
                    "password" => \Hash::hashPassword($_POST["password"]),
                ])
                ->insert();

            Session::flash("message", [
                "type" => "success",
                "text" => "A user with ID <a href='".Router::route("admin.user.edit", ["id" => $user_id])."'>{$user_id}</a> has been successfully created!"
            ]);
            Router::redirect(Router::back(Router::route("admin.user.list")));
            return;
        }

        // Preserve form data (except passwords) on validation error
        Session::flash("old_input", $_POST);
        Session::removeFlash("old_input.password");
        Session::removeFlash("old_input.password_confirmation");

        Router::redirect(Router::back(Router::route("admin.user.create")));
    }

    /**
     * Update user data
     */
    public function put_data(int $user_id) {
        $user = DB::query()
            ->from("user")
            ->select(["login"])
            ->where("id", "=", $user_id)
            ->first();

        if ($this->validate_data($user["login"])) {
            DB::query()
                ->from("user")
                ->where("id", "=", $user_id)
                ->update([
                    "login" => $_POST["login"],
                    "name" => $_POST["name"],
                    "surname" => $_POST["surname"],
                    "patronymic" => $_POST["patronymic"],
                    "gender" => $_POST["gender"],
                    "birthday" => $_POST["birthday"],
                    "role" => $_POST["role"],
                ]);
            Session::flash("message", [
                "type" => "success",
                "text" => "User's data has been successfully updated"
            ]);
            Router::redirect(Router::back(Router::route("admin.user.edit", ["id" => $user_id])));
            return;
        }

        Session::flash("old_input", $_POST);
        Router::redirect(Router::back(Router::route("admin.user.edit", ["id" => $user_id])));
    }

    /**
     * Generate a test user with random data (for testing/demo)
     */
    public function generate_user() {
        DB::query()
            ->from("user")
            ->set([
                "login" => Str::random(6),
                "name" => ["Andrew", "Basil", "Gregory", "Daniel", "Elias"][random_int(0, 4)],
                "surname" => ["Ivanov", "Eltsin", "Khrushchev", "Yushchenko", "Chaikovsky"][random_int(0, 4)],
                "patronymic" => ["Aleksandrovich", "Valerevich", "Vitalevich", "Dmitrievich", "Evgenevich"][random_int(0, 4)],
                "gender" => "M",
                "birthday" => Date::randomDate("1985-01-01", "2007-31-12"),
                "role" => "None",
                "password" => \Hash::hashPassword("12341234"),
            ])
            ->insert();

        echo 1; // Simple success response
    }

    /**
     * Update user password
     */
    public function put_password(int $user_id) {
        if ($this->validate_password()) {
            DB::query()
                ->from("user")
                ->where("id", "=", $user_id)
                ->update([
                    "password" => \Hash::hashPassword($_POST["password"]),
                ]);
            Session::flash("message", [
                "type" => "success",
                "text" => "Password has been successfully updated."
            ]);
        }

        Router::redirect(Router::back(Router::route("admin.user.edit", ["id" => $user_id])));
    }

    /**
     * Delete user
     */
    public function delete(int $id) {
        DB::query()
            ->from("user")
            ->where("id", "=", $id)
            ->delete();

        Router::redirect(Router::back(Router::route("admin.user.list")));
    }
}