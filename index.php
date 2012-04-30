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
require_once("libraries/tables/setting.php");
require_once("libraries/tables/cart.php");

/* ======================================================================
 *                  Variable delarce
 * ======================================================================
 */
$title = "Qx Fashion Brand|Home";

// Declare Object
$objSetting    = new setting();
$objProduct    = new Product();
$objTemplate   = new template("default");
$objPagination = new pagination("index.php?page=", $page_id, $total_products, $objProduct->limit);

// Declare Variable
$page_id = isset($_GET['page']) ? intval(sql_injection($_GET['page'])) : 0;
//$categories = "";
//$product_rows = "";
//$menu_rows = "";


/* ======================================================================
 *                  Render
 * ======================================================================
 */
require_once("includes/list.menu.php"); // Navigator


$objProduct->limit = $objSetting->product_limit;
$total_products = $objProduct->get_total_products();

// set the start to get products    
$objProduct->start = $objPagination->start;
$query_products = $objProduct->get_topproduct();
$pagination_html = $objPagination->get_html();
while ($obj = $mysql->fo($query_products)) {
    $objProduct = Product::static_set_object($obj);
    if ($objProduct->discount > 0) {
        $discount_price = get_discount_price($objProduct->price, $objProduct->discount);
        $objProduct->price = "<del>{$objProduct->price}$</del> {$discount_price}$";
    } else {
        $objProduct->price = $objProduct->price . "$";
    }
    eval("\$product_rows .= \"" . $objTemplate->get("product.rows") . "\";");
}        
// Categories section
require_once("includes/list.categories.php");

// cart
//require_once("includes/list.cart.php");
// layout
eval("\$content = \"" . $objTemplate->get("index.content") . "\";");

eval("\$header = \"" . $objTemplate->get("header") . "\";");
//eval("\$cart = \"" . $objTemplate->get("body.cart") . "\";");
eval("\$body = \"" . $objTemplate->get("body") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

//LPT Bring category sub to direct index pagr
eval("\$categoryRow = \"" . $objTemplate->get("category.rows") . "\";");
eval("\$categoryRowsChild = \"" . $objTemplate->get("category.rows.child") . "\";");
// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("includes/configuration.end.php");
?>