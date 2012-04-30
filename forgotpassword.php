<?php

// show off: template
require_once("configuration.php");
require_once("includes/io.php");
require_once("includes/template.php");
require_once("includes/function.php");
require_once("includes/category.php");


// INIT VARIABLES
$categories = "";
$menu_rows = "";
$email = "";
$errors = "";
$class_error = "input_error";
$class_request = "";
$class_email = "";
$objTemplate = new template("default");

// ACTIONS
if (isset($_POST['forgotpassword'])) {
    $email = (isset($_POST['email'])) ? sql_injection($_POST['email']) : NULL;

    if ($email == NULL) {
        $ErrorMessage[] = "Email is empty.";
        $class_email = $class_error;
    } else
    if (!(preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $email))) {
        $ErrorMessage[] = "Email is invalid.";
        $class_email = $class_error;
    }

    if (!isset($ErrorMessage)) {
        // send email
    } else {
        $class_request = $class_error;
        $errors .= "<ol style=\"padding: 6px 12px; margin-left: 10px; border: 1px solid #c60;\">";
        $i = 1;
        foreach ($ErrorMessage as $name => $value) {
            $errors .= "<li>{$i}. {$value}</li>";
            $i++;
        }
        $errors .= "</ol>";
    }
}


require_once("includes/list.categories.php");

// cart
require_once("includes/function.number.php");
require_once("includes/product.php");
require_once("includes/cart.php");
require_once("includes/list.cart.php");

// Navigator
require_once("includes/page.php");
require_once("includes/list.menu.php");
// Layout
eval("\$content = \"" . $objTemplate->get("forgotpassword.content") . "\";");
eval("\$cart = \"" . $objTemplate->get("body.cart") . "\";");
eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$body = \"" . $objTemplate->get("body") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("configuration.end.php");
?>