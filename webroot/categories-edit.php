<?php

declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/authorization.php";
require_once "../lib/database.php";
require_once "../lib/request.php";
require_once "../lib/validate.php";
require_once "../lib/template.php";

mh_request_assert_methods(["GET", "POST"]);

$logged_in_user = mh_authentication_require_login();

function mh_render_categories_edit(array $category, array $categories, array $errors, array $parent_category): void
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
    "parent" => null,
];
$edited_category = null;

$statement = $pdo->query("select node.id, node.name, count(parent.name) -1 as depth, node.lft, node.rgt from categories as node, categories as parent where node.lft between parent.lft and parent.rgt group by node.name order by node.lft");
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

$statement = $pdo->prepare("select parent.id, parent.name, parent.lft, parent.rgt from categories as node, categories as parent where node.lft between parent.lft and parent.rgt and node.id = :id order by parent.lft desc");
$statement->bindValue(":id", $category_id, PDO::PARAM_INT);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$parent_category = count($result) > 1 ? $result[1] : $result[0];

if (mh_request_is_method("GET")) {
    $category = array_values(array_filter($categories, fn($c) => $c["id"] === $category_id))[0];
} else {
    $category = array_values(array_filter($categories, fn($c) => $c["id"] === $category_id))[0];
    $left = $category["lft"];
    $right = $category["rgt"];
    unset($category);
    $edited_category = [
        "id" => $category_id,
        "name" => trim($_POST["name"] ?? ""),
        "lft" => $left,
        "rgt" => $right,
        "parent_id" => mh_request_get_int_parameter("parent_id", INPUT_POST, 0, PHP_INT_MAX, -1),
    ];

    $errors["name"] = mh_validate_category_name($edited_category["name"], "name");
    $errors["parent"] = $edited_category["parent_id"] === -1 ? "parent category is required" : null;

    if ($errors["name"] === null && !mh_database_is_unique_column_value($pdo, "categories", "name", $edited_category["name"], $category_id)) {
        $errors["name"] = "name already in use";
    }

    if (mh_errors_is_empty($errors)) {
        if (($edited_category["parent_id"] === 0 && $edited_category["lft"]) || ($edited_category["parent_id"] === $parent_category["id"])) {
            $statement = $pdo->prepare("update categories set name=:name where id=:id");
            $statement->bindValue(":name", $edited_category["name"], PDO::PARAM_STR);
            $statement->bindValue(":id", $edited_category["id"], PDO::PARAM_INT);
            $statement->execute();

            mh_request_redirect("/categories");
        }
    }
}


$category = mh_template_escape_array($category ?? $edited_category);
$categories = mh_template_escape_array_of_arrays($categories);

mh_template_render_header("Add category");
mh_template_render_sidebar("/categories");
mh_render_categories_edit($category, $categories,  $errors, $parent_category);
mh_template_render_footer();
