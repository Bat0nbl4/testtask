<?php

namespace controllers\admin;

use controllers\Controller;
use vendor\data_base\DB;
use vendor\rendering\View;

/**
 * Admin view controller - displays admin management pages
 */
class AdminController extends Controller
{
    /**
     * Display paginated user list
     * @param int $page Current page number (default: 1)
     */
    public function list(int $page = 1) {
        $users_on_page = 10;

        // Get total user count for pagination
        $users_count = DB::query()
            ->from("user")
            ->select(["COUNT(id)"])
            ->get()[0]["COUNT(id)"];

        $page_count = ceil($users_count / $users_on_page);

        // Get users for current page
        $users = DB::query()
            ->from("user")
            ->select([
                "user.id",
                "user.login",
                "user.name",
                "user.surname",
                "user.patronymic",
                "user.gender",
                "user.birthday",
                "user.role",
            ])
            ->orderBy("id", "DESC")
            ->offset(($page-1)*$users_on_page)
            ->limit($users_on_page)
            ->get();

        View::render("admin/user/list", [
            "users" => $users,
            "page_count" => $page_count,
            "current_page" => $page
        ]);
    }

    /**
     * Display user creation form
     */
    public function create() {
        View::render("admin/user/create");
    }

    /**
     * Display user edit form
     * @param int $id User ID to edit
     */
    public function edit(int $id) {
        $user = DB::query()
            ->from("user")
            ->select([
                "user.id",
                "user.login",
                "user.name",
                "user.surname",
                "user.patronymic",
                "user.gender",
                "user.birthday",
                "user.role",
            ])
            ->where("user.id", "=", $id)
            ->first();

        View::render("admin/user/edit", ["user" => $user]);
    }
}