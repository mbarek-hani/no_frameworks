<?php

declare(strict_types=1);

require_once "template.php";

function mh_authorization_is_authorized(string $action): bool
{
    if (
        !isset($_SESSION["actions"]) ||
        !mh_authentication_is_user_logged_in()
    ) {
        http_response_code(401);
        mh_template_render_401();
        exit();
    }
    return in_array($action, $_SESSION["actions"]);
}

function mh_authorization_is_authorized_any(array $actions): bool
{
    if (
        !isset($_SESSION["actions"]) ||
        !mh_authentication_is_user_logged_in()
    ) {
        http_response_code(401);
        mh_template_render_401();
        exit();
    }
    return count(array_intersect($_SESSION["actions"], $actions)) >= 1;
}

function mh_authorization_is_authorized_all(array $actions): bool
{
    if (
        !isset($_SESSION["actions"]) ||
        !mh_authentication_is_user_logged_in()
    ) {
        http_response_code(401);
        mh_template_render_401();
        exit();
    }
    return count(array_intersect($_SESSION["actions"], $actions)) ===
        count($actions);
}

function mh_authorization_assert_authorized(string $action): void
{
    if (!mh_authorization_is_authorized($action)) {
        http_response_code(401);
        mh_template_render_401();
        exit();
    }
}

function mh_authorization_assert_authorized_any(array $actions): void
{
    if (!mh_authorization_is_authorized_any($actions)) {
        http_response_code(401);
        mh_template_render_401();
        exit();
    }
}

function mh_authorization_assert_authorized_all(array $actions): void
{
    if (!mh_authorization_is_authorized_all($actions)) {
        http_response_code(401);
        mh_template_render_401();
        exit();
    }
}
