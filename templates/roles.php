<div class="content">
    <form class="search" method="get" action="/roles">
        <input type="text" name="q" placeholder="Search with role name..." value="<?= htmlspecialchars(
            $search_query,
        ) ?>"/> <input type="submit" value="search" />
    </form>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="20%">name</th>
                <th width="75%">description</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($roles) > 0): ?>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= $role["id"] ?></td>
                        <td><?= $role["name"] ?></td>
                        <td><?= $role["description"] ?></td>
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
