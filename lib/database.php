<?php
declare(strict_types=1);

require_once "dotenv.php";

function mh_database_get_connection(): PDO {
    $dsn = "mysql:host=" . getenv("DB_HOST") . ";dbname=" . getenv("DB_NAME") . ";charset=utf8mb4";
    $pdo = new PDO($dsn, getenv("DB_USER"), getenv("DB_PASS"));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function mh_database_does_user_exist(PDO $pdo, int $id):bool {
    $statement = $pdo->prepare("select count(*) from users where id=:id");
    $statement->bindValue(":id", $id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchColumn(0) == 1;
}

function mh_database_does_username_exist(PDO $pdo, string $username, ?int $id = null):bool {
    if ($id) {
        $statement = $pdo->prepare("select count(*) from users where username=:username and id<>:id");
        $statement->bindValue(":username", $username, PDO::PARAM_STR);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
    }else {
        $statement = $pdo->prepare("select count(*) from users where username=:username");
        $statement->bindValue(":username", $username, PDO::PARAM_STR);
    }
    $statement->execute();
    return $statement->fetchColumn(0) == 1;
}

function mh_database_does_email_exist(PDO $pdo, string $email ,?int $id = null):bool {
    if ($id) {
        $statement = $pdo->prepare("select count(*) from users where email=:email and id<>:id");
        $statement->bindValue(":email", $email, PDO::PARAM_STR);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
    }else {
        $statement = $pdo->prepare("select count(*) from users where email=:email");
        $statement->bindValue(":email", $email, PDO::PARAM_STR);
    }
    $statement->execute();
    return $statement->fetchColumn(0) == 1;
}

function mh_database_does_name_exist(PDO $pdo, string $first_name, string $last_name, ?int $id = null):bool {
    if ($id) {
        $statement = $pdo->prepare("select count(*) from users where first_name=:first_name and last_name=:last_name and id<>:id");
        $statement->bindValue(":first_name", $first_name, PDO::PARAM_STR);
        $statement->bindValue(":last_name", $last_name, PDO::PARAM_STR);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
    }else {
        $statement = $pdo->prepare("select count(*) from users where first_name=:first_name and last_name=:last_name");
        $statement->bindValue(":first_name", $first_name, PDO::PARAM_STR);
        $statement->bindValue(":last_name", $last_name, PDO::PARAM_STR);
    }
    $statement->execute();
    return $statement->fetchColumn(0) == 1;
}
