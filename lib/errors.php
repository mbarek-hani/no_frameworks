<?php
declare(strict_types=1);

// DEV ERROR HANDLER
function development_error_handler(int $errno, string $errstr, string $errfile, int $errline) {
    http_response_code(500);
    echo "<pre><strong>ERROR [$errno]</strong>: $errstr in $errfile on line $errline</pre>";
    exit(1);
};

// DEV EXCEPTION HANDLER
function development_exception_handler(Throwable $exception) {
    http_response_code(500);
    echo "<pre><strong>UNCAUGHT EXCEPTION</strong>: " . $exception->getMessage() . "\n";
    echo $exception->getTraceAsString() . "</pre>";
    exit(1);
};

set_exception_handler("development_exception_handler");
set_error_handler("development_error_handler");

// PROD ERROR HANDLER
// set_error_handler(function ($errno, $errstr, $errfile, $errline) {
//     error_log("ERROR [$errno]: $errstr in $errfile on line $errline");
//     http_response_code(500);
//     echo "An unexpected error occurred. Please try again later.";
//     return true;
// });

// PROD EXCEPTION HANDLER
// set_exception_handler(function ($exception) {
//     error_log("UNCAUGHT EXCEPTION: " . $exception->getMessage() . "\n" . $exception->getTraceAsString());
//     http_response_code(500);
//     echo "Something went wrong. We're working on it.";
// });

// register_shutdown_function(function () {
//     $error = error_get_last();
//     if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
//         // Handle fatal error
//         error_log("Fatal error: " . $error['message']);
//         http_response_code(500);
//         echo "A critical error occurred.";
//     }
// });