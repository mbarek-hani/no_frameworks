<?php

declare(strict_types=1);

require "../lib/errors.php";
require "../lib/request.php";
require "../lib/database.php";
require "../lib/validate.php";
require "../lib/template.php";

mh_request_assert_methods(["GET, POST"]);

function mh_render_users_add(array $user, array $errors): void
{
    require "../templates/users-add.php";
}

$pdo = mh_database_get_connection();

$errors = [
    "username" => null,
    "first_name" => null,
    "last_name" => null,
    "email" => null,
];
$added_user = [
    "username" => null,
    "first_name" => null,
    "last_name" => null,
    "email" => null,
];

if (mh_request_is_method("POST")) {
    $added_user = [
        "username" => trim($_POST["username"] ?? ""),
        "first_name" => trim($_POST["first_name"] ?? ""),
        "last_name" => trim($_POST["last_name"] ?? ""),
        "email" => trim($_POST["email"] ?? ""),
    ];

    $errors["username"] = mh_validate_username(
        $added_user["username"],
        "username",
    );
    $errors["first_name"] = mh_validate_name(
        $added_user["first_name"],
        "first name",
    );
    $errors["last_name"] = mh_validate_name(
        $added_user["last_name"],
        "last name",
    );
    $errors["email"] = mh_validate_email($added_user["email"], "email");

    if (
        $errors["username"] === null &&
        mh_database_does_username_exist($pdo, $added_user["username"])
    ) {
        $errors["username"] = "username already in use";
    }

    if (
        $errors["email"] === null &&
        mh_database_does_email_exist($pdo, $added_user["email"])
    ) {
        $errors["email"] = "email already in use";
    }

    if (
        $errors["first_name"] === null &&
        $errors["last_name"] === null &&
        mh_database_does_name_exist(
            $pdo,
            $added_user["first_name"],
            $added_user["last_name"],
        )
    ) {
        $errors["first_name"] = "first name and last name are already in use";
    }

    if (mh_errors_is_empty($errors)) {
        $statement = $pdo->prepare(
            "insert into users(username, first_name, last_name, email) values (:username, :first_name, :last_name, :email)",
        );
        $statement->bindValue(
            ":username",
            $added_user["username"],
            PDO::PARAM_STR,
        );
        $statement->bindValue(
            ":first_name",
            $added_user["first_name"],
            PDO::PARAM_STR,
        );
        $statement->bindValue(
            ":last_name",
            $added_user["last_name"],
            PDO::PARAM_STR,
        );
        $statement->bindValue(":email", $added_user["email"], PDO::PARAM_STR);
        $statement->execute();

        mh_request_redirect("/users");
    }
}

$data = mh_template_escape_array($added_user);

mh_template_render_header("Add user");
mh_template_render_sidebar("/users/add");
mh_render_users_add($data, $errors);
mh_template_render_footer();
