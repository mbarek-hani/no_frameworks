<?php

declare(strict_types=1);

require_once "../lib/errors.php";
require_once "../lib/authentication.php";
require_once "../lib/authorization.php";
require_once "../lib/database.php";
require_once "../lib/request.php";
require_once "../lib/template.php";

mh_request_assert_method("GET");

$logged_in_user = mh_authentication_require_login();

function mh_render_categories(array $categories): void
{
    require_once "../templates/categories.php";
}

$pdo = mh_database_get_connection();

$statement = $pdo->query("select node.id, node.name, count(parent.name) -1 as depth, node.lft, node.rgt from categories as node, categories as parent where node.lft between parent.lft and parent.rgt group by node.name order by node.lft");
$categories = mh_template_escape_array_of_arrays($statement->fetchAll(PDO::FETCH_ASSOC));


mh_template_render_header("Categories");
mh_template_render_sidebar("/categories");
mh_render_categories($categories);
mh_template_render_footer();
