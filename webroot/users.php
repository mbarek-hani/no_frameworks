<?php

declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/authorization.php";
require_once "../lib/database.php";
require_once "../lib/request.php";
require_once "../lib/template.php";
require_once "../lib/validate.php";
require_once "../lib/users.php";

mh_request_assert_method("GET");

$logged_in_user = mh_authentication_require_login();

mh_authorization_assert_authorized("ListUsers");

function mh_render_users(
    array $users,
    string $url,
    string $search_query,
    int $total_pages,
    int $current_page,
    int $size,
) {
    require "../templates/users.php";
}

$page = mh_request_get_int_parameter("page", INPUT_GET, 1, PHP_INT_MAX, 1);
$size = mh_request_get_int_parameter("size", INPUT_GET, 5, 30, 10);
$search = mh_validate_search_query_parameter("q");

$pdo = mh_database_get_connection();

$total_users = mh_users_count($pdo, $search);

$total_pages = intval(ceil($total_users / $size));
if ($total_pages > 0 && $page > $total_pages) {
    mh_request_terminate(400);
}

$offset = ($page - 1) * $size;
$limit = $size;

$users = mh_template_escape_array_of_arrays(
    mh_users_get_all($pdo, $search, $offset, $limit)
);

mh_template_render_header("Users");
mh_template_render_sidebar("/users");
mh_render_users($users, "/users", $search, $total_pages, $page, $size);
mh_template_render_footer();
