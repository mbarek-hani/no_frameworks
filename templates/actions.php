<div class="content">
    <form class="search" method="get" action="/actions">
        <input type="text" name="q" placeholder="Search with action name..." value="<?= htmlspecialchars(
            $search_query,
        ) ?>"/> <input type="submit" value="search" />
    </form>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="20%">name</th>
                <th width="65%">description</th>
                <th width="10%">actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($actions) > 0): ?>
                <?php foreach ($actions as $action): ?>
                    <tr>
                        <td><?= $action["id"] ?></td>
                        <td><?= $action["name"] ?></td>
                        <td><?= $action["description"] ?></td>
                        <td class="actions">
                            <a href="/actions/edit/<?= $action["id"] ?>">
                                <img src="/assets/edit.svg" width="30" height="30" />
                            </a>
                            <form class="deleteForm" id="form<?= $action[
                                "id"
                            ] ?>" action="/actions/delete/<?= $action[
    "id"
] ?>" method="POST" style="display: none;"></form>
                            <button form="form<?= $action[
                                "id"
                            ] ?>" type="submit">
                                <img src="assets/delete.svg" width="30" height="30" />
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">There is no actions.</td>
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
                if(!confirm("Are you sure you want to delete this action?")) {
                    e.preventDefault();
                }
            });
        }
    </script>
