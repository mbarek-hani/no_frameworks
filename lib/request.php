<?php
declare(strict_types=1);

function mh_request_terminate(int $code): void {
    http_response_code($code);
    exit(1);
}

function mh_request_assert_method(string $method): void {
    if (!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"] !== $method) {
        http_response_code(405);
        header("Allow: " . $method);
        exit();
    }
}

function mh_request_assert_methods(array $methods): void {
    if (!isset($_SERVER["REQUEST_METHOD"]) || in_array($_SERVER["REQUEST_METHOD"], haystack: $methods)) {
        http_response_code(405);
        header("Allow: " . implode(", ", $methods));
        exit();
    }
}

function mh_request_get_int_query_parameter(string $parameter, int $min, int $max, ?int $default=null): int {
    $value = filter_input(
        INPUT_GET, 
        $parameter, 
        FILTER_VALIDATE_INT, 
        [
            "options" => ["min_range" => $min, "max_range" => $max],
            "flags" => FILTER_NULL_ON_FAILURE
        ]
    );

    if (($value === false && $default === null) || $value === null) {
        mh_request_terminate(400);
    }

    if ($value === false) {
        return $default;
    }

    return $value; 
}
