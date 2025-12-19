<footer class="bg-dark p-3">
    <?php if (\vendor\session\Session::get("user.role") == "Admin") ?>
    <a class="btn btn-outline-secondary mb-1" data-bs-toggle="collapse" href="#session_data" role="button" aria-expanded="false" aria-controls="collapseExample">session data (debug)</a>
    <pre class="collapse text-white " id="session_data">
        <?php print_r(\vendor\session\Session::all()); ?>
    </pre>
</footer>