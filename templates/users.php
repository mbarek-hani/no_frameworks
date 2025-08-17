<div class="content">
    <form class="search" method="get" action="/users">
        <input type="text" name="q" placeholder="Search with username ..." value="<?= htmlspecialchars(
            $search_query,
        ) ?>"/>
        <input type="submit" value="search" />
    </form>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="20%">username</th>
                <th width="15%">first name</th>
                <th width="15%">last name</th>
                <th width="35%">email</th>
                <th width="10%">actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) > 0): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user["id"] ?></td>
                        <td><?= $user["username"] ?></td>
                        <td><?= $user["first_name"] ?></td>
                        <td><?= $user["last_name"] ?></td>
                        <td><?= $user["email"] ?></td>
                        <td class="actions">
                            <a href="/users/edit/<?= $user["id"] ?>">
                                <img src="/assets/edit.svg" width="30" height="30" />
                            </a>
                            <form class="deleteForm" id="form<?= $user[
                                "id"
                            ] ?>" action="/users/delete/<?= $user[
    "id"
] ?>" method="POST" style="display: none;"></form>
                            <button form="form<?= $user["id"] ?>" type="submit">
                                <img src="assets/delete.svg" width="30" height="30" />
                            </button>
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
    <?php mh_template_render_pager(
        $url,
        $search_query,
        $total_pages,
        $size,
        $current_page,
    ); ?>
</div>
<script>
    const deleteForms = document.querySelectorAll(".deleteForm");
    for (let i = 0; i<deleteForms.length; i++) {
        deleteForms[i].addEventListener("submit", (e) => {
            if(!confirm("Are you sure you want to delete this user?")) {
                e.preventDefault();
            }
        });
    }
</script>
