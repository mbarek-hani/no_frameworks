<?php
declare(strict_types=1);

require "../lib/errors.php";
require "../lib/database.php";
require "../lib/request.php";

mh_request_assert_method("GET");

$id = mh_request_get_int_query_parameter("id", 1, PHP_INT_MAX);

$pdo = mh_database_get_connection();

if (!mh_database_does_user_exist($pdo, $id)) {
    mh_request_terminate(400);
    exit(1);
}

$statement = $pdo->prepare("delete from users where id=:id");
$statement->bindValue(":id", $id);
$statement->execute();

mh_request_redirect("/users");
