<?php
declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/authorization.php";
require_once "../lib/request.php";
require_once "../lib/database.php";
require_once "../lib/validate.php";
require_once "../lib/template.php";

mh_request_assert_methods(["GET", "POST"]);

$logged_in_user = mh_authentication_require_login();

mh_authorization_assert_authorized_any(["ReadAction", "UpdateAction"]);

function mh_render_actions_edit(array $action, array $errors): void
{
    require "../templates/actions-edit.php";
}

$id = mh_request_get_int_parameter("id", INPUT_GET, 1, PHP_INT_MAX);

$pdo = mh_database_get_connection();

if (!mh_database_does_row_exist($pdo, "actions", $id)) {
    http_response_code(404);
    mh_template_render_404();
    die();
}

$action = null;
$errors = [
    "name" => null,
    "description" => null,
];
$edited_action = null;

if (mh_request_is_method("GET")) {
    mh_authorization_assert_authorized("ReadAction");
    $statement = $pdo->prepare("select * from actions where id = :id");
    $statement->bindValue(":id", $id, PDO::PARAM_INT);
    $statement->execute();
    $action = $statement->fetch(PDO::FETCH_ASSOC);
} else {
    mh_authorization_assert_authorized("UpdateAction");
    $edited_action = [
        "id" => $id,
        "name" => trim($_POST["name"] ?? ""),
        "description" => trim($_POST["description"] ?? ""),
    ];

    $errors["name"] = mh_validate_action_name($edited_action["name"], "name");
    $errors["description"] = mh_validate_action_description(
        $edited_action["description"],
        "description",
    );

    if (
        $errors["name"] === null &&
        !mh_database_is_unique_column_value(
            $pdo,
            "actions",
            "name",
            $edited_action["name"],
            $id,
        )
    ) {
        $errors["name"] = "name already in use";
    }

    if (mh_errors_is_empty($errors)) {
        $statement = $pdo->prepare(
            "update actions set name=:name, description=:description where id=:id",
        );
        $statement->bindValue(":id", $id, PDO::PARAM_STR);
        $statement->bindValue(":name", $edited_action["name"], PDO::PARAM_STR);
        $statement->bindValue(
            ":description",
            $edited_action["description"],
            PDO::PARAM_STR,
        );
        $statement->execute();
        mh_request_redirect("/actions");
    }
}

$data = mh_template_escape_array($action ?? $edited_action);

mh_template_render_header("Edit action");
mh_template_render_sidebar("/actions/edit");
mh_render_actions_edit($data, $errors);
mh_template_render_footer();
