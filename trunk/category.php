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
require_once("libraries/tables/comment.php");
require_once("libraries/tables/setting.php");

/* ======================================================================
 *                  Variable delarce
 * ======================================================================
 */
$title = "Qx Fashion Brand|Category";

// 
$id = isset($_GET['id']) ? intval(sql_injection($_GET['id'])) : 0;
$page_id = isset($_GET['page']) ? intval(sql_injection($_GET['page'])) : 0;
    
// variable initializationu
$categories = "";
$product_rows = "";
$menu_rows = "";

/* ======================================================================
 *                  Render
 * ======================================================================
 */

if (is_int($id) && $id >= 0) {
    $category_id = $id;
    $objTemplate = new template("default");
    $objSetting = new setting();
    
// PRODUCT SECTION
    $objProduct = new Product();
    $objProduct->category_id = $id;
    $objProduct->limit = $objSetting->product_limit;
    $total_products = $objProduct->get_total_products_category();
// required pagination

    $objPagination = new pagination("index.php?page=", $page_id, $total_products, $objProduct->limit);
// set the start to get products    
    $objProduct->start = $objPagination->start;
    if($id==0)
        $query_products = $objProduct->get_products();
    else $query_products = $objProduct->get_products_category();
    $pagination_html = $objPagination->get_html();

    if ($mysql->n($query_products) > 0) {
        while ($obj = $mysql->fo($query_products)) {
            
            if ($obj->discount > 0) {
                $discount_price = get_discount_price($obj->price, $obj->discount);
                $obj->price = "<del>{$obj->price}$</del> {$discount_price}$";
            } else {
                $obj->price = $obj->price . "$";
            }
            $objProduct = Product::static_set_object($obj);
            eval("\$product_rows .= \"" . $objTemplate->get("product.rows") . "\";");
        }
    } else {
        eval("\$product_rows = \"" . $objTemplate->get("product.empty") . "\";");
    }
    eval("\$products = \"{$product_rows}\";"); 
    
// Categories section    
    require_once("includes/list.categories.php");
// cart
    require_once("includes/list.cart.php");
// Navigator
    require_once("includes/list.menu.php");

// layout
    //AG: change content to category content
    eval("\$content = \"" . $objTemplate->get("category.content") . "\";");

    eval("\$header = \"" . $objTemplate->get("header") . "\";");
    eval("\$cart = \"" . $objTemplate->get("body.cart") . "\";");
    eval("\$body = \"" . $objTemplate->get("body") . "\";");
    eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
    eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
    echo ($index); // can use echo $template->compress($index) to compress size of html

    require_once("includes/configuration.end.php");
}
?>