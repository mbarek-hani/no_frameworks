<?php

declare(strict_types=1);

/*
 * load environment variables from a .env file
 * @param $path the path of the .env file
 * @throws Exception when $path doesn't exist
 */
function mh_dotenv_load(string $path): void
{
    if (!file_exists($path)) {
        throw new Exception("$path doesn't exist");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), "#") === 0) {
            continue;
        }
        [$name, $value] = explode("=", $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_ENV)) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}

mh_dotenv_load("../.env");
