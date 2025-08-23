<?php
declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/template.php";

function mh_render_login()
{
    require_once "../templates/login.php";
}

mh_template_render_header("Login");
mh_render_login();
mh_template_render_footer();
