<?php
/*
 * Author: Agrond
 * 
 * Publish Date: 28/04/2012
 * 
 * Content: Create the Index page 
 * 
 * 
 * History
 * 
 * Author       Date            Description
 * 
 * 
 */
/* ======================================================================
 *                  Include addition php page
 * ======================================================================
 */
require_once("includes/configuration.php");
require_once("components/com_helper/function.php");
require_once("components/com_helper/function.number.php");
require_once("components/com_io/io.php");
require_once("components/com_template/template.php");
require_once("components/com_pagination/pagination.php");

//Include object of database
require_once("libraries/tables/category.php");
require_once("libraries/tables/product.php");
require_once("libraries/tables/page.php");
require_once("libraries/tables/cart.php");

/* ======================================================================
 *                  Variable delarce
 * ======================================================================
 */

$title = "Home";
$objTemplate = new template("default");

// variable initializationu
$categories = "";
$product_rows = "";
$menu_rows = "";
/* ======================================================================
 *                  Render
 * ======================================================================
 */
// Navigator
require_once("includes/list.menu.php");
// Categories section
require_once("includes/list.categories.php");
// cart
require_once("includes/list.cart.php");
// layout
eval("\$content = \"" . $objTemplate->get("contact.thankyou.content") . "\";");

eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$cart = \"" . $objTemplate->get("body.cart") . "\";");
eval("\$body = \"" . $objTemplate->get("body") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("includes/configuration.end.php");
?>