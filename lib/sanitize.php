<?php
declare(strict_types=1);

function mh_sanitize_search_query_username(string $parameter): string {
    if (!isset($_GET[$parameter]) || empty($_GET[$parameter])) {
        return "";
    }
    if (!preg_match("/^[a-zA-Z0-9\s]{1,32}$/", trim($_GET[$parameter]))) {
        mh_request_terminate(400);
    }
    return trim($_GET[$parameter]);
}
