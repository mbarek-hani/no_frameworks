<div class="sidebar">
    <ul class="sidebar-nav">
        <li class="header">Access control</li>
        <li <?php echo str_starts_with($url_path, "/users")
            ? 'class="active"'
            : ""; ?> >
            <a href="/users">Users</a>
            <a href="/users/add">+</a>
        </li>
        <li <?php echo str_starts_with($url_path, "/roles")
            ? 'class="active"'
            : ""; ?> >
            <a href="/roles">roles</a>
            <a href="/roles/add">+</a>
        </li>
        <li <?php echo str_starts_with($url_path, "/actions")
            ? 'class="active"'
            : ""; ?> >
                    <a href="/actions">actions</a>
                    <a href="/actions/add">+</a>
                </li>
    </ul>
</div>
