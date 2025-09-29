<?php

declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/request.php";
require_once "../lib/database.php";
require_once "../lib/validate.php";
require_once "../lib/template.php";

mh_request_assert_method("POST");

$logged_in_user = mh_authentication_require_login();

$category_id = mh_request_get_int_parameter("id", INPUT_GET, 1, PHP_INT_MAX);

$pdo = mh_database_get_connection();

if (!mh_database_does_row_exist($pdo, "categories", $category_id)) {
    http_response_code(404);
    mh_template_render_404();
    die();
}

$statement = $pdo->prepare("select * from categories where id = :id");
$statement->bindValue(":id", $category_id, pdo::PARAM_INT);
$statement->execute();
$category = $statement->fetch(pdo::FETCH_ASSOC);

if (intval($category["rgt"]) !== intval($category["lft"]) + 1) {
    http_response_code(400);
    die();
}

$pdo->beginTransaction();

$statement = $pdo->prepare("delete from categories where id = :id");
$statement->bindValue(":id", $category_id, pdo::PARAM_INT);
$statement->execute();

$statement = $pdo->prepare("update categories set lft = lft - 2 where lft > :left");
$statement->bindValue(":left", $category["lft"], pdo::PARAM_INT);
$statement->execute();

$statement = $pdo->prepare("update categories set rgt = rgt - 2 where rgt > :left");
$statement->bindValue(":left", $category["lft"], pdo::PARAM_INT);
$statement->execute();

$pdo->commit();

mh_request_redirect("/categories");
