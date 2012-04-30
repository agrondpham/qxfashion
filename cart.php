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
$title = "Qx Fashion Brand|Shopping Cart";

//$categories = "";
//$menu_rows = "";
//$cart_edit_rows = "";
$total = 0.00;
// Declare Variable
$objTemplate = new template("default");
$objCart = new customers_cart();
/* ======================================================================
 *                  Render
 * ======================================================================
 */
// Categories section
require_once("includes/list.categories.php");


$objCart->session_id = $CurrentSession->session_id;
$cart_queries = $objCart->get_session_cart();

while ($obj = $mysql->fo($cart_queries)) {
    $objCart = customers_cart::static_set_object($obj);
    $objProduct = product::get_product($objCart->product_id);
    $original_price = $objProduct->price;
    if ($objProduct->discount > 0) {
        $discount_price = get_discount_price($objProduct->price, $objProduct->discount);
        $total += $discount_price * $objCart->quantity;
        $objProduct->price = "{$discount_price}";
    } else {
        $total += $objProduct->price * $objCart->quantity;
    }
    eval("\$cart_rows .= \"" . $objTemplate->get("cart.rows") . "\";");
}
eval("\$cart_list = \"" . $objTemplate->get("cart.list") . "\";");
// Navigator
require_once("includes/list.menu.php");

// layout



eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$body = \"" . $objTemplate->get("cart.content") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("includes/configuration.end.php");
?>