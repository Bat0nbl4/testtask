<?php \vendor\rendering\View::title("Edit user №".$user["id"]); ?>

<div class="container" style="margin-top: auto; margin-bottom: auto;">
    <div class="row justify-content-center gap-4">
        <form class="shadow-sm p-4 rounded-4 d-flex flex-column gap-2" style="max-width: 750px" method="POST" action="<?php echo \vendor\routing\Router::route("admin.user.put.data", ["user_id" => $user["id"]]) ?>">
            <div class="d-flex flex-column flex-md-row gap-2">
                <div class="flex-fill">
                    <label for="name" class="form-label">Name</label>
                    <input type="text"
                           name="name"
                           class="form-control <?php echo \vendor\session\Session::getFlash("input_errors.name") ? "is-invalid" : "" ?>"
                           value="<?php echo \vendor\session\Session::getFlash("old_input.name") ?? $user["name"] ?>"
                           id="name">
                    <span class="form-text text-danger"><?php echo \vendor\session\Session::getFlash("input_errors.name") ?></span>
                </div>
                <div class="flex-fill">
                    <label for="surname" class="form-label">Surname</label>
                    <input type="text"
                           name="surname"
                           class="form-control <?php echo \vendor\session\Session::getFlash("input_errors.surname") ? "is-invalid" : "" ?>"
                           value="<?php echo \vendor\session\Session::getFlash("old_input.surname") ?? $user["surname"] ?>"
                           id="surname">
                    <span class="form-text text-danger"><?php echo \vendor\session\Session::getFlash("input_errors.surname") ?></span>
                </div>
                <div class="flex-fill">
                    <label for="patronymic" class="form-label">Patronymic</label>
                    <input type="text"
                           name="patronymic"
                           class="form-control <?php echo \vendor\session\Session::getFlash("input_errors.patronymic") ? "is-invalid" : "" ?>"
                           value="<?php echo \vendor\session\Session::getFlash("old_input.patronymic") ?? $user["patronymic"] ?>"
                           id="patronymic">
                    <span class="form-text text-danger"><?php echo \vendor\session\Session::getFlash("input_errors.patronymic") ?></span>
                </div>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-2">
                <div class="flex-fill">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender"
                            id="gender"
                            class="form-control"
                            required>
                        <?php foreach (["M", "W"] as $gender): ?>
                            <option value="<?php echo $gender ?>" <?php if ($gender == (\vendor\session\Session::getFlash("old_input.gender") ?? $user["gender"])):?>selected<?php endif; ?>><?php echo $gender ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form-text text-danger"><?php echo \vendor\session\Session::getFlash("input_errors.gender") ?></span>
                </div>
                <div class="flex-fill">
                    <label for="role" class="form-label">Role</label>
                    <select name="role"
                            id="role"
                            class="form-control"
                            required>
                        <?php foreach (["None", "Admin"] as $role): ?>
                            <option value="<?php echo $role ?>" <?php if ($role == (\vendor\session\Session::getFlash("old_input.role") ?? $user["role"])): ?>selected<?php endif; ?>><?php echo $role ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form-text text-danger"><?php echo \vendor\session\Session::getFlash("input_errors.role") ?></span>
                </div>
            </div>
            <div>
                <label for="birthday" class="form-label">Birthday</label>
                <input type="date"
                       name="birthday"
                       class="form-control <?php echo \vendor\session\Session::getFlash("input_errors.birthday") ? "is-invalid" : "" ?>"
                       value="<?php echo \vendor\session\Session::getFlash("old_input.birthday") ?? $user["birthday"] ?>"
                       id="birthday">
                <span class="form-text text-danger"><?php echo \vendor\session\Session::getFlash("input_errors.birthday") ?></span>
            </div>
            <div>
                <label for="login" class="form-label">Login</label>
                <input type="text"
                       name="login"
                       class="form-control <?php echo \vendor\session\Session::getFlash("input_errors.login") ? "is-invalid" : "" ?>"
                       value="<?php echo \vendor\session\Session::getFlash("old_input.login") ?? $user["login"] ?>"
                       id="login">
                <span class="form-text text-danger"><?php echo \vendor\session\Session::getFlash("input_errors.login") ?></span>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-outline-danger">Clear form</button>
                <button type="submit" class="btn btn-primary">Store</button>
            </div>
        </form>
        <form class="shadow-sm p-4 rounded-4 d-flex flex-column gap-2" style="max-width: 750px" method="POST" action="<?php echo \vendor\routing\Router::route("admin.user.put.password", ["user_id" => $user["id"]]) ?>">
            <div class="d-flex flex-column flex-md-row gap-2">
                <div class="flex-fill">
                    <label for="password" class="form-label">New password</label>
                    <input type="password"
                           name="password"
                           class="form-control <?php echo \vendor\session\Session::getFlash("input_errors.password") ? "is-invalid" : "" ?>"
                           id="password">
                    <span class="form-text text-danger"><?php echo \vendor\session\Session::getFlash("input_errors.password") ?></span>
                </div>
                <div class="flex-fill">
                    <label for="password_confirmation" class="form-label">Confirm new password</label>
                    <input type="password"
                           name="password_confirmation"
                           class="form-control <?php echo \vendor\session\Session::getFlash("input_errors.password_confirmation") ? "is-invalid" : "" ?>"
                           id="password_confirmation">
                    <span class="form-text text-danger"><?php echo \vendor\session\Session::getFlash("input_errors.password_confirmation") ?></span>
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-outline-danger">Clear form</button>
                <button type="submit" class="btn btn-primary">Store</button>
            </div>
        </form>
    </div>
</div>
