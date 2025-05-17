<ul>
    <?php if ($current_page > 1) : ?>
        <li>
            <a href="<?= $url ?>?<?php echo !empty($search_query) ? 'q=' . $search_query . '&' : '' ?>page=<?= $current_page - 1 ?>&size=<?= $size ?>">&laquo;</a>
        </li>
    <?php endif; ?>

    <?php for($i = 1; $i <= $total_pages; $i++): ?>
        <li>
            <a href="<?= $url ?>?<?php echo !empty($search_query) ? 'q=' . $search_query . '&' : '' ?>page=<?= $i ?>&size=<?= $size ?>"<?php echo $i === $current_page ? " class='active'" : "" ?>><?= $i ?></a>
        </li>
    <?php endfor; ?>
    <?php if ($current_page < $total_pages ) : ?>
        <li>
            <a href="<?= $url ?>?<?php echo !empty($search_query) ? 'q=' . $search_query . '&' : '' ?>page=<?= $current_page + 1 ?>&size=<?= $size ?>">&raquo;</a>
        </li>
    <?php endif; ?>

</ul>

