<?php
declare(strict_types=1);

require_once "request.php";

function mh_authorization_is_authorized(string $action): bool
{
    if (
        !isset($_SESSION["actions"]) ||
        !mh_authentication_is_user_logged_in()
    ) {
        mh_request_terminate(401);
    }
    return in_array($action, $_SESSION["actions"]);
}

function mh_authorization_is_authorized_any(array $actions): bool
{
    if (
        !isset($_SESSION["actions"]) ||
        !mh_authentication_is_user_logged_in()
    ) {
        mh_request_terminate(401);
    }
    return count(array_intersect($_SESSION["actions"], $actions)) >= 1;
}

function mh_authorization_is_authorized_all(array $actions): bool
{
    if (
        !isset($_SESSION["actions"]) ||
        !mh_authentication_is_user_logged_in()
    ) {
        mh_request_terminate(401);
    }
    return count(array_intersect($_SESSION["actions"], $actions)) ===
        count($actions);
}

function mh_authorization_assert_authorized(string $action): void
{
    if (!mh_authorization_is_authorized($action)) {
        mh_request_terminate(401);
    }
}

function mh_authorization_assert_authorized_any(array $actions): void
{
    if (!mh_authorization_is_authorized_any($actions)) {
        mh_request_terminate(401);
    }
}

function mh_authorization_assert_authorized_all(array $actions): void
{
    if (!mh_authorization_is_authorized_all($actions)) {
        mh_request_terminate(401);
    }
}
