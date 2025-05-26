<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>All users</title>
    <link rel="stylesheet" href="/css/default.css" />
</head>

<body>
    <div class="sidebar">
        <ul class="sidebar-nav">
            <li class="header">Access control</li>
            <li class="active">
                <a href="/users">Users</a>
                <a href="/users/add">+</a>
            </li>
        </ul>
    </div>
    <div class="content">
        <form class="search" method="get" action="/users">
            <input type="text" name="q" placeholder="Search with username ..." value="<?= htmlspecialchars($search_query) ?>"/>
            <input type="submit" value="search" />
        </form>
        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="20%">username</th>
                    <th width="15%">first name</th>
                    <th width="15%">last name</th>
                    <th width="30%">email</th>
                    <th width="15%">actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0) : ?>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td><?= $user["id"]; ?></td>
                            <td><?= $user["username"]; ?></td>
                            <td><?= $user["first_name"]; ?></td>
                            <td><?= $user["last_name"]; ?></td>
                            <td><?= $user["email"]; ?></td>
                            <td class="actions">
                                <a href="/users/edit/<?= htmlspecialchars($user["id"]) ?>">Edit</a>
                                <form id="form<?= htmlspecialchars($user["id"]) ?>" action="/users/delete/<?= htmlspecialchars($user["id"]) ?>" method="POST" style="display: none;"></form>
                                <button form="form<?= htmlspecialchars($user["id"]) ?>" type="submit">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">There is no users.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php mh_template_render_pager($url, $search_query, $total_pages, $size, $current_page); ?>
    </div>
</body>

</html>
