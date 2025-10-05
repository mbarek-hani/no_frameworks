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
            <label for="parent">Parent category</label>
            <?php if ($category["lft"] == 1): ?>
                <select disabled class="input">
                    <option value="0" selected>No parent</option>
                </select>
                <input type="hidden" name="parent_id" value="0">
            <?php elseif (count($categories) > 0): ?>
                <select id="parent" name="parent_id" class="input" disabled>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat["id"] ?>" <?= $cat["id"] == $parent_category["id"] ? "selected" : "" ?>><?= str_repeat("&nbsp;&nbsp;", $cat["depth"]) . $cat["name"] ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <select disabled class="input">
                    <option>No categories found.</option>
                </select>
            <?php endif; ?>
        </div>
        <div>
            <input type="submit" value="Edit" />
            <a href="/categories">Cancel</a>
        </div>
    </form>
</div>