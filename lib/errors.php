<?php

declare(strict_types=1);

require_once __DIR__ . "/dotenv.php";
require_once __DIR__ . "/template.php";

const ERROR_LOG_FILENAME = __DIR__ . "/../error.log";
const DATE_LOG_FORMAT = "d/m/Y H:i:s";

ob_start();

function log_error(string $message): void
{
    $handle = fopen(ERROR_LOG_FILENAME, "a");
    if (!$handle) {
        return;
    }
    fwrite($handle, sprintf("ERROR: %s %s\n", date(DATE_LOG_FORMAT), $message));
    fclose($handle);
}

function error_handler(
    int $errno,
    string $errstr,
    string $errfile,
    int $errline,
) {
    ob_clean();
    http_response_code(500);
    $debug = false;
    if (getenv("DEBUG") === "true") {
        $debug = true;
    }
    if (!$debug) {
        log_error(sprintf("%s in %s on line %d code %d", $errstr, $errfile, $errline, $errno));
        mh_template_render_header("Server error");
        mh_template_render_sidebar("");
        require_once __DIR__ . "/../templates/500.php";
        mh_template_render_footer();
    } else {
        echo "<pre><strong>ERROR [$errno]</strong>: $errstr in $errfile on line $errline</pre>";
    }
    exit(1);
}

function exception_handler(Throwable $exception)
{
    ob_clean();
    http_response_code(500);
    $debug = false;
    if (getenv("DEBUG") === "true") {
        $debug = true;
    }
    if (!$debug) {
        log_error(sprintf("UNCAUGHT EXCEPTION: %s \n\t%s", $exception->getMessage(),  str_replace("\n", "\n\t", $exception->getTraceAsString())));
        mh_template_render_header("Server error");
        mh_template_render_sidebar("");
        require_once __DIR__ . "/../templates/500.php";
        mh_template_render_footer();
    } else {
        echo "<pre><strong>UNCAUGHT EXCEPTION</strong>: " .
            $exception->getMessage() .
            "\n";
        echo $exception->getTraceAsString() . "</pre>";
    }
    exit(1);
}

set_exception_handler("exception_handler");
set_error_handler("error_handler");

/**
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
