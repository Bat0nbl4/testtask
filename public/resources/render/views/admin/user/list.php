<?php \vendor\rendering\View::title("User list | Page: ".$current_page); ?>

<div class="d-flex justify-content-center">
    <?php if (\vendor\session\Session::hasFlash("success")): ?>
        <div class="col-sm-4 col-lg-3 m-3 bg-success bg-opacity-10 p-2 rounded-3 border border-success border-1 mb-3">
            <span class="text-success"><?php echo \vendor\session\Session::getFlash("success") ?></span>
        </div>
    <?php endif; ?>
</div>

<div class="container-lg">
    <table class="table">
        <thead>
            <tr>
                <th>id</th>
                <th>login</th>
                <th class="d-table-cell d-lg-none">full name</th>
                <th class="d-none d-lg-table-cell">name</th>
                <th class="d-none d-lg-table-cell">surname</th>
                <th class="d-none d-lg-table-cell">patronymic</th>
                <th>gender</th>
                <th>birthday</th>
                <th>role</th>
                <th class="w-0">action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user["id"] ?></td>
                    <td><?php echo $user["login"] ?></td>
                    <td class="d-table-cell d-lg-none"><?php echo $user["surname"] ?>&#160<?php echo substr($user["surname"], 0, 1) ?>.&#160<?php echo $user["patronymic"] ? substr($user["patronymic"], 0, 1)."." : "" ?></td>
                    <td class="d-none d-lg-table-cell"><?php echo $user["name"] ?></td>
                    <td class="d-none d-lg-table-cell"><?php echo $user["surname"] ?></td>
                    <td class="d-none d-lg-table-cell"><?php echo $user["patronymic"] ?></td>
                    <td><?php echo $user["gender"] ?></td>
                    <td><?php echo \vendor\helpers\Date::normal_date($user["birthday"]) ?></td>
                    <td><?php echo $user["role"] ?></td>
                    <td class="w-0">
                        <div class="d-flex flex-nowrap gap-1">
                            <a class="btn btn-primary" href="<?php echo \vendor\routing\Router::route("admin.user.edit", ["id" => $user["id"]]) ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                    <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                                </svg>
                            </a>
                            <a class="btn btn-danger" href="<?php echo \vendor\routing\Router::route("admin.user.delete", ["id" => $user["id"]]) ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="d-flex justify-content-center align-items-center gap-1 fs-4">
        <a class="text-decoration-none <?php echo $current_page > 1 ? "text-primary" : "text-secondary" ?>" href="<?php echo $current_page > 1 ? \vendor\routing\Router::route("admin.user.list", ["page" => 1]) : "" ?>">⋘</a>
        <a class="text-decoration-none <?php echo $current_page - 1 >= 1 ? "text-primary" : "text-secondary" ?>" href="<?php echo $current_page - 1 >= 1 ? \vendor\routing\Router::route("admin.user.list", ["page" => $current_page - 1]) : "" ?>"><</a>
        <a class="text-decoration-none"><?php echo $current_page ?></a>
        <a class="text-decoration-none <?php echo $current_page + 1 <= $page_count ? "text-primary" : "text-secondary" ?>" href="<?php echo $current_page + 1 <= $page_count ? \vendor\routing\Router::route("admin.user.list", ["page" => $current_page + 1]) : ""?>">></a>
        <a class="text-decoration-none <?php echo $current_page < $page_count ? "text-primary" : "text-secondary" ?>" href="<?php echo $current_page < $page_count ? \vendor\routing\Router::route("admin.user.list", ["page" => $page_count]) : "" ?>">⋙</a>
    </div>
</div>