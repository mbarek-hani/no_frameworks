<?php
declare(strict_types=1);

require "../lib/errors.php";
require "../lib/database.php";
require "../lib/request.php";
require "../lib/template.php";

mh_request_assert_method("POST");

$id = mh_request_get_int_parameter("id", INPUT_GET, 1, PHP_INT_MAX);

$pdo = mh_database_get_connection();

if (!mh_database_does_row_exist($pdo, "users", $id)) {
    http_response_code(400);
    mh_template_render_404();
    die();
}

$statement = $pdo->prepare("delete from users where id=:id");
$statement->bindValue(":id", $id);
$statement->execute();

mh_request_redirect("/users");
