<div class="content">
    <?php if (count($categories) > 0): ?>
        <ul>
            <?php foreach ($categories as $category): ?>
                <li>
                    <?= str_repeat("&nbsp;", intval($category["depth"]) * 4) . $category["name"] ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>