<?php

declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/authorization.php";
require_once "../lib/database.php";
require_once "../lib/request.php";
require_once "../lib/template.php";

mh_request_assert_methods(["GET", "POST"]);

$logged_in_user = mh_authentication_require_login();

function mh_render_categories_edit(array $category): void
{
    require_once "../templates/categories-edit.php";
}

$category_id = mh_request_get_int_parameter("id", INPUT_GET, 1, PHP_INT_MAX);

$pdo = mh_database_get_connection();

if (!mh_database_does_row_exist($pdo, "categories", $category_id)) {
    http_response_code(404);
    mh_template_render_404();
    die();
}

$category = null;
$errors = [
    "name" => null,
];
$edited_category = null;

if (mh_request_is_method("GET")) {
    $statement = $pdo->prepare("select * from categories where id = :id");
    $statement->bindValue(":id", $category_id, PDO::PARAM_INT);
    $statement->execute();
    $category = $statement->fetch(PDO::FETCH_ASSOC);
} else {
    // TODO
}


$data = mh_template_escape_array($category ?? $edited_category);

mh_template_render_header("Add category");
mh_template_render_sidebar("/categories");
mh_render_categories_edit($category);
mh_template_render_footer();
