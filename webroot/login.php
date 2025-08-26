<?php
declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/database.php";
require_once "../lib/request.php";
require_once "../lib/validate.php";
require_once "../lib/template.php";

function mh_render_login(array $credentials, array $errors): void
{
    require_once "../templates/login.php";
}

mh_request_assert_methods(["GET", "POST"]);

mh_authentication_require_logout();

$pdo = mh_database_get_connection();

$credentials = [
    "username" => "",
    "password" => "",
];

$errors = [
    "username" => null,
    "password" => null,
];

if (mh_request_is_method("POST")) {
    $credentials["username"] = trim($_POST["username"] ?? "");
    $credentials["password"] = trim($_POST["password"] ?? "");

    $errors["username"] = mh_validate_login_username(
        $credentials["username"],
        "username",
    );
    $errors["password"] = mh_validate_login_password(
        $credentials["password"],
        "password",
    );

    if (mh_errors_is_empty($errors)) {
        $statement = $pdo->prepare(
            "select * from users where username=:username",
        );
        $statement->bindValue(
            ":username",
            $credentials["username"],
            PDO::PARAM_STR,
        );
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        if (
            $user === false ||
            !password_verify($credentials["password"], $user["password"])
        ) {
            $errors["username"] = "Invalid credentials";
        } else {
            $_SESSION["user_id"] = $user["id"];
            mh_request_redirect("/users");
        }
    }
}

$credentials = mh_template_escape_array($credentials);

mh_template_render_header("Login");
mh_render_login(mh_template_escape_array($credentials), $errors);
mh_template_render_footer();
