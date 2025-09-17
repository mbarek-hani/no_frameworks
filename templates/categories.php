<div class="content" style="margin-top: 70px;">
    <ul class="tree-list">
        <?php if (count($categories) > 0): ?>
            <?php foreach ($categories as $category): ?>
                <li class="tree-item hidden" data-depth="<?= $category['depth'] ?>">
                    <span class="expand-toggle <?= intval($category["rgt"]) !== intval($category["lft"]) + 1 ? '' : 'no-children' ?>">
                        <?= intval($category["rgt"]) !== intval($category["lft"]) + 1 ? '▶' : '' ?>
                    </span>
                    <span class="category-name"><?= $category['name'] ?></span>
                    <div class="actions">
                        <a href="/categories/edit/<?= $category['id'] ?>">
                            <img src="assets/edit.svg" width="30" height="30" />
                        </a>
                        <form class="deleteForm" action="/categories/delete/<?= $category['id'] ?>" method="POST" style="display: inline;">
                            <button type="submit">
                                <img src="assets/delete.svg" width="30" height="30" />
                            </button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No categories found.</li>
        <?php endif; ?>
    </ul>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add expantion of the tree items logic
        document.querySelectorAll('.expand-toggle').forEach(elem => {
            if (elem.classList.contains('no-children')) {
                return;
            }
            elem.addEventListener('click', (e) => {
                const clickedItem = e.target.closest('.tree-item');
                const clickedDepth = parseInt(clickedItem.getAttribute('data-depth'));
                const allItems = document.querySelectorAll('.tree-item');
                const toggle = e.target;

                const isExpanded = toggle.classList.contains('expanded');

                // Find all children of this item
                let childItems = [];
                let foundCurrent = false;

                for (let i = 0; i < allItems.length; i++) {
                    const item = allItems[i];
                    const itemDepth = parseInt(item.getAttribute('data-depth'));

                    if (item === clickedItem) {
                        foundCurrent = true;
                        continue;
                    }

                    if (foundCurrent) {
                        if (itemDepth > clickedDepth) {
                            childItems.push(item);
                        } else {
                            break; // We've reached a sibling or parent, stop
                        }
                    }
                }

                if (isExpanded) {
                    // Collapse: hide children
                    toggle.classList.remove('expanded');
                    childItems.forEach(child => {
                        child.classList.add('hidden');
                        // Also collapse any expanded children
                        const childToggle = child.querySelector('.expand-toggle');
                        if (childToggle) {
                            childToggle.classList.remove('expanded');
                        }
                    });
                } else {
                    // Expand: show direct children only
                    toggle.classList.add('expanded');
                    childItems.forEach(child => {
                        const childDepth = parseInt(child.getAttribute('data-depth'));
                        if (childDepth === clickedDepth + 1) {
                            // Only show direct children
                            child.classList.remove('hidden');
                        }
                    });
                }
            });
        });
        // Initialize: Start with root items not expanded and others invisible
        document.querySelectorAll('.tree-item[data-depth="0"]').forEach(root => {
            root.classList.remove("hidden");
        });
        // Add confirmation dialog before deleting any category
        document.querySelectorAll(".deleteForm").forEach(form => {
            form.addEventListener("submit", (e) => {
                if (!confirm("Are you sure you want to delete this category?")) {
                    e.preventDefault();
                }
            });
        });
    });
</script>