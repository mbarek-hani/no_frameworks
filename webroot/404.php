<?php
declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/template.php";

http_response_code(404);
mh_template_render_404();
