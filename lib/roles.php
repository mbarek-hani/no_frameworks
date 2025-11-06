<?php

declare(strict_types=1);

function mh_roles_count(PDO $pdo, string $search_param): int
{
    $statement = $pdo->prepare(
        "select count(*) from roles where name like :search",
    );
    $statement->bindValue(":search", "%$search_param%");
    $statement->execute();
    return $statement->fetchColumn(0);
}

function mh_roles_get_all(PDO $pdo, string $search_param, int $offset, int $limit): array
{
    $statement = $pdo->prepare(
        "select * from roles where name like :search limit :offset, :limit",
    );
    $statement->bindValue(":offset", $offset, PDO::PARAM_INT);
    $statement->bindValue(":limit", $limit, PDO::PARAM_INT);
    $statement->bindValue(":search", "%$search_param%", PDO::PARAM_STR);
    $statement->execute();
    return $statement->fetchAll(pdo::FETCH_ASSOC);
}

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
