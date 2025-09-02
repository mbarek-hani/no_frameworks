<div class="content">
    <form class="search" method="get" action="/roles">
        <input type="text" name="q" placeholder="Search with role name..." value="<?= htmlspecialchars(
                                                                                        $search_query,
                                                                                    ) ?>" /> <input type="submit" value="search" />
    </form>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="20%">name</th>
                <th width="65%">description</th>
                <?php if (
                    mh_authorization_is_authorized_any([
                        "ReadRole",
                        "DeleteRole",
                    ])
                ): ?>
                    <th width="10%">actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (count($roles) > 0): ?>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= $role["id"] ?></td>
                        <td><?= $role["name"] ?></td>
                        <td><?= $role["description"] ?></td>
                        <?php if (
                            mh_authorization_is_authorized_any([
                                "ReadUser",
                                "DeleteUser",
                            ])
                        ): ?>
                            <td class="actions">
                                <?php if (mh_authorization_is_authorized("ReadRole")): ?>
                                    <a href="/roles/edit/<?= $role["id"] ?>">
                                        <img src="/assets/edit.svg" width="30" height="30" />
                                    </a>
                                <?php endif; ?>
                                <?php if (mh_authorization_is_authorized("DeleteRole")): ?>
                                    <form class="deleteForm" id="form<?= $role["id"] ?>" action="/roles/delete/<?= $role["id"] ?>" method="POST" style="display: none;"></form>
                                    <button form="form<?= $role["id"] ?>" type="submit">
                                        <img src="assets/delete.svg" width="30" height="30" />
                                    </button>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">There is no roles.</td>
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
<?php if (mh_authorization_is_authorized("DeleteRole")): ?>
    <script>
        const deleteForms = document.querySelectorAll(".deleteForm");
        for (let i = 0; i < deleteForms.length; i++) {
            deleteForms[i].addEventListener("submit", (e) => {
                if (!confirm("Are you sure you want to delete this role?")) {
                    e.preventDefault();
                }
            });
        }
    </script>
<?php endif; ?>