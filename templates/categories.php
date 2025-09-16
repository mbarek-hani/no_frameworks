<div class="content">
    <table style="margin-top: 70px;">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="85%">name</th>
                <th width="10%">actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($categories) > 0): ?>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= $category["id"] ?></td>
                        <td><?= str_repeat("&nbsp;", intval($category["depth"]) * 4) . $category["name"] ?></td>
                        <td class="actions">
                            <a href="/categories/edit/<?= $category["id"] ?>">
                                <img src="/assets/edit.svg" width="30" height="30" />
                            </a>
                            <form class="deleteForm" id="form<?= $category["id"] ?>" action="/categories/delete/<?= $category["id"] ?>" method="POST" style="display: none;"></form>
                            <button form="form<?= $category["id"] ?>" type="submit">
                                <img src="assets/delete.svg" width="30" height="30" />
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>

            <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
    const deleteForms = document.querySelectorAll(".deleteForm");
    for (let i = 0; i < deleteForms.length; i++) {
        deleteForms[i].addEventListener("submit", (e) => {
            if (!confirm("Are you sure you want to delete this category?")) {
                e.preventDefault();
            }
        });
    }
</script>