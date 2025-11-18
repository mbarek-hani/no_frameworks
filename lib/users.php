<?php

declare(strict_types=1);

function mh_users_get_by_id(PDO $pdo, int $user_id): array
{
    $statement = $pdo->prepare(
        "select id, username, first_name, last_name, email from users where id = :id",
    );
    $statement->bindValue(":id", $user_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function mh_users_get_roles(PDO $pdo, int $user_id): array
{
    $statement = $pdo->prepare(
        "select id, name, description from roles, users_roles where role_id = id and user_id = :id",
    );
    $statement->bindValue(":id", $user_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function mh_users_get_other_roles(PDO $pdo, int $user_id): array
{
    $statement = $pdo->prepare(
        "select id, name, description from roles where id not in (select role_id from users_roles where user_id = :id)",
    );
    $statement->bindValue(":id", $user_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function mh_users_add_role(PDO $pdo, int $user_id, int $role_id): void
{
    $statement = $pdo->prepare(
        "insert into users_roles values(:user_id, :role_id)",
    );
    $statement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $statement->bindValue(":role_id", $role_id, PDO::PARAM_INT);
    $statement->execute();
}

function mh_users_remove_role(PDO $pdo, int $user_id, int $role_id): void
{
    $statement = $pdo->prepare(
        "delete from users_roles where user_id=:user_id and role_id=:role_id",
    );
    $statement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $statement->bindValue(":role_id", $role_id, PDO::PARAM_INT);
    $statement->execute();
}

function mh_users_edit(PDO $pdo, array $edited_user): void
{
    if (!empty($edited_user["password"])) {
        $statement = $pdo->prepare(
            "update users set username=:username, first_name=:first_name, last_name=:last_name, email=:email, password=:password where id=:id",
        );
        $password_hash = password_hash(
            $edited_user["password"],
            PASSWORD_DEFAULT,
        );
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
    $statement->bindValue(":email", $edited_user["email"], PDO::PARAM_STR);
    $statement->bindValue(":id", $edited_user["id"], PDO::PARAM_INT);
    $statement->execute();
}
