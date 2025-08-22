<?php

declare(strict_types=1);

require "../lib/errors.php";
require "../lib/request.php";
require "../lib/database.php";
require "../lib/validate.php";
require "../lib/template.php";

mh_request_assert_methods(["GET, POST"]);

function mh_render_roles_add(array $role, array $errors): void
{
    require "../templates/roles-add.php";
}

$pdo = mh_database_get_connection();

$errors = [
    "name" => null,
    "description" => null,
];
$added_role = [
    "name" => null,
    "description" => null,
];

if (mh_request_is_method("POST")) {
    $added_role = [
        "name" => trim($_POST["name"] ?? ""),
        "description" => trim($_POST["description"] ?? ""),
    ];

    $errors["name"] = mh_validate_role_name($added_role["name"], "name");
    $errors["description"] = mh_validate_role_description(
        $added_role["description"],
        "description",
    );

    if (
        $errors["name"] === null &&
        !mh_database_is_unique_column_value(
            $pdo,
            "roles",
            "name",
            $added_role["name"],
        )
    ) {
        $errors["name"] = "role name already in use";
    }

    if (mh_errors_is_empty($errors)) {
        $statement = $pdo->prepare(
            "insert into roles(name, description) values (:name, :description)",
        );
        $statement->bindValue(":name", $added_role["name"], PDO::PARAM_STR);
        $statement->bindValue(
            ":description",
            $added_role["description"],
            PDO::PARAM_STR,
        );
        $statement->execute();

        mh_request_redirect("/roles");
    }
}

$data = mh_template_escape_array($added_role);

mh_template_render_header("Add role");
mh_template_render_sidebar("/roles/add");
mh_render_roles_add($data, $errors);
mh_template_render_footer();
