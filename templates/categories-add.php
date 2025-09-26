<div class="content">
    <form class="edit" method="POST" action="/categories/add">
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" placeholder="name" value="<?= $category["name"] ?? "" ?>" />
        </div>
        <?php if (isset($errors["name"])): ?>
            <p class="error"><?= $errors["name"] ?></p>
        <?php endif; ?>
        <div>
            <label for="parent">Parent category</label>
            <?php if (count($categories) > 0): ?>
                <select id="parent" name="parent_id" class="input">
                    <option value="0">No parent</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat["id"] ?>" <?= $cat["id"] == $category["parent_id"] ? "selected" : "" ?>><?= str_repeat("&nbsp;&nbsp;", $cat["depth"]) . $cat["name"] ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <select id="parent" class="input" name="parent_id">
                    <option value="0">No parent.</option>
                </select>
            <?php endif; ?>
        </div>
        <?php if (isset($errors["parent"])): ?>
            <p class="error"><?= $errors["parent"] ?></p>
        <?php endif; ?>
        <div>
            <input type="submit" value="Add" />
            <a href="/categories">Cancel</a>
        </div>
    </form>
</div>