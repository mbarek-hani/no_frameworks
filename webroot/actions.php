<?php

declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/authorization.php";
require_once "../lib/database.php";
require_once "../lib/request.php";
require_once "../lib/template.php";
require_once "../lib/validate.php";

mh_request_assert_method("GET");

$logged_in_user = mh_authentication_require_login();

mh_authorization_assert_authorized("ListActions");

function mh_render_actions(
    array $actions,
    string $url,
    string $search_query,
    int $total_pages,
    int $current_page,
    int $size,
) {
    require "../templates/actions.php";
}

$page = mh_request_get_int_parameter("page", INPUT_GET, 1, PHP_INT_MAX, 1);
$size = mh_request_get_int_parameter("size", INPUT_GET, 5, 30, 10);
$search = mh_validate_search_query_parameter("q");

$pdo = mh_database_get_connection();

$statement = $pdo->prepare(
    "select count(*) from actions where name like :search",
);
$statement->bindValue(":search", "%$search%");
$statement->execute();
$total_actions = $statement->fetchColumn(0);

$total_pages = intval(ceil($total_actions / $size));
if ($total_pages > 0 && $page > $total_pages) {
    mh_request_terminate(400);
}

$statement = $pdo->prepare(
    "select * from actions where name like :search limit :offset, :limit",
);
$statement->bindValue(":offset", ($page - 1) * $size, PDO::PARAM_INT);
$statement->bindValue(":limit", $size, PDO::PARAM_INT);
$statement->bindValue(":search", "%$search%", PDO::PARAM_STR);
$statement->execute();

$actions = mh_template_escape_array_of_arrays(
    $statement->fetchAll(PDO::FETCH_ASSOC),
);

mh_template_render_header("Actions");
mh_template_render_sidebar("/actions");
mh_render_actions($actions, "/actions", $search, $total_pages, $page, $size);
mh_template_render_footer();
