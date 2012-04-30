<?php

// show off: template
require_once("configuration.php");
require_once("includes/function.php");
require_once("includes/function.number.php");
require_once("includes/io.php");
require_once("includes/template.php");
require_once("includes/category.php");
require_once("includes/product.php");

$errors = "";
$category_options = "";
$done = (isset($_GET['done'])) ? intval(sql_injection($_GET['done'])) : NULL;
if($done)
{
    $objProduct = new product();
    $objProduct->id = $done;
    $objProduct->get();
    $errors = "{$product->name} has added to your <a href=\"cart.edit.php\">shopping cart</a>.";
}


$id = (isset($_GET['id'])) ? intval(sql_injection($_GET['id'])) : 0;
$keyword = isset($_GET['keyword']) ? sql_injection($_GET['keyword']) : NULL;
// check id is integer
if (is_int($id) && $id > 0) {
    require_once("includes/cart.php");
    $objCart = new customers_cart();
    $objCart->customer_id = $CurrentSession->customer_id;
    $objCart->session_id = $CurrentSession->session_id;
    $objCart->product_id = $id;
    $objCart->quantity = 1;
    $objCart->status = cart_status::Active;
    $objCart->create();
    header ('HTTP/1.1 301 Moved Permanently');
    header("location: search.php?keyword={$keyword}&done={$cart->product_id}");
    exit();
}

// action

$search_product_rows = "";
$objTemplate = new template("default");

if ($keyword) {
    $objProduct = new product();
    $product_queries = $objProduct->search_product_name($keyword);
    while ($obj = $mysql->fo($product_queries)) {
        $objProduct = product::static_set_object($obj);
        if ($objProduct->discount > 0) {
            $price_after_discount = get_discount_price($objProduct->price, $objProduct->discount);
        } else {
            $price_after_discount = $objProduct->price;
        }
        eval("\$search_product_rows .= \"" . $objTemplate->get("search.rows") . "\";");
    }
}

$title = "Home - Search";


// variable initializationu
$categories = "";
$order_product_rows = "";
$menu_rows = "";
$order_tables = "";

// Navigator
require_once("includes/page.php");
require_once("includes/list.menu.php");

// Categories section
require_once("includes/list.categories.php");



// View Order
eval("\$body = \"" . $objTemplate->get("search.content") . "\";");

// layout

eval("\$header = \"" . $objTemplate->get("header") . "\";");

eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("configuration.end.php");
?>