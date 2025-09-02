<?php $canUpdate = mh_authorization_is_authorized("UpdateAction"); ?>
<div class="content">
    <form class="edit" <?php if ($canUpdate): ?> method="POST" action="/actions/edit/<?= $action["id"] ?>" <?php endif; ?>>
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" placeholder="name" value="<?= $action["name"] ?>" <?= !$canUpdate ? "readonly" : "" ?> />
        </div>
        <?php if (isset($errors["name"])): ?>
            <p class="error"><?= $errors["name"] ?></p>
        <?php endif; ?>
        <div>
            <label for="description">Description</label>
            <input type="text" name="description" placeholder="Description..." value="<?= $action["description"] ?>" <?= !$canUpdate ? "readonly" : "" ?> />
        </div>
        <?php if (isset($errors["description"])): ?>
            <p class="error"><?= $errors["description"] ?></p>
        <?php endif; ?>
        <?php if ($canUpdate): ?>
            <div>
                <input type="submit" value="Edit" />
                <a href="/actions">Cancel</a>
            </div>
        <?php else: ?>
            <div>
                <a href="/actions">Cancel</a>
            </div>
        <?php endif; ?>
    </form>
</div>