<?php

declare(strict_types=1);

function development_error_handler(
    int $errno,
    string $errstr,
    string $errfile,
    int $errline,
) {
    http_response_code(500);
    echo "<pre><strong>ERROR [$errno]</strong>: $errstr in $errfile on line $errline</pre>";
    exit(1);
}

function development_exception_handler(Throwable $exception)
{
    http_response_code(500);
    echo "<pre><strong>UNCAUGHT EXCEPTION</strong>: " .
        $exception->getMessage() .
        "\n";
    echo $exception->getTraceAsString() . "</pre>";
    exit(1);
}

set_exception_handler("development_exception_handler");
set_error_handler("development_error_handler");

/*
 * check if there is any errors in an array of errors
 * @param $errors an associative array with keys that are fields and string values as their corresponding error or null if there is no error
 * @return bool true if empty, false if not
 */
function mh_errors_is_empty(array $errors): bool
{
    foreach ($errors as $key => $error) {
        if ($error !== null) {
            return false;
        }
    }
    return true;
}
