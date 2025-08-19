<div class="content">
    <form class="edit" method="POST" action="/users/edit/<?= $user["id"] ?>">
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="username" value="<?= $user["username"] ?>"/>
        </div>
        <?php if (isset($errors["username"])): ?>
            <p class="error"><?= $errors["username"] ?></p>
        <?php endif; ?>
        <div>
            <label for="firstname">First name</label>
            <input type="text" name="first_name" placeholder="firstname" value="<?= $user["first_name"] ?>"/>
        </div>
        <?php if (isset($errors["first_name"])): ?>
            <p class="error"><?= $errors["first_name"] ?></p>
        <?php endif; ?>
        <div>
            <label for="lastname">Last name</label>
            <input type="text" name="last_name" placeholder="lastname" value="<?= $user["last_name"] ?>"/>
        </div>
        <?php if (isset($errors["last_name"])): ?>
            <p class="error"><?= $errors["last_name"] ?></p>
        <?php endif; ?>
        <div>
            <label for="email">Email</label>
            <input type="text" name="email" placeholder="email" value="<?= $user["email" ?>"/>
        </div>
        <?php if (isset($errors["email"])): ?>
            <p class="error"><?= $errors["email"] ?></p>
        <?php endif; ?>
        <div>
            <input type="submit" value="Edit" />
            <a href="/users">Cancel</a>
        </div>
    </form>
    <h2 class="H2">Roles</h2>
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
            <?php if (count($roles) > 0): ?>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?= $role["id"] ?></td>
                        <td><?= $role["name"] ?></td>
                        <td><?= $role["description"] ?></td>
                        <td class="actions">
                            <!-- <form id="form<?= $role[
                                "id"
                            ] ?>" action="/roles/delete/<?= $role[
    "id"
] ?>" method="POST" style="display: none;"></form> -->
                            <button type="submit">
                                <img src="/assets/delete.svg" width="30" height="30" />
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">There are no roles associated with user "<?= $user[
                        "username"
                    ] ?>".</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
