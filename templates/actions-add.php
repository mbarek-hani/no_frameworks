<div class="content">
    <form class="edit" method="POST" action="/actions/add">
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" placeholder="name" value="<?= $action[
                "name"
            ] ?>" />
        </div>
        <?php if (isset($errors["name"])): ?>
            <p class="error"><?= $errors["name"] ?></p>
        <?php endif; ?>
        <div>
            <label for="description">Description</label>
            <input type="text" name="description" placeholder="Description..." value="<?= $action[
                "description"
            ] ?>" />
        </div>
        <?php if (isset($errors["description"])): ?>
            <p class="error"><?= $errors["description"] ?></p>
        <?php endif; ?>
        <div>
            <input type="submit" value="Add" />
            <a href="/actions">Cancel</a>
        </div>
    </form>
</div>
