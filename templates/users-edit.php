<?php $canUpdate = mh_authorization_is_authorized("UpdateUser"); ?>
<div class="content">
    <form class="edit" <?php if (
                            $canUpdate
                        ): ?> method="POST" action="/users/edit/<?= $user["id"] ?>" <?php endif; ?>>
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="username" value="<?= $user["username"] ?>" <?= !$canUpdate ? "readonly" : "" ?> />
        </div>
        <?php if (isset($errors["username"])): ?>
            <p class="error"><?= $errors["username"] ?></p>
        <?php endif; ?>
        <div>
            <label for="firstname">First name</label>
            <input type="text" name="first_name" placeholder="firstname" value="<?= $user["first_name"] ?>" <?= !$canUpdate ? "readonly" : "" ?> />
        </div>
        <?php if (isset($errors["first_name"])): ?>
            <p class="error"><?= $errors["first_name"] ?></p>
        <?php endif; ?>
        <div>
            <label for="lastname">Last name</label>
            <input type="text" name="last_name" placeholder="lastname" value="<?= $user["last_name"] ?>" <?= !$canUpdate ? "readonly" : "" ?> />
        </div>
        <?php if (isset($errors["last_name"])): ?>
            <p class="error"><?= $errors["last_name"] ?></p>
        <?php endif; ?>
        <div>
            <label for="email">Email</label>
            <input type="text" name="email" placeholder="email" value="<?= $user["email"] ?>" <?= !$canUpdate ? "readonly" : "" ?> />
        </div>
        <?php if (isset($errors["email"])): ?>
            <p class="error"><?= $errors["email"] ?></p>
        <?php endif; ?>
        <?php if ($canUpdate): ?>
            <div>
                <input type="submit" value="Edit" />
                <a href="/users">Cancel</a>
            </div>
        <?php else: ?>
            <div>
                <a href="/users">Cancel</a>
            </div>
        <?php endif; ?>
    </form>
    <h2 class="H2">Roles</h2>
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
            <?php if (count($user_roles) > 0): ?>
                <?php foreach ($user_roles as $role): ?>
                    <tr>
                        <td><?= $role["id"] ?></td>
                        <td><?= $role["name"] ?></td>
                        <td><?= $role["description"] ?></td>
                        <?php if ($canUpdate): ?>
                            <td class="actions">
                                <form class="removeForm" action="/users/edit/<?= $user["id"] ?>" method="POST" id="form<?= $role["id"] ?>" style="display: none;">
                                    <input type="hidden" name="action" value="remove" />
                                    <input type="hidden" name="role" value="<?= $role["id"] ?>" />
                                </form>
                                <button type="submit" form="form<?= $role["id"] ?>">
                                    <img src="/assets/delete.svg" width="30" height="30" />
                                </button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">There are no roles associated with user "<?= $user["username"] ?>".</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php if ($canUpdate): ?>
        <form class="add-role" method="POST" action="/users/edit/<?= $user["id"] ?>">
            <input type="hidden" name="action" value="add" />
            <div>
                <label for="role">Role</label>
                <?php if (count($other_roles) > 0): ?>
                    <select id="role" name="role">
                        <?php foreach ($other_roles as $role): ?>
                            <option value="<?= $role["id"] ?>"><?= $role["name"] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="submit" value="Add" />
                <?php else: ?>
                    <select disabled>
                        <option>No roles found.</option>
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
                if (!confirm("Are you sure you want to remove this role from user \"<?= $user["username"] ?>\"?")) {
                    e.preventDefault();
                }
            });
        }
    </script>
<?php endif; ?>