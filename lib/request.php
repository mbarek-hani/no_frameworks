<?php
declare(strict_types=1);

/*
 * terminate the current request with a given status code
 * @param $code the status code
 */
function mh_request_terminate(int $code): void
{
    http_response_code($code);
    die();
}

/*
 * insure the request is made with a given method otherwise terminate it with 405 METHOD NOT ALLOWED and set the allow header
 * @param $method the method to allow
 */
function mh_request_assert_method(string $method): void
{
    if (
        !isset($_SERVER["REQUEST_METHOD"]) ||
        $_SERVER["REQUEST_METHOD"] !== $method
    ) {
        http_response_code(405);
        header("Allow: $method");
        exit();
    }
}

/*
 * insure the request is made with a either one of the given methods otherwise terminate it with 405 METHOD NOT ALLOWED and set the allow header
 * @param $methods the methods to allow
 */
function mh_request_assert_methods(array $methods): void
{
    if (
        !isset($_SERVER["REQUEST_METHOD"]) ||
        in_array($_SERVER["REQUEST_METHOD"], haystack: $methods)
    ) {
        http_response_code(405);
        header("Allow: " . implode(", ", $methods));
        exit();
    }
}

/*
 * validate an input (GET or POST) parameter to be an integer in the range of a given min and max and return it or a default if this parameter doesn't exist, otherwise terminate request with a status of 400 BAD REQUEST
 * @param $parameter the input parameter to validate
 * @param $input the input (either INPUT_GET or INPUT_POST) to look for parameter in
 * @param $min the minimum integer the parameter has to be to pass validation
 * @param $max the maximum integer the parameter has to be to pass validation
 * @param $default the integer to return (if it is provided) if the parameter doesn't exist at all
 * @return int the paramter value if it has passed validation or $default if provided
 */
function mh_request_get_int_parameter(
    string $parameter,
    int $input,
    int $min,
    int $max,
    ?int $default = null,
): int {
    $value = filter_input($input, $parameter, FILTER_VALIDATE_INT, [
        "options" => ["min_range" => $min, "max_range" => $max],
        "flags" => FILTER_NULL_ON_FAILURE,
    ]);

    if (($value === false && $default === null) || $value === null) {
        mh_request_terminate(400);
    }

    if ($value === false) {
        return $default;
    }

    return $value;
}

/*
 * check if the request method is the given one
 * @param $method the method to check for
 * @return bool true if it is, false if not
 */
function mh_request_is_method(string $method): bool
{
    return $_SERVER["REQUEST_METHOD"] === $method;
}

/*
 * redirect clients to the given url
 * @param $url the url to redirect to
 */
function mh_request_redirect(string $url): never
{
    header("Location: $url");
    exit();
}
