<?php
declare(strict_types=1);

/*
 * use htmlspecialchars on every value in the inner arrays of the array $data
 * @param $data array of associative arrays with data to sanitize
 * @return array array of associative arrays with data sanitized
 */
function mh_template_escape_array_of_arrays(array $data): array
{
    return array_map(fn($item) => array_map("htmlspecialchars", $item), $data);
}

/*
 * use htmlspecialchars on every value in the array $data
 * @param $data associative array that have values that need escaping
 * @return array associative array that have values escaped
 */
function mh_template_escape_array(array $data): array
{
    return array_map(fn($item) => htmlspecialchars((string) $item), $data);
}

/*
 * render the pagination section
 * @param the url to use
 * @param $search_query the value of the search GET parameter to paginate searches as well
 * @param $total_pages the number of total pages
 * @param $size the size of the data per page
 * @param $current_page the number of the current page
 */
function mh_template_render_pager(
    string $url,
    string $search_query,
    int $total_pages,
    int $size,
    int $current_page,
): void {
    if ($total_pages > 1) {
        require "../templates/pager.php";
    }
}

function mh_template_render_header(string $title): void
{
    require "../templates/header.php";
}

function mh_template_render_sidebar(string $url_path): void
{
    require "../templates/sidebar.php";
}

function mh_template_render_footer(): void
{
    require "../templates/footer.php";
}

function mh_template_render_404(): void
{
    require "../templates/404.php";
}
