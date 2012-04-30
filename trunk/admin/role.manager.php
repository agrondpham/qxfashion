<?php

require_once("../configuration.php");
require_once("global.php");


// show off: template
require_once("../includes/io.php");
require_once("../includes/template.php");
require_once("../includes/roles.privileges.php");
require_once("../includes/function.php");
$title = "Admin - Roles";
$objTemplate = new template("../admin");
$defaulttemplate = new template("../default");

eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");

eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
eval("\$content = \"" . $objTemplate->get("role.manager.content") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("../configuration.end.php");
?>