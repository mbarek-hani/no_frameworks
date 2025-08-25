<?php
declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/database.php";
require_once "../lib/request.php";
require_once "../lib/template.php";

mh_request_assert_method("POST");

$logged_in_user = mh_authentication_require_login();

$id = mh_request_get_int_parameter("id", INPUT_GET, 1, PHP_INT_MAX);

$pdo = mh_database_get_connection();

if (!mh_database_does_row_exist($pdo, "roles", $id)) {
    http_response_code(400);
    mh_template_render_404();
    die();
}

$statement = $pdo->prepare("delete from roles where id=:id");
$statement->bindValue(":id", $id);
$statement->execute();

mh_request_redirect("/roles");
