<?php \vendor\rendering\View::title("Вход"); ?>

<div class="container" style="margin-top: auto; margin-bottom: auto;">
    <div class="row justify-content-center">
        <form class="shadow-sm p-4 rounded-4" style="max-width: 450px" method="POST" action="<?php echo \vendor\routing\Router::route("auth") ?>">
            <p class="text-center fs-2 fw-semibold ">Login</p>
            <div class="mb-3">
                <label for="login" class="form-label">Login</label>
                <input type="text"
                       name="login"
                       class="form-control <?php echo \vendor\session\Session::getFlash("input_errors") ? "is-invalid" : "" ?>"
                       value="<?php echo \vendor\session\Session::getFlash("old_input.login") ?>"
                       id="login">
                <span class="form-text text-danger"><?php echo \vendor\session\Session::getFlash("input_errors.login") ?></span>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password"
                       name="password"
                       class="form-control <?php echo \vendor\session\Session::getFlash("input_errors") ? "is-invalid" : "" ?>"
                       id="password">
                <span class="form-text text-danger"><?php echo \vendor\session\Session::getFlash("input_errors.password") ?></span>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-secondary">Clear form</button>
                <button type="submit" class="btn btn-primary">Log in</button>
            </div>
        </form>
    </div>
</div>