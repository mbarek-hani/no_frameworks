<?php
declare(strict_types=1);

require_once "dotenv.php";

/*
 * get database connection as a PDO object
 * @return PDO
 * @throws PDOException
 */
function mh_database_get_connection(): PDO
{
    $dsn =
        "mysql:host=" .
        getenv("DB_HOST") .
        ";dbname=" .
        getenv("DB_NAME") .
        ";charset=utf8mb4";
    $pdo = new PDO($dsn, getenv("DB_USER"), getenv("DB_PASS"));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

/*
 * check if a row exists with id in a specific table
 * @param $pdo PDO object to connect to db
 * @param $table string table to look in
 * @param $id id to look for
 * @return bool true if exists, false if not
 */
function mh_database_does_row_exist(PDO $pdo, string $table, int $id): bool
{
    $statement = $pdo->prepare("select count(*) from $table where id=:id");
    $statement->bindValue(":id", $id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchColumn(0) == 1;
}

/*
 * check if a value is unique in a specific column of a specific table.
 * @param $pdo PDO object to connect to db
 * @param $table_name the table name
 * @param $column_name the column name to check
 * @param $column_value the column value
 * @param $id if passed, the check will be done on every row that have a  primary key different than it
 * @return bool true if it is unique, false if not
 */
function mh_database_is_unique_column_value(
    PDO $pdo,
    string $table_name,
    string $column_name,
    mixed $column_value,
    ?int $id = null,
): bool {
    $type = match (gettype($column_value)) {
        "string" => PDO::PARAM_STR,
        "integer" => PDO::PARAM_INT,
        "boolean" => PDO::PARAM_BOOL,
        default => trigger_error(
            "got an unexpected type of \$column_value",
            E_USER_ERROR,
        ),
    };
    if ($id) {
        $statement = $pdo->prepare(
            "select count(*) from $table_name where $column_name=:value and id<>:id",
        );
        $statement->bindValue(":value", $column_value, $type);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
    } else {
        $statement = $pdo->prepare(
            "select count(*) from $table_name where $column_name=:value",
        );
        $statement->bindValue(":value", $column_value, $type);
    }
    $statement->execute();
    return $statement->fetchColumn(0) == 0;
}

/*
 * check if a user exists with first and last names
 * @param $pdo PDO object to connect to db
 * @param $first_name first name to look for
 * @param $last_name last name to look for
 * @param $id if passed, the check will be done on every user that have a different id
 * @return bool true if exists, false if not
 */
function mh_database_does_name_exist(
    PDO $pdo,
    string $first_name,
    string $last_name,
    ?int $id = null,
): bool {
    if ($id) {
        $statement = $pdo->prepare(
            "select count(*) from users where first_name=:first_name and last_name=:last_name and id<>:id",
        );
        $statement->bindValue(":first_name", $first_name, PDO::PARAM_STR);
        $statement->bindValue(":last_name", $last_name, PDO::PARAM_STR);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
    } else {
        $statement = $pdo->prepare(
            "select count(*) from users where first_name=:first_name and last_name=:last_name",
        );
        $statement->bindValue(":first_name", $first_name, PDO::PARAM_STR);
        $statement->bindValue(":last_name", $last_name, PDO::PARAM_STR);
    }
    $statement->execute();
    return $statement->fetchColumn(0) == 1;
}
