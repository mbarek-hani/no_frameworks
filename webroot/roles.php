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

mh_authorization_assert_authorized("ListRoles");

function mh_render_roles(
    array $roles,
    string $url,
    string $search_query,
    int $total_pages,
    int $current_page,
    int $size,
) {
    require "../templates/roles.php";
}

function mh_roles_count(PDO $pdo, string $search_param): int
{
    $statement = $pdo->prepare(
        "select count(*) from roles where name like :search",
    );
    $statement->bindValue(":search", "%$search_param%");
    $statement->execute();
    return $statement->fetchColumn(0);
}

function mh_roles_get_all(
    PDO $pdo,
    string $search_param,
    int $offset,
    int $limit,
): array {
    $statement = $pdo->prepare(
        "select * from roles where name like :search limit :offset, :limit",
    );
    $statement->bindValue(":offset", $offset, PDO::PARAM_INT);
    $statement->bindValue(":limit", $limit, PDO::PARAM_INT);
    $statement->bindValue(":search", "%$search_param%", PDO::PARAM_STR);
    $statement->execute();
    return $statement->fetchAll(pdo::FETCH_ASSOC);
}

$page = mh_request_get_int_parameter("page", INPUT_GET, 1, PHP_INT_MAX, 1);
$size = mh_request_get_int_parameter("size", INPUT_GET, 5, 30, 10);
$search = mh_validate_search_query_parameter("q");

$pdo = mh_database_get_connection();

$total_users = mh_roles_count($pdo, $search);

$total_pages = intval(ceil($total_users / $size));
if ($total_pages > 0 && $page > $total_pages) {
    mh_request_terminate(400);
}

$offset = ($page - 1) * $size;
$limit = $size;

$roles = mh_template_escape_array_of_arrays(
    mh_roles_get_all($pdo, $search, $offset, $limit),
);

mh_template_render_header("roles");
mh_template_render_sidebar("/roles");
mh_render_roles($roles, "/roles", $search, $total_pages, $page, $size);
mh_template_render_footer();
