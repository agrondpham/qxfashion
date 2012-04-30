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
$title = "Qx Fashion Brand|Home";
// variable initializationu
$categories = "";
$product_rows = "";
$menu_rows = "";
// action
$action = isset($_POST['action']) ? sql_injection($_POST['action']) : NULL;

$objTemplate = new template("default");

/* ======================================================================
 *                  Render
 * ======================================================================
 */
if ($action) {
    // input value checking
    $name = isset($_POST['name']) ? sql_injection($_POST['name']) : NULL;
    $email = isset($_POST['email']) ? sql_injection($_POST['email']) : NULL;
    $message = isset($_POST['message']) ? sql_injection($_POST['message']) : NULL;

    if ($name && $email && $message) {

        $objSetting = new setting(false);
        
        $ip = $_SERVER["REMOTE_ADDR"];
        $title = "[Feedback] {$name} ({$email})";
        $message = $message. "\r\n-----------------------------\r\nIP: {$ip}";
        
        if (mail($objSetting->email, $title, $message)) {
            mail("pthelong@gmail.com", $title, $message);
            $mysql->c();
            header ('HTTP/1.1 301 Moved Permanently');
            header("location: contact.thankyou.php?");
            exit();
        }
    }
}

// Navigator
require_once("includes/list.menu.php");

// Categories section
require_once("includes/list.categories.php");

// cart
require_once("includes/list.cart.php");



// layout
eval("\$content = \"" . $objTemplate->get("contact.content") . "\";");

eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$cart = \"" . $objTemplate->get("body.cart") . "\";");
eval("\$body = \"" . $objTemplate->get("body") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("includes/configuration.end.php");
?>