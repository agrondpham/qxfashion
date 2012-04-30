<?php

// show off: template
require_once("configuration.php");
require_once("includes/function.php");
require_once("includes/io.php");
require_once("includes/template.php");
require_once("includes/category.php");
require_once("includes/product.php");

$title = "Home";
$objTemplate = new template("default");

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
require_once("includes/order.php");
require_once("includes/order.product.php");
$order = new order();
$order->session_id = $CurrentSession->session_id;
if($CurrentSession->id > 0)
{
    $order->customer_id = $CurrentSession->id;
    $order_queries = $order->get_customer_orders();
}
else
{
    $order_queries = $order->get_orders();
}
while($obj = $mysql->fo($order_queries))
{
    
    $order = order::static_set_object($obj);
    $order_products = new order_product();
    $order_products->order_id = $order->order_id;
    
    $order_product_rows = "";
    $order_product_queries = $order_products->get_products();
    while($obj_order_product = $mysql->fo($order_product_queries))
    {
        $order_product = order_product::static_set_object($obj_order_product);
        
        $objProduct = new product();
        $objProduct->id = $order_product->product_id;
        $objProduct->get();
        
        eval("\$order_product_rows  .= \"" . $objTemplate->get("order.view.rows") . "\";");
    }
    
    eval("\$order_tables .= \"" . $objTemplate->get("order.view.table") . "\";");
}
eval("\$body = \"" . $objTemplate->get("order.view.content") . "\";");

// layout

eval("\$header = \"" . $objTemplate->get("header") . "\";");

eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("configuration.end.php");
?>