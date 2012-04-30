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
require_once("components/com_helper/function.parse.php");
require_once("components/com_io/io.php");
require_once("components/com_template/template.php");
require_once("components/com_pagination/pagination.php");

//Include object of database
require_once("libraries/tables/category.php");
require_once("libraries/tables/product.php");
require_once("libraries/tables/page.php");
require_once("libraries/tables/cart.php");
require_once("libraries/tables/faq.php");


/* ======================================================================
 *                  Variable delarce
 * ======================================================================
 */
// INIT VARIABLES
$categories = "";
$menu_rows = "";
$faq_rows = "";
$faq = new faq();
$faq_queries = $faq->get_faqs();
$objTemplate = new template("default");

// Category section
require_once("includes/list.categories.php");
// cart
require_once("includes/list.cart.php");
// Navigator
require_once("includes/list.menu.php");

/* ======================================================================
 *                  Render
 * ======================================================================
 */
while ($obj = $mysql->fo($faq_queries)) {
    $faq = faq::static_set_object($obj);
    $faq->content = parse_html($faq->content);
    eval("\$faq_rows .= \"" . $objTemplate->get("faq.rows") . "\";");
}
// ACTIONs
eval("\$testimonials = \"" . $objTemplate->get("element.testimonial") . "\";");
eval("\$content = \"" . $objTemplate->get("faq.content") . "\";");
eval("\$cart = \"" . $objTemplate->get("body.cart") . "\";");
eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$body = \"" . $objTemplate->get("body") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("configuration.end.php");
?>