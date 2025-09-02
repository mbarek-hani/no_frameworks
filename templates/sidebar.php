<?php if (mh_authentication_is_user_logged_in()): ?>
    <div class="sidebar">
        <ul class="sidebar-nav">
            <li class="header">Access control</li>
            <li <?php echo str_starts_with($url_path, "/users")
                    ? 'class="active"'
                    : ""; ?>>
                <a href="/users">Users</a>
                <?php if (mh_authorization_is_authorized("CreateUser")): ?>
                    <a href="/users/add">+</a>
                <?php endif; ?>
            </li>
            <li <?php echo str_starts_with($url_path, "/roles")
                    ? 'class="active"'
                    : ""; ?>>
                <a href="/roles">roles</a>
                <?php if (mh_authorization_is_authorized("CreateRole")): ?>
                    <a href="/roles/add">+</a>
                <?php endif; ?>
            </li>
            <li <?php echo str_starts_with($url_path, "/actions")
                    ? 'class="active"'
                    : ""; ?>>
                <a href="/actions">actions</a>
                <?php if (mh_authorization_is_authorized("CreateAction")): ?>
                    <a href="/actions/add">+</a>
                <?php endif; ?>
            </li>
            <li class="header">Session</li>
            <li>
                <form action="/logout" method="POST" style="display: none;" id="logoutForm">
                </form>
                <button type="submit" form="logoutForm">Logout</button>
            </li>
        </ul>
    </div>
<?php endif; ?>