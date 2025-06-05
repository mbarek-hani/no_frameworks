<?php
declare(strict_types=1);

function mh_template_escape_array_of_arrays(array $data): array {
    return array_map(fn($item) => array_map("htmlspecialchars", $item), $data);
}

function mh_template_escape_array(array $data):array {
    return array_map(fn($item) => htmlspecialchars((string)$item), $data);
}

function mh_template_render_pager(string $url,string $search_query, int $total_pages, int $size, int $current_page): void {
    if ($total_pages > 1) {
        require "../templates/pager.php";
    }
}

function mh_template_render_header(string $title):void {
    require "../templates/header.php";
}

function mh_template_render_sidebar():void {
    require "../templates/sidebar.php";
}

function mh_template_render_footer():void {
    require "../templates/footer.php";
}
