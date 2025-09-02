    <div class="content">
        <form class="edit" method="POST" action="/users/add">
            <div>
                <label for="username">Username</label>
                <input type="text" name="username" placeholder="username" value="<?= htmlspecialchars($user["username"]) ?>" />
            </div>
            <?php if (isset($errors["username"])): ?>
                <p class="error"><?= $errors["username"] ?></p>
            <?php endif; ?>
            <div>
                <label for="firstname">First name</label>
                <input type="text" name="first_name" placeholder="firstname" value="<?= htmlspecialchars($user["first_name"]) ?>" />
            </div>
            <?php if (isset($errors["first_name"])): ?>
                <p class="error"><?= $errors["first_name"] ?></p>
            <?php endif; ?>
            <div>
                <label for="lastname">Last name</label>
                <input type="text" name="last_name" placeholder="lastname" value="<?= htmlspecialchars($user["last_name"]) ?>" />
            </div>
            <?php if (isset($errors["last_name"])): ?>
                <p class="error"><?= $errors["last_name"] ?></p>
            <?php endif; ?>
            <div>
                <label for="email">Email</label>
                <input type="text" name="email" placeholder="email" value="<?= htmlspecialchars($user["email"]) ?>" />
            </div>
            <?php if (isset($errors["email"])): ?>
                <p class="error"><?= $errors["email"] ?></p>
            <?php endif; ?>
            <div>
                <input type="submit" value="Add" />
                <a href="/users">Cancel</a>
            </div>
        </form>
    </div>