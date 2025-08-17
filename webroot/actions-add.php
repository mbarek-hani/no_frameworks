<?php

declare(strict_types=1);

require "../lib/errors.php";
require "../lib/request.php";
require "../lib/database.php";
require "../lib/validate.php";
require "../lib/template.php";

mh_request_assert_methods(["GET, POST"]);

function mh_render_actions_add(array $action, array $errors): void
{
    require "../templates/actions-add.php";
}

$pdo = mh_database_get_connection();

$errors = [
    "name" => null,
    "description" => null,
];
$added_action = [
    "name" => null,
    "description" => null,
];

if (mh_request_is_method("POST")) {
    $added_action = [
        "name" => trim($_POST["name"] ?? ""),
        "description" => trim($_POST["description"] ?? ""),
    ];

    $errors["name"] = mh_validate_action_name($added_action["name"], "name");
    $errors["description"] = mh_validate_action_description(
        $added_action["description"],
        "description",
    );

    if (
        $errors["name"] === null &&
        mh_database_does_action_name_exist($pdo, $added_action["name"])
    ) {
        $errors["name"] = "action name already in use";
    }

    if (mh_errors_is_empty($errors)) {
        $statement = $pdo->prepare(
            "insert into actions(name, description) values (:name, :description)",
        );
        $statement->bindValue(":name", $added_action["name"], PDO::PARAM_STR);
        $statement->bindValue(
            ":description",
            $added_action["description"],
            PDO::PARAM_STR,
        );
        $statement->execute();

        mh_request_redirect("/actions");
    }
}

$data = mh_template_escape_array($added_action);

mh_template_render_header("Add action");
mh_template_render_sidebar("/actions/add");
mh_render_actions_add($data, $errors);
mh_template_render_footer();
