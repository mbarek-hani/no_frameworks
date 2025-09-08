<?php

declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/authorization.php";
require_once "../lib/database.php";
require_once "../lib/request.php";
require_once "../lib/template.php";
require_once "../lib/validate.php";

mh_request_assert_method("GET");

function mh_render_categories(array $categories): void
{
    require_once "../templates/categories.php";
}

mh_template_render_header("Categories");
mh_template_render_sidebar("/categories");
mh_render_categories([]);
mh_template_render_footer();
