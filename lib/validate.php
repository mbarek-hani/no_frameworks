<?php
declare(strict_types=1);

function mh_validate_username(string $username): bool {
    if (!preg_match("/^[a-zA-Z][a-zA-Z0-9]{3,31}/", $username)) {
        return false;
    }
    return true;
}

function mh_validate_name(string $name): bool {
    if (!preg_match("/^[a-zA-Z]{4,15}/", $name)) {
        return false;
    }
    return true;
}

function mh_validate_email(string $email): bool {
    if (filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) === null) {
        return false;
    }
    return true;
}
