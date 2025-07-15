<?php
declare(strict_types=1);

function mh_validate_username(string $username, string $fieldname):?string {
    $msg = null;
    if (empty($username)) {
        $msg = "$fieldname is required";
    }elseif(!preg_match("/^[a-zA-Z][a-zA-Z0-9]{3,31}/", $username)) {
        $msg = "$fieldname should be min 4 max 32 alphanumeric characters starting with a letter";
    }
    return $msg;
}

function mh_validate_email(string $email, string $fieldname):?string {
    $msg = null;
    if (empty($email)) {
        $msg = "$fieldname is required";
    }elseif(filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) === null) {
        $msg = "invalid email address";
    }
    return $msg;
}

function mh_validate_name(string $name, string $fieldname):?string {
    $msg = null;
    if (empty($name)) {
        $msg = "$fieldname is required";
    }elseif(!preg_match("/^[a-zA-Z]{4,15}/", $name)) {
        $msg = "$fieldname should be min 4 max 15 letters";
    }
    return $msg;
}

/*
* validate the search GET parameter.If the validation failed terminate the request with status code 400 BAD REQUEST
* @param $parameter the name of the search parameter
* @return string the parameter trimmed after successfull validation
*/
function mh_validate_search_query_parameter(string $parameter): string {
    if (!isset($_GET[$parameter]) || empty($_GET[$parameter])) {
        return "";
    }
    if (!preg_match("/^[a-zA-Z0-9\s]{1,32}$/", trim($_GET[$parameter]))) {
        mh_request_terminate(400);
    }
    return trim($_GET[$parameter]);
}
