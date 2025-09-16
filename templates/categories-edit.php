<div class="content">
    <form class="edit" method="POST" action="/categories/edit/<?= $category["id"] ?>">
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" placeholder="name" value="<?= $category["name"] ?>" />
        </div>
        <?php if (isset($errors["name"])): ?>
            <p class="error"><?= $errors["name"] ?></p>
        <?php endif; ?>
        <div>
            <input type="submit" value="Edit" />
            <a href="/categories">Cancel</a>
        </div>
    </form>
</div>