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
 * Ag
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

//Include object of database
require_once("libraries/tables/category.php");
require_once("libraries/tables/product.php");
require_once("libraries/tables/page.php");
require_once("libraries/tables/cart.php");
require_once("libraries/tables/comment.php");

/* ======================================================================
 *                  Variable delarce
 * ======================================================================
 */
$title = "Qx Fashion Brand|Product";

// Variable Declare
$id = (isset($_GET['id'])) ? intval(sql_injection($_GET['id'])) : 0;
$type= (isset($_GET['type'])) ? intval(sql_injection($_GET['type'])) : 0;
$categories = "";
$product_rows = "";
$menu_rows = "";


// Object Declare
$objProduct = new Product();
$objComment = new Comment();

/* ======================================================================
 *                  Render
 * ======================================================================
 */
// check id is integer
if (is_int($id) && $id > 0) {
// initialization
    $objTemplate = new template("default");
    $objProduct->id = $id;
    if($type==-1)
        $objProduct->getnews() ;
    else
        $objProduct->get();
    if ($objProduct->id > 0) {
        $category_id = $objProduct->category_id;
        if ($objProduct->discount > 0) {
            $discount_price = get_discount_price($objProduct->price, $objProduct->discount);
            $objProduct->price = "<del>{$product_object->price}$</del> {$discount_price}$";
        } else {
            $objProduct->price = $objProduct->price . "$";
        }
    }
// Comment section
    $query_comment = $objComment->get_comments($id);
    while($obj = $mysql->fo($query_comment)){
        $objComment = Comment::static_set_object($obj);
        eval("\$comment_object .= \"" . $objTemplate->get("comment") . "\";");
    }
    
// Categories section
    require_once("includes/list.categories.php");
    //require_once("includes/list.cart.php");

// Navigator
    //require_once("includes/list.menu.php");

    eval("\$content = \"" . $objTemplate->get("product.content") . "\";");
    
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