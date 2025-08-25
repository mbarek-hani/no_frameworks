<?php
declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/request.php";
require_once "../lib/database.php";
require_once "../lib/validate.php";
require_once "../lib/template.php";

mh_request_assert_methods(["GET", "POST"]);

$logged_in_user = mh_authentication_require_login();

function mh_render_roles_edit(
    array $role,
    array $role_actions,
    array $other_actions,
    array $errors,
): void {
    require "../templates/roles-edit.php";
}

$role_id = mh_request_get_int_parameter("id", INPUT_GET, 1, PHP_INT_MAX);

$pdo = mh_database_get_connection();

if (!mh_database_does_row_exist($pdo, "roles", $role_id)) {
    http_response_code(404);
    mh_template_render_404();
    die();
}

$role = null;
$errors = [
    "name" => null,
    "description" => null,
];
$edited_role = null;
$role_actions = [];
$other_actions = [];

if (mh_request_is_method("GET")) {
    $statement = $pdo->prepare("select * from roles where id = :id");
    $statement->bindValue(":id", $role_id, PDO::PARAM_INT);
    $statement->execute();
    $role = $statement->fetch(PDO::FETCH_ASSOC);

    $statement = $pdo->prepare(
        "select id, name, description from actions, roles_actions where action_id = id and role_id = :id",
    );
    $statement->bindValue(":id", $role_id, PDO::PARAM_INT);
    $statement->execute();
    $role_actions = mh_template_escape_array_of_arrays(
        $statement->fetchAll(PDO::FETCH_ASSOC),
    );

    $statement = $pdo->prepare(
        "select id, name, description from actions where id not in (select action_id from roles_actions where role_id = :id)",
    );
    $statement->bindValue(":id", $role_id, PDO::PARAM_INT);
    $statement->execute();
    $other_actions = mh_template_escape_array_of_arrays(
        $statement->fetchAll(PDO::FETCH_ASSOC),
    );
} else {
    $action_id = mh_request_get_int_parameter(
        "action",
        INPUT_POST,
        1,
        PHP_INT_MAX,
        0,
    );
    if ($action_id > 0) {
        if (!mh_database_does_row_exist($pdo, "actions", $action_id)) {
            http_response_code(404);
            mh_template_render_404();
            die();
        }
        $action = $_POST["do"] ?? "";
        switch ($action) {
            case "add":
                $statement = $pdo->prepare(
                    "insert into roles_actions values(:role_id, :action_id)",
                );
                $statement->bindValue(":role_id", $role_id, PDO::PARAM_INT);
                $statement->bindValue(":action_id", $action_id, PDO::PARAM_INT);
                $statement->execute();
                mh_request_redirect("/roles/edit/$role_id");
                break;
            case "remove":
                $statement = $pdo->prepare(
                    "delete from roles_actions where role_id=:role_id and action_id=:action_id",
                );
                $statement->bindValue(":role_id", $role_id, PDO::PARAM_INT);
                $statement->bindValue(":action_id", $action_id, PDO::PARAM_INT);
                $statement->execute();
                mh_request_redirect("/roles/edit/$role_id");
                break;
            default:
                mh_request_terminate(400);
        }
    } else {
        $edited_role = [
            "id" => $role_id,
            "name" => trim($_POST["name"] ?? ""),
            "description" => trim($_POST["description"] ?? ""),
        ];

        $errors["name"] = mh_validate_role_name($edited_role["name"], "name");
        $errors["description"] = mh_validate_role_description(
            $edited_role["description"],
            "description",
        );

        if (
            $errors["name"] === null &&
            !mh_database_is_unique_column_value(
                $pdo,
                "roles",
                "name",
                $edited_role["name"],
                $role_id,
            )
        ) {
            $errors["name"] = "name already in use";
        }

        if (mh_errors_is_empty($errors)) {
            $statement = $pdo->prepare(
                "update roles set name=:name, description=:description where id=:id",
            );
            $statement->bindValue(":id", $role_id, PDO::PARAM_STR);
            $statement->bindValue(
                ":name",
                $edited_role["name"],
                PDO::PARAM_STR,
            );
            $statement->bindValue(
                ":description",
                $edited_role["description"],
                PDO::PARAM_STR,
            );
            $statement->execute();
            mh_request_redirect("/roles");
        }
    }
}

$data = mh_template_escape_array($role ?? $edited_role);

mh_template_render_header("Edit role");
mh_template_render_sidebar("/roles/edit");
mh_render_roles_edit($data, $role_actions, $other_actions, $errors);
mh_template_render_footer();
