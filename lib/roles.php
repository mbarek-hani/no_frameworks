<?php

declare(strict_types=1);

function mh_roles_get_by_id(PDO $pdo, int $role_id): array
{
    $statement = $pdo->prepare("select * from roles where id = :id");
    $statement->bindValue(":id", $role_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function mh_roles_get_actions(PDO $pdo, int $role_id): array
{
    $statement = $pdo->prepare(
        "select id, name, description from actions, roles_actions where action_id = id and role_id = :id",
    );
    $statement->bindValue(":id", $role_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function mh_roles_get_other_actions(PDO $pdo, int $role_id): array
{
    $statement = $pdo->prepare(
        "select id, name, description from actions where id not in (select action_id from roles_actions where role_id = :id)",
    );
    $statement->bindValue(":id", $role_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function mh_roles_add_action(PDO $pdo, int $role_id, int $action_id): void
{
    $statement = $pdo->prepare(
        "insert into roles_actions values(:role_id, :action_id)",
    );
    $statement->bindValue(":role_id", $role_id, PDO::PARAM_INT);
    $statement->bindValue(":action_id", $action_id, PDO::PARAM_INT);
    $statement->execute();
}

function mh_roles_remove_action(PDO $pdo, int $role_id, int $action_id): void
{
    $statement = $pdo->prepare(
        "delete from roles_actions where role_id=:role_id and action_id=:action_id",
    );
    $statement->bindValue(":role_id", $role_id, PDO::PARAM_INT);
    $statement->bindValue(":action_id", $action_id, PDO::PARAM_INT);
    $statement->execute();
}

function mh_roles_edit(PDO $pdo, array $edited_role): void
{
    $statement = $pdo->prepare(
        "update roles set name=:name, description=:description where id=:id",
    );
    $statement->bindValue(":id", $edited_role["id"], PDO::PARAM_STR);
    $statement->bindValue(":name", $edited_role["name"], PDO::PARAM_STR);
    $statement->bindValue(
        ":description",
        $edited_role["description"],
        PDO::PARAM_STR,
    );
    $statement->execute();
}

function mh_roles_delete(PDO $pdo, int $role_id): void
{
    $statement = $pdo->prepare("delete from roles where id=:id");
    $statement->bindValue(":id", $role_id, PDO::PARAM_INT);
    $statement->execute();
}
