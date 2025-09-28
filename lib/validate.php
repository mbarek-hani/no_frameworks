<?php

declare(strict_types=1);

function mh_validate_username(string $username, string $fieldname): ?string
{
    $msg = null;
    if (empty($username)) {
        $msg = "$fieldname is required";
    } elseif (!preg_match("/^[a-zA-Z][a-zA-Z0-9]{3,31}$/", $username)) {
        $msg = "$fieldname should be min 4 max 32 alphanumeric characters starting with a letter";
    }
    return $msg;
}

function mh_validate_email(string $email, string $fieldname): ?string
{
    $msg = null;
    if (empty($email)) {
        $msg = "$fieldname is required";
    } elseif (
        filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) ===
        null
    ) {
        $msg = "invalid email address";
    }
    return $msg;
}

function mh_validate_name(string $name, string $fieldname): ?string
{
    $msg = null;
    if (empty($name)) {
        $msg = "$fieldname is required";
    } elseif (!preg_match("/^[a-zA-Z]{4,15}$/", $name)) {
        $msg = "$fieldname should be min 4 max 15 letters";
    }
    return $msg;
}

function mh_validate_password(
    string $password,
    string $password_confirm,
    string $fieldname,
): ?string {
    $msg = null;
    if (empty($password)) {
        $msg = "$fieldname is required";
    } elseif (!preg_match("/^[a-zA-Z0-9]{8,24}$/", $password)) {
        $msg = "$fieldname must be at least 8 characters";
    } elseif ($password !== $password_confirm) {
        $msg = "passwords do not match";
    }
    return $msg;
}
/*
 * validate the search GET parameter.If the validation failed terminate the request with status code 400 BAD REQUEST
 * @param $parameter the name of the search parameter
 * @return string the parameter trimmed after successfull validation
 */
function mh_validate_search_query_parameter(string $parameter): string
{
    if (!isset($_GET[$parameter]) || empty($_GET[$parameter])) {
        return "";
    }
    if (!preg_match("/^[a-zA-Z0-9\s]{1,32}$/", trim($_GET[$parameter]))) {
        mh_request_terminate(400);
    }
    return trim($_GET[$parameter]);
}

function mh_validate_role_name(string $role_name, string $fieldname): ?string
{
    $msg = null;
    if (empty($role_name)) {
        $msg = "$fieldname is required";
    } elseif (!preg_match("/^[a-zA-Z]{4,31}$/", $role_name)) {
        $msg = "$fieldname should be min 4 max 32 letters";
    }
    return $msg;
}

function mh_validate_role_description(
    string $role_description,
    string $fieldname,
): ?string {
    $msg = null;
    if (empty($role_description)) {
        $msg = "$fieldname is required";
    } elseif (!preg_match("/^[a-zA-Z ;.,!:?]{10,1024}$/", $role_description)) {
        $msg = "$fieldname length should be between 10 and 1024 letters";
    }
    return $msg;
}

function mh_validate_action_name(
    string $action_name,
    string $fieldname,
): ?string {
    $msg = null;
    if (empty($action_name)) {
        $msg = "$fieldname is required";
    } elseif (!preg_match("/^[a-zA-Z]{4,31}$/", $action_name)) {
        $msg = "$fieldname should be min 4 max 32 letters";
    }
    return $msg;
}

function mh_validate_action_description(
    string $action_description,
    string $fieldname,
): ?string {
    $msg = null;
    if (empty($action_description)) {
        $msg = "$fieldname is required";
    } elseif (
        !preg_match("/^[a-zA-Z ;.,!:?]{10,1024}$/", $action_description)
    ) {
        $msg = "$fieldname length should be between 10 and 1024 letters";
    }
    return $msg;
}

function mh_validate_login_username(
    string $username,
    string $fieldname,
): ?string {
    $msg = null;
    if (empty($username)) {
        $msg = "$fieldname is required";
    }
    return $msg;
}

function mh_validate_login_password(
    string $password,
    string $fieldname,
): ?string {
    $msg = null;
    if (empty($password)) {
        $msg = "$fieldname is required";
    }
    return $msg;
}

function mh_validate_category_name(string $category_name, string $fieldname): ?string
{
    $msg = null;
    if (empty($category_name)) {
        $msg = "$fieldname is required";
    } elseif (!preg_match("/^[a-zA-Z' ]{4,64}$/", $category_name)) {
        $msg = "$fieldname should be min 4 max 64 letters";
    }
    return $msg;
}
