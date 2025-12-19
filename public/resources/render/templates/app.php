<!doctype html>
<html lang="ru">
<?php \vendor\rendering\View::IncludeComponent("base/head") ?>
<body class="d-flex flex-column min-vh-100">
    <?php \vendor\rendering\View::IncludeComponent("base/header") ?>
    <main class="d-flex flex-column flex-fill">
        <!-- Global flash message container -->
        <div class="container">
            <div class="row justify-content-center">
                <?php if (\vendor\session\Session::getFlash("message")): ?>
                    <!-- Bootstrap alert for flash messages -->
                    <div class="alert alert-<?php echo \vendor\session\Session::getFlash("message.type") ?> alert-dismissible fade show" role="alert" style="max-width: 500px">
                        <?php echo \vendor\session\Session::getFlash("message.text") ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Main content area (replaced with specific view content) -->
        <?php \vendor\rendering\View::content() ?>
    </main>
    <?php \vendor\rendering\View::IncludeComponent("base/footer") ?>
</body>
</html>