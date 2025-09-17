<div class="content" style="margin-top: 70px;">
    <ul class="tree-list">
        <?php if (count($categories) > 0): ?>
            <?php foreach ($categories as $category): ?>
                <li class="tree-item" data-depth="<?= $category['depth'] ?>" data-id="<?= $category['id'] ?>">
                    <span class="expand-toggle <?= intval($category["rgt"]) !== intval($category["lft"]) + 1 ? 'expanded' : 'no-children' ?>">
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
    const deleteForms = document.querySelectorAll(".deleteForm");
    for (let i = 0; i < deleteForms.length; i++) {
        deleteForms[i].addEventListener("submit", (e) => {
            if (!confirm("Are you sure you want to delete this category?")) {
                e.preventDefault();
            }
        });
    }
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('expand-toggle') && !e.target.classList.contains('no-children')) {
            const clickedItem = e.target.closest('.tree-item');
            const clickedDepth = parseInt(clickedItem.getAttribute('data-depth'));
            const clickedId = clickedItem.getAttribute('data-id');
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
        }
    });

    // Search functionality
    const searchBox = document.getElementById('searchBox');
    searchBox.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const treeItems = document.querySelectorAll('.tree-item');

        if (searchTerm === '') {
            // Reset to normal state
            treeItems.forEach(item => {
                item.classList.remove('hidden');
            });
        } else {
            treeItems.forEach(item => {
                const categoryName = item.querySelector('.category-name').textContent.toLowerCase();

                if (categoryName.includes(searchTerm)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }
    });

    // Initialize: Start with root items expanded and others visible
    document.addEventListener('DOMContentLoaded', function() {
        const allItems = document.querySelectorAll('.tree-item');
        allItems.forEach(item => {
            item.classList.remove('hidden');
        });
    });
</script>