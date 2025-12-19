<header class="d-flex justify-content-center py-3 border-bottom mb-3">
    <ul class="nav nav-pills">
        <li class="nav-item"><a href="<?php echo \vendor\routing\Router::route("index") ?>" class="nav-link">Main</a></li>
        <?php if (\vendor\session\Session::get("user.role") == "Admin"): ?>
            <li class="nav-item"><a href="<?php echo \vendor\routing\Router::route("admin.user.list") ?>" class="nav-link">Users list</a></li>
            <li class="nav-item"><a href="<?php echo \vendor\routing\Router::route("admin.user.create") ?>" class="nav-link">Create new user</a></li>
        <?php endif; ?>
        <?php if (\vendor\session\Session::has("user")): ?>
            <li class="nav-item"><a href="<?php echo \vendor\routing\Router::route("logout") ?>" class="nav-link text-danger">Log out</a></li>
        <?php else: ?>
            <li class="nav-item"><a href="<?php echo \vendor\routing\Router::route("login") ?>" class="nav-link">Log in</a></li>
        <?php endif; ?>
    </ul>
</header>