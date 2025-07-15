<?php
declare(strict_types=1);

require "../lib/errors.php";
require "../lib/database.php";
require "../lib/request.php";
require "../lib/template.php";
require "../lib/validate.php";

mh_request_assert_method("GET");

function mh_render_users(array $users,string $url,string $search_query, int $total_pages, int $current_page, int $size) {
    require "../templates/users.php";
}

$page = mh_request_get_int_query_parameter("page", 1, PHP_INT_MAX, 1);
$size = mh_request_get_int_query_parameter("size", 5, 30, 10);
$search = mh_validate_search_query_parameter("q");

$pdo = mh_database_get_connection();

$statement = $pdo->prepare("select count(*) from users where username like :search");
$statement->bindValue(":search", "%" . $search . "%");
$statement->execute();
$total_users = $statement->fetchColumn(0);


$total_pages = intval(ceil($total_users / $size));
if ($total_pages > 0 && $page > $total_pages) {
    mh_request_terminate(400);
}

$statement = $pdo->prepare("select * from users where username like :search limit :offset, :limit");
$statement->bindValue(":offset", ($page - 1) * $size, PDO::PARAM_INT);
$statement->bindValue(":limit", $size, PDO::PARAM_INT);
$statement->bindValue(":search", "%" . $search . "%", PDO::PARAM_STR);
$statement->execute();

$users = mh_template_escape_array_of_arrays($statement->fetchAll(PDO::FETCH_ASSOC));

mh_template_render_header("Users");
mh_template_render_sidebar();
mh_render_users($users, "/users", $search, $total_pages, $page, $size);
mh_template_render_footer();
