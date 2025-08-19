<?php
declare(strict_types=1);

require "../lib/errors.php";
require "../lib/request.php";
require "../lib/database.php";
require "../lib/validate.php";
require "../lib/template.php";

mh_request_assert_methods(["GET, POST"]);

function mh_render_users_edit(
    array $user,
    array $user_roles,
    array $other_roles,
    array $errors,
): void {
    require "../templates/users-edit.php";
}

$id = mh_request_get_int_query_parameter("id", 1, PHP_INT_MAX);

$pdo = mh_database_get_connection();

if (!mh_database_does_row_exist($pdo, "users", $id)) {
    http_response_code(404);
    mh_template_render_404();
    die();
}

$user = null;
$errors = [
    "username" => null,
    "first_name" => null,
    "last_name" => null,
    "email" => null,
];
$edited_user = null;
$user_roles = [];
$other_roles = [];

if (mh_request_is_method("GET")) {
    $statement = $pdo->prepare("select * from users where id = :id");
    $statement->bindValue(":id", $id, PDO::PARAM_INT);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    $statement = $pdo->prepare(
        "select id, name, description from roles, users_roles where role_id = id and user_id = :id",
    );
    $statement->bindValue(":id", $id, PDO::PARAM_INT);
    $statement->execute();
    $user_roles = mh_template_escape_array_of_arrays(
        $statement->fetchAll(PDO::FETCH_ASSOC),
    );

    $statement = $pdo->prepare(
        "select id, name, description from roles where id not in (select role_id from users_roles where user_id = :id)",
    );
    $statement->bindValue(":id", $id, PDO::PARAM_INT);
    $statement->execute();
    $other_roles = mh_template_escape_array_of_arrays(
        $statement->fetchAll(PDO::FETCH_ASSOC),
    );
} else {
    $edited_user = [
        "id" => $id,
        "username" => trim($_POST["username"] ?? ""),
        "first_name" => trim($_POST["first_name"] ?? ""),
        "last_name" => trim($_POST["last_name"] ?? ""),
        "email" => trim($_POST["email"] ?? ""),
    ];

    $errors["username"] = mh_validate_username(
        $edited_user["username"],
        "username",
    );
    $errors["first_name"] = mh_validate_name(
        $edited_user["first_name"],
        "first name",
    );
    $errors["last_name"] = mh_validate_name(
        $edited_user["last_name"],
        "last name",
    );
    $errors["email"] = mh_validate_email($edited_user["email"], "email");

    if (
        $errors["username"] === null &&
        mh_database_does_username_exist($pdo, $edited_user["username"], $id)
    ) {
        $errors["username"] = "username already in use";
    }

    if (
        $errors["email"] === null &&
        mh_database_does_email_exist($pdo, $edited_user["email"], $id)
    ) {
        $errors["email"] = "email already in use";
    }

    if (
        $errors["first_name"] === null &&
        $errors["last_name"] === null &&
        mh_database_does_name_exist(
            $pdo,
            $edited_user["first_name"],
            $edited_user["last_name"],
            $id,
        )
    ) {
        $errors["first_name"] = "first name and last name already in use";
    }

    if (mh_errors_is_empty($errors)) {
        $statement = $pdo->prepare(
            "update users set username=:username, first_name=:first_name, last_name=:last_name, email=:email where id=:id",
        );
        $statement->bindValue(
            ":username",
            $edited_user["username"],
            PDO::PARAM_STR,
        );
        $statement->bindValue(
            ":first_name",
            $edited_user["first_name"],
            PDO::PARAM_STR,
        );
        $statement->bindValue(
            ":last_name",
            $edited_user["last_name"],
            PDO::PARAM_STR,
        );
        $statement->bindValue(":email", $edited_user["email"], PDO::PARAM_STR);
        $statement->bindValue(":id", $edited_user["id"], PDO::PARAM_INT);
        $statement->execute();

        mh_request_redirect("/users");
    }
}

$data = mh_template_escape_array($user ?? $edited_user);

mh_template_render_header("Edit user");
mh_template_render_sidebar("/users/edit");
mh_render_users_edit($data, $user_roles, $other_roles, $errors);
mh_template_render_footer();
