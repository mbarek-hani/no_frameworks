<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>All users</title>
    <link rel="stylesheet" href="/css/default.css" />
</head>

<body>
    <form class="search" method="get" action="/users">
        <input type="text" name="q" placeholder="Search with username ..." value="<?= htmlspecialchars($search_query) ?>"/>
        <input type="submit" value="search" />
    </form>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="20%">username</th>
                <th width="20%">first name</th>
                <th width="20%">last name</th>
                <th width="35%">email</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) > 0) : ?>
                <?php foreach($users as $user): ?>
                    <tr>
                        <td><?= $user["id"]; ?></td>
                        <td><a href="/users/edit/<?= htmlspecialchars($user["id"]) ?>"><?= $user["username"]; ?></a></td>
                        <td><?= $user["first_name"]; ?></td>
                        <td><?= $user["last_name"]; ?></td>
                        <td><?= $user["email"]; ?></td>
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
</body>

</html>
