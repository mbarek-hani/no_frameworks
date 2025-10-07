<?php

declare(strict_types=1);

/**
 * count the total users found in the users table where username like $search_param
 * if $search_param is an empty string it will count all users 
 * @param $search_param the username to search with 
 * @return int the count of users found
 */
function mh_users_count(PDO $pdo, string $search_param): int
{
    if ($search_param !== "") {
        $statement = $pdo->prepare(
            "select count(*) from users where username like :search",
        );
        $statement->bindValue(":search", "%$search_param%");
        $statement->execute();
    } else {
        $statement = $pdo->query(
            "select * from users"
        );
    }
    return intval($statement->fetchColumn(0));
}

/**
 * get all users from users table where username like $search_param from $offset to $limit
 * @param $search_param the username to search with 
 * @param $offset the row to start fetching from 
 * @param $limit the maximum number of users to fetch 
 * @return array the users fetched in the form of an associative array
 */
function mh_users_get_all(PDO $pdo, string $search_param, int $offset, int $limit): array
{
    $statement = $pdo->prepare(
        "select id, username, first_name, last_name, email from users where username like :search limit :offset, :limit",
    );
    $statement->bindValue(":offset", $offset, PDO::PARAM_INT);
    $statement->bindValue(":limit", $limit, PDO::PARAM_INT);
    $statement->bindValue(":search", "%$search_param%", PDO::PARAM_STR);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

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
}

function mh_users_delete(PDO $pdo, int $user_id): void
{
    $statement = $pdo->prepare("delete from users where id=:id");
    $statement->bindValue(":id", $user_id);
    $statement->execute();
}

function mh_users_add(PDO $pdo, array $user): void
{
    $password_hash = password_hash($user["password"], PASSWORD_DEFAULT);
    $statement = $pdo->prepare(
        "insert into users(username, first_name, last_name, email, password) values (:username, :first_name, :last_name, :email, :password)",
    );
    $statement->bindValue(
        ":username",
        $user["username"],
        PDO::PARAM_STR,
    );
    $statement->bindValue(
        ":first_name",
        $user["first_name"],
        PDO::PARAM_STR,
    );
    $statement->bindValue(
        ":last_name",
        $user["last_name"],
        PDO::PARAM_STR,
    );
    $statement->bindValue(
        ":email",
        $user["email"],
        PDO::PARAM_STR
    );
    $statement->bindValue(
        ":password",
        $password_hash,
        PDO::PARAM_STR,
    );
    $statement->execute();
}
