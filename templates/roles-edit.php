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
</div>
