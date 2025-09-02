<?php $canUpdate = mh_authorization_is_authorized("UpdateRole"); ?>
<div class="content">
    <form class="edit" <?php if (
                            $canUpdate
                        ): ?> method="POST" action="/roles/edit/<?= $role["id"] ?>" <?php endif; ?>>
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" placeholder="name" value="<?= $role["name"] ?>" <?= !$canUpdate ? "readonly" : "" ?> />
        </div>
        <?php if (isset($errors["name"])): ?>
            <p class="error"><?= $errors["name"] ?></p>
        <?php endif; ?>
        <div>
            <label for="description">Description</label>
            <input type="text" name="description" placeholder="Description..." value="<?= $role["description"] ?>" <?= !$canUpdate ? "readonly" : "" ?> />
        </div>
        <?php if (isset($errors["description"])): ?>
            <p class="error"><?= $errors["description"] ?></p>
        <?php endif; ?>
        <?php if ($canUpdate): ?>
            <div>
                <input type="submit" value="Edit" />
                <a href="/roles">Cancel</a>
            </div>
        <?php else: ?>
            <div>
                <a href="/roles">Cancel</a>
            </div>
        <?php endif; ?>
    </form>
    <h2 class="H2">Actions</h2>
    <table>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="20%">name</th>
                <th width="65%">description</th>
                <?php if ($canUpdate): ?>
                    <th width="10%">remove</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (count($role_actions) > 0): ?>
                <?php foreach ($role_actions as $action): ?>
                    <tr>
                        <td><?= $action["id"] ?></td>
                        <td><?= $action["name"] ?></td>
                        <td><?= $action["description"] ?></td>
                        <?php if ($canUpdate): ?>
                            <td class="actions">
                                <form class="removeForm" action="/roles/edit/<?= $role["id"] ?>" method="POST" id="form<?= $action["id"] ?>" style="display: none;">
                                    <input type="hidden" name="do" value="remove" />
                                    <input type="hidden" name="action" value="<?= $action["id"] ?>" />
                                </form>
                                <button type="submit" form="form<?= $action["id"] ?>">
                                    <img src="/assets/delete.svg" width="30" height="30" />
                                </button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">There are no actions associated with role "<?= $role["name"] ?>".</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php if ($canUpdate): ?>
        <form class="add-role" method="POST" action="/roles/edit/<?= $role["id"] ?>">
            <input type="hidden" name="do" value="add" />
            <div>
                <label for="action">action</label>
                <?php if (count($other_actions) > 0): ?>
                    <select id="action" name="action">
                        <?php foreach ($other_actions as $action): ?>
                            <option value="<?= $action["id"] ?>"><?= $action["name"] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="submit" value="Add" />
                <?php else: ?>
                    <select disabled>
                        <option>No actions found.</option>
                    </select>
                    <input type="submit" value="Add" disabled />
                <?php endif; ?>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php if ($canUpdate): ?>
    <script>
        const removeForms = document.querySelectorAll(".removeForm");
        for (let i = 0; i < removeForms.length; i++) {
            removeForms[i].addEventListener("submit", (e) => {
                if (!confirm("Are you sure you want to remove this action from role \"<?= $role["name"] ?>\"?")) {
                    e.preventDefault();
                }
            });
        }
    </script>
<?php endif; ?>