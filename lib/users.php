<?php

declare(strict_types=1);

/*
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
