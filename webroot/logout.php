<?php

declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/request.php";

mh_request_assert_method("POST");

mh_authentication_require_login();

session_unset();
session_destroy();
mh_request_redirect("/login");
