<?php

// show off: template
require_once("configuration.php");
require_once("includes/io.php");
require_once("includes/template.php");
require_once("includes/function.php");
require_once("includes/category.php");


$CurrentSession->delete_session();
session_destroy();

// INIT VARIABLES
$categories = "";
$menu_rows = "";
$objTemplate = new template("default");
require_once("includes/list.categories.php");

$errors = "";
// cart
require_once("includes/function.number.php");
require_once("includes/product.php");
require_once("includes/cart.php");
require_once("includes/list.cart.php");
// Navigator
require_once("includes/page.php");
require_once("includes/list.menu.php");


eval("\$content = \"" . $objTemplate->get("logout.content") . "\";");

eval("\$cart = \"" . $objTemplate->get("body.cart") . "\";");

eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$body = \"" . $objTemplate->get("body") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("configuration.end.php");
?>