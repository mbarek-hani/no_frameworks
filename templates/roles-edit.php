<div class="content">
    <form class="edit" method="POST" action="/roles/edit/<?= $role["id"] ?>">
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" placeholder="name" value="<?= $role[
                "name"
            ] ?>" />
        </div>
        <?php if (isset($errors["name"])): ?>
            <p class="error"><?= $errors["name"] ?></p>
        <?php endif; ?>
        <div>
            <label for="description">Description</label>
            <input type="text" name="description" placeholder="Description..." value="<?= $role[
                "description"
            ] ?>" />
        </div>
        <?php if (isset($errors["description"])): ?>
            <p class="error"><?= $errors["description"] ?></p>
        <?php endif; ?>
        <div>
            <input type="submit" value="Edit" />
            <a href="/roles">Cancel</a>
        </div>
    </form>
    <h2 class="H2">Actions</h2>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="20%">name</th>
                <th width="65%">description</th>
                <th width="10%">remove</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($role_actions) > 0): ?>
                <?php foreach ($role_actions as $action): ?>
                    <tr>
                        <td><?= $action["id"] ?></td>
                        <td><?= $action["name"] ?></td>
                        <td><?= $action["description"] ?></td>
                        <td class="actions">
                            <form class="removeForm" action="/roles/edit/<?= $role[
                                "id"
                            ] ?>" method="POST" id="form<?= $action[
    "id"
] ?>" style="display: none;">
                                <input type="hidden" name="do" value="remove" />
                                <input type="hidden" name="action" value="<?= $action[
                                    "id"
                                ] ?>" />
                            </form>
                            <button type="submit" form="form<?= $action[
                                "id"
                            ] ?>">
                                <img src="/assets/delete.svg" width="30" height="30" />
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">There are no actions associated with role "<?= $role[
                        "name"
                    ] ?>".</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <form class="add-role" method="POST" action="/roles/edit/<?= $role[
        "id"
    ] ?>">
        <input type="hidden" name="do" value="add" />
        <div>
            <label for="action">action</label>
                <?php if (count($other_actions) > 0): ?>
                    <select id="action" name="action">
                        <?php foreach ($other_actions as $action): ?>
                            <option value="<?= $action["id"] ?>"><?= $action[
    "name"
] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="submit" value="Add" />
                <?php else: ?>
                    <select disabled>
                        <option>No actions found.</option>
                    </select>
                    <input type="submit" value="Add" disabled/>
                <?php endif; ?>
        </div>
    </form>
</div>
<script>
    const removeForms = document.querySelectorAll(".removeForm");
    for (let i = 0; i<removeForms.length; i++) {
        removeForms[i].addEventListener("submit", (e) => {
            if(!confirm("Are you sure you want to remove this action from role \"<?= $role[
                "name"
            ] ?>\"?")) {
                e.preventDefault();
            }
        });
    }
</script>
