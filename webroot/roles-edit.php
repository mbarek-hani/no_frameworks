<?php
declare(strict_types=1);

require "../lib/errors.php";
require "../lib/request.php";
require "../lib/database.php";
require "../lib/validate.php";
require "../lib/template.php";

mh_request_assert_methods(["GET, POST"]);

function mh_render_roles_edit(array $role, array $errors): void
{
    require "../templates/roles-edit.php";
}

$id = mh_request_get_int_parameter("id", INPUT_GET, 1, PHP_INT_MAX);

$pdo = mh_database_get_connection();

if (!mh_database_does_row_exist($pdo, "roles", $id)) {
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

if (mh_request_is_method("GET")) {
    $statement = $pdo->prepare("select * from roles where id = :id");
    $statement->bindValue(":id", $id, PDO::PARAM_INT);
    $statement->execute();
    $role = $statement->fetch(PDO::FETCH_ASSOC);
} else {
    $edited_role = [
        "id" => $id,
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
        mh_database_does_role_name_exist($pdo, $edited_role["name"], $id)
    ) {
        $errors["name"] = "name already in use";
    }

    if (mh_errors_is_empty($errors)) {
        $statement = $pdo->prepare(
            "update roles set name=:name, description=:description where id=:id",
        );
        $statement->bindValue(":id", $id, PDO::PARAM_STR);
        $statement->bindValue(":name", $edited_role["name"], PDO::PARAM_STR);
        $statement->bindValue(
            ":description",
            $edited_role["description"],
            PDO::PARAM_STR,
        );
        $statement->execute();
        mh_request_redirect("/roles");
    }
}

$data = mh_template_escape_array($role ?? $edited_role);

mh_template_render_header("Edit role");
mh_template_render_sidebar("/roles/edit");
mh_render_roles_edit($data, $errors);
mh_template_render_footer();
