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

function mh_render_categories_add(array $category, array $categories, array $errors): void
{
    require_once "../templates/categories-add.php";
}

$pdo = mh_database_get_connection();

$added_category = [
    "name" => null,
    "parent_id" => null,
];
$errors = [
    "name" => null,
    "parent" => null,
];

$statement = $pdo->query("select node.id, node.name, count(parent.name) -1 as depth, node.lft, node.rgt from categories as node, categories as parent where node.lft between parent.lft and parent.rgt group by node.name order by node.lft");
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

if (mh_request_is_method("POST")) {
    $added_category = [
        "name" => trim($_POST["name"] ?? ""),
        "parent_id" => mh_request_get_int_parameter("parent_id", INPUT_POST, 0, PHP_INT_MAX),
    ];

    $errors["name"] = mh_validate_category_name($added_category["name"], "name");
    $errors["parent"] = $added_category["parent_id"] === -1 ? "parent category is required" : null;

    if ($errors["name"] === null && !mh_database_is_unique_column_value($pdo, "categories", "name", $added_category["name"])) {
        $errors["name"] = "name already in use";
    }

    $parent_category = array_values(array_filter($categories, fn($cat) => $cat["id"] == $added_category["parent_id"]));
    $parent_category = count($parent_category) === 1 ? $parent_category[0] : null;

    if ($parent_category === null && $added_category["parent_id"] != 0) {
        $errors["parent"] = "invalid parent category";
    }

    if (mh_errors_is_empty($errors)) {
        $pdo->beginTransaction();
        if ($added_category["parent_id"] === 0) {
            // inserting a root node
            $max_right = intval($pdo->query("select max(rgt) as max from categories")->fetchColumn(0));
            $statement = $pdo->prepare("insert into categories(name, lft, rgt) values(:name, :lft, :rgt)");
            $statement->bindValue(":name", $added_category["name"], PDO::PARAM_STR);
            $statement->bindValue(":lft", $max_right + 1, PDO::PARAM_INT);
            $statement->bindValue(":rgt", $max_right + 2, PDO::PARAM_INT);
            $statement->execute();
        } elseif ($parent_category["rgt"] == $parent_category["lft"] + 1) {
            // inserting as a child of a leaf node
            $statement = $pdo->prepare("update categories set lft = lft + 2 where lft > :lft");
            $statement->bindValue(":lft", $parent_category["lft"], PDO::PARAM_INT);
            $statement->execute();

            $statement = $pdo->prepare("update categories set rgt = rgt + 2 where rgt > :lft");
            $statement->bindValue(":lft", $parent_category["lft"], PDO::PARAM_INT);
            $statement->execute();

            $statement = $pdo->prepare("insert into categories (name, lft, rgt) values(:name, :lft, :rgt)");
            $statement->bindValue(":name", $added_category["name"], PDO::PARAM_STR);
            $statement->bindValue(":lft", $parent_category["lft"] + 1, pdo::PARAM_INT);
            $statement->bindValue(":rgt", $parent_category["lft"] + 2, pdo::PARAM_INT);
            $statement->execute();
        } else {

            $statement = $pdo->prepare("update categories set lft = lft + 2 where lft > :rgt");
            $statement->bindValue(":rgt", $parent_category["rgt"], PDO::PARAM_INT);
            $statement->execute();

            $statement = $pdo->prepare("update categories set rgt = rgt + 2 where rgt >= :rgt");
            $statement->bindValue(":rgt", $parent_category["rgt"], PDO::PARAM_INT);
            $statement->execute();

            $statement = $pdo->prepare("insert into categories (name, lft, rgt) values(:name, :lft, :rgt)");
            $statement->bindValue(":name", $added_category["name"], PDO::PARAM_STR);
            $statement->bindValue(":lft", $parent_category["rgt"], pdo::PARAM_INT);
            $statement->bindValue(":rgt", $parent_category["rgt"] + 1, pdo::PARAM_INT);
            $statement->execute();
        }
        $pdo->commit();
        mh_request_redirect("/categories");
    }
}


$category = mh_template_escape_array($added_category);
$categories = mh_template_escape_array_of_arrays($categories);

mh_template_render_header("Add category");
mh_template_render_sidebar("/categories");
mh_render_categories_add($category, $categories,  $errors);
mh_template_render_footer();
