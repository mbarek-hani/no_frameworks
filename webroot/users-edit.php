<?php
declare(strict_types=1);

require "../lib/errors.php";
require "../lib/request.php";
require "../lib/database.php";
require "../lib/validate.php";
require "../lib/template.php";

mh_request_assert_methods(["GET, POST"]);

function mh_render_users_edit(array $user, array $errors):void {
    require "../templates/users-edit.php";
}

$id = mh_request_get_int_query_parameter("id", 1, PHP_INT_MAX);

$pdo = mh_database_get_connection();

$user = null;
$errors = [
        "username" => "",
        "first_name" => "",
        "last_name" => "",
        "email" => "",
];
$edited_user = null;

if (mh_request_is_method("GET")) {
    $statement = $pdo->prepare("select * from users where id = :id");
    $statement->bindValue(":id", $id, PDO::PARAM_INT);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);
}else {
    $edited_user = [
            "id" => $id,
            "username" => trim($_POST["username"] ?? ""),
            "first_name" => trim($_POST["first_name"] ?? ""),
            "last_name" => trim($_POST["last_name"] ?? ""),
            "email" => trim($_POST["email"] ?? ""),
    ];

    if (empty($edited_user["username"])) {
        $errors["username"] = "username is required";
    }
    
    if (empty($edited_user["first_name"])) {
        $errors["first_name"] = "first name is required";
    }

    if (empty($edited_user["last_name"])) {
        $errors["last_name"] = "last name is required";
    }

    if (empty($edited_user["email"])) {
        $errors["email"] = "email is required";
    }

    if (empty($errors["username"]) && !mh_validate_username($edited_user["username"])) {
        $errors["username"] = "username must start with a letter with at least 4 letters or numbers up to 32";
    }

    if (empty($errors["first_name"]) && !mh_validate_name($edited_user["first_name"])) {
        $errors["first_name"] = "first name must be between 4 and 15 letters";
    }

    if (empty($errors["last_name"]) && !mh_validate_name($edited_user["last_name"])) {
        $errors["last_name"] = "last name must be between 4 and 15 letters";
    }

    if (empty($errors["email"]) && !mh_validate_email($edited_user["email"])) {
        $errors["email"] = "invalid email address";
    }

    if (empty($errors["username"]) && mh_database_does_username_exist($pdo, $edited_user["username"], $id)) {
        $errors["username"] = "username already in use";
    }

    if (empty($errors["email"]) && mh_database_does_email_exist($pdo, $edited_user["email"], $id)) {
        $errors["email"] = "email already in use";
    }
 
    if (empty($errors["first_name"]) && mh_database_does_name_exist($pdo, $edited_user["first_name"], $edited_user["last_name"], $id)) {
        $errors["first_name"] = "first name and last name already in use";
    }

    if (empty($errors["username"]) && empty($errors["first_name"]) && empty($errors["last_name"]) && empty($errors["email"])) {
        $statement = $pdo->prepare("update users set username=:username, first_name=:first_name, last_name=:last_name, email=:email where id=:id");
        $statement->bindValue(":username", $edited_user["username"], PDO::PARAM_STR);
        $statement->bindValue(":first_name", $edited_user["first_name"], PDO::PARAM_STR);
        $statement->bindValue(":last_name", $edited_user["last_name"], PDO::PARAM_STR);
        $statement->bindValue(":email", $edited_user["email"], PDO::PARAM_STR);
        $statement->bindValue(":id", $edited_user["id"], PDO::PARAM_INT);
        $statement->execute();

        mh_request_redirect("/users");
    }
}

$data = mh_template_escape_array($user ?? $edited_user);

mh_render_users_edit($data, $errors);
