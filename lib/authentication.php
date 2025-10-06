<?php

declare(strict_types=1);

require_once "request.php";

session_start();

/**
 * Redirect user to the login page if he is not logged in
 * @return int the user id if he is logged in
 */
function mh_authentication_require_login(): int
{
    if (!mh_authentication_is_user_logged_in()) {
        mh_request_redirect("/login");
    }

    return $_SESSION["user_id"];
}

/**
 * Redirect user to the users listing page for now if he is logged in.
 * It could be used for example to restrict logged in users from accessing the login page
 */
function mh_authentication_require_logout()
{
    if (mh_authentication_is_user_logged_in()) {
        mh_request_redirect("/users");
    }
}

function mh_authentication_is_user_logged_in(): bool
{
    return isset($_SESSION["user_id"]);
}
