<?php

declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/authorization.php";
require_once "../lib/request.php";
require_once "../lib/database.php";
require_once "../lib/validate.php";
require_once "../lib/template.php";
require_once "../lib/users.php";

mh_request_assert_methods(["GET", "POST"]);

$logged_in_user = mh_authentication_require_login();

mh_authorization_assert_authorized_any(["ReadUser", "UpdateUser"]);

function mh_render_users_edit(
    array $user,
    array $user_roles,
    array $other_roles,
    array $errors,
): void {
    require "../templates/users-edit.php";
}

$user_id = mh_request_get_int_parameter("id", INPUT_GET, 1, PHP_INT_MAX);

$pdo = mh_database_get_connection();

if (!mh_database_does_row_exist($pdo, "users", $user_id)) {
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
    "password" => null,
];
$edited_user = null;
$user_roles = [];
$other_roles = [];

if (mh_request_is_method("GET")) {
    mh_authorization_assert_authorized("ReadUser");

    $user = mh_users_get_by_id($pdo, $user_id);

    $user_roles = mh_template_escape_array_of_arrays(
        mh_users_get_roles($pdo, $user_id),
    );

    $statement = $pdo->prepare(
        "select id, name, description from roles where id not in (select role_id from users_roles where user_id = :id)",
    );
    $statement->bindValue(":id", $user_id, PDO::PARAM_INT);
    $statement->execute();
    $other_roles = mh_template_escape_array_of_arrays(
        $statement->fetchAll(PDO::FETCH_ASSOC),
    );
} else {
    mh_authorization_assert_authorized("UpdateUser");
    $new_role_id = mh_request_get_int_parameter(
        "role",
        INPUT_POST,
        1,
        PHP_INT_MAX,
        0,
    );
    if ($new_role_id > 0) {
        if (!mh_database_does_row_exist($pdo, "roles", $new_role_id)) {
            http_response_code(404);
            mh_template_render_404();
            die();
        }
        $action = $_POST["action"] ?? "";
        switch ($action) {
            case "add":
                $statement = $pdo->prepare(
                    "insert into users_roles values(:user_id, :role_id)",
                );
                $statement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
                $statement->bindValue(":role_id", $new_role_id, PDO::PARAM_INT);
                $statement->execute();
                mh_request_redirect("/users/edit/$user_id");
                break;
            case "remove":
                $statement = $pdo->prepare(
                    "delete from users_roles where user_id=:user_id and role_id=:role_id",
                );
                $statement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
                $statement->bindValue(":role_id", $new_role_id, PDO::PARAM_INT);
                $statement->execute();
                mh_request_redirect("/users/edit/$user_id");
                break;
            default:
                mh_request_terminate(400);
        }
    } else {
        $edited_user = [
            "id" => $user_id,
            "username" => trim($_POST["username"] ?? ""),
            "first_name" => trim($_POST["first_name"] ?? ""),
            "last_name" => trim($_POST["last_name"] ?? ""),
            "email" => trim($_POST["email"] ?? ""),
            "password" => $_POST["password"] ?? "",
            "password_confirm" => $_POST["password_confirm"] ?? "",
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

        $no_password = empty($edited_user["password"]);
        if (!$no_password) {
            $errors["password"] = mh_validate_password($edited_user["password"], $edited_user["password_confirm"], "password");
        }

        if (
            $errors["username"] === null &&
            !mh_database_is_unique_column_value(
                $pdo,
                "users",
                "username",
                $edited_user["username"],
                $user_id,
            )
        ) {
            $errors["username"] = "username already in use";
        }

        if (
            $errors["email"] === null &&
            !mh_database_is_unique_column_value(
                $pdo,
                "users",
                "email",
                $edited_user["email"],
                $user_id,
            )
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
                $user_id,
            )
        ) {
            $errors["first_name"] = "first name and last name already in use";
        }

        if (mh_errors_is_empty($errors)) {
            if (!$no_password) {
                $statement = $pdo->prepare(
                    "update users set username=:username, first_name=:first_name, last_name=:last_name, email=:email, password=:password where id=:id"
                );
                $password_hash = password_hash($edited_user["password"], PASSWORD_DEFAULT);
                $statement->bindValue(":password", $password_hash, Pdo::PARAM_STR);
            } else {
                $statement = $pdo->prepare(
                    "update users set username=:username, first_name=:first_name, last_name=:last_name, email=:email where id=:id",
                );
            }
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
            $statement->bindValue(
                ":email",
                $edited_user["email"],
                PDO::PARAM_STR,
            );
            $statement->bindValue(":id", $edited_user["id"], PDO::PARAM_INT);
            $statement->execute();

            mh_request_redirect("/users");
        }
    }
}

$data = mh_template_escape_array($user ?? $edited_user);

mh_template_render_header("Edit user");
mh_template_render_sidebar("/users/edit");
mh_render_users_edit($data, $user_roles, $other_roles, $errors);
mh_template_render_footer();
