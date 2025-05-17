<?php
declare(strict_types=1);

require_once "dotenv.php";

function mh_database_get_connection(): PDO {
    $dsn = "mysql:host=" . getenv("DB_HOST") . ";dbname=" . getenv("DB_NAME") . ";charset=utf8mb4";
    $pdo = new PDO($dsn, getenv("DB_USER"), getenv("DB_PASS"));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}
