<div class="login">
    <form class="login-form" method="POST" action="/login">
        <h3>Sign in</h3>
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="username" value="<?= $credentials[
                "username"
            ] ?>"/>
        </div>
        <?php if (isset($errors["username"])): ?>
            <p class="error"><?= $errors["username"] ?></p>
        <?php endif; ?>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="password" value="<?= $credentials[
                "password"
            ] ?>"/>
        </div>
        <?php if (isset($errors["password"])): ?>
            <p class="error"><?= $errors["password"] ?></p>
        <?php endif; ?>
        <div>
            <input type="submit" value="Login" />
        </div>
    </form>
</div>
