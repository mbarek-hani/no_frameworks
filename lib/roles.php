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
