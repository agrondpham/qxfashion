<?php

// show off: template
require_once("configuration.php");
require_once("includes/io.php");
require_once("includes/template.php");
require_once("includes/category.php");
require_once("includes/product.php");
require_once("includes/function.php");

// initialization
$objTemplate = new template("default");
$categories = "";
$menu_rows = "";
// Categories section
require_once("includes/list.categories.php");
require_once("includes/cart.php");
require_once("includes/function.number.php");
$objCart = new customers_cart();
$objCart->session_id = $CurrentSession->session_id;
$cart_queries = $objCart->get_session_cart();
$cart_edit_rows = "";
$total = 0.00;

while ($obj = $mysql->fo($cart_queries)) {
    $objCart = customers_cart::static_set_object($obj);
    $objProduct = product::get_product($objCart->product_id);
    $original_price = $objProduct->price;
    
    if ($objProduct->discount > 0) {
        $discount_price = get_discount_price($objProduct->price, $objProduct->discount);
        $total += $discount_price*$objCart->quantity;
        $objProduct->price = "{$discount_price}";
        $product_total_price = $discount_price * $objCart->quantity;
    } else {
        $total += $objProduct->price*$objCart->quantity;
        $product_total_price = $objProduct->price * $objCart->quantity;
    }
    eval("\$cart_edit_rows .= \"" . $objTemplate->get("cart.edit.preview.rows") . "\";");
}
eval("\$cart_edit_form = \"" . $objTemplate->get("cart.edit.preview") . "\";");
// Navigator
require_once("includes/page.php");
require_once("includes/list.menu.php");
// layout
$title = "Shopping cart";


eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$body = \"" . $objTemplate->get("cart.checkout.content") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("configuration.end.php");
?>