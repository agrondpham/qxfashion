<?php

// show off: template
require_once("configuration.php");
require_once("includes/io.php");
require_once("includes/template.php");
require_once("includes/function.php");
require_once("includes/category.php");
// init variables
$categories = "";
$menu_rows = "";
require_once("includes/list.categories.php");

$errors = "";
$class_name = "";
$class_password = "";
$class_repassword = "";
$class_email = "";
$class_dob = "";
$class_shipaddress = "";
$class_register = "";
$repassword = "";
$customer_password = "";
// ACTIONs
if (isset($_POST['register'])) {

    $objCustomer = new customer();
    require_once("includes/customer.action.check.php");
    if (!isset($ErrorMessage)) {

        $objCustomer->create();
        require_once("includes/role.account.php");
        $ar = new account_roles();
        $ar->role_id = 3; // 3 is Site User Role
        $ar->customer_id = $objCustomer->id;
        $ar->create_account_role();

        if ($objCustomer->id > 0) {
            $errors = "you have registered successfully, <a href=\"/login.php\">click here</a> to login.";
        }
    } else {
        $class_register = $class_error;
        $errors .= "<ol style=\"padding: 6px 12px; margin-left: 10px; border: 1px solid #c60;\">";
        $i = 1;
        foreach ($ErrorMessage as $name => $value) {
            $errors .= "<li>{$i}. {$value}</li>";
            $i++;
        }
        $errors .= "</ol>";
    }
} else {
    $objCustomer = new customer();
}

$objTemplate = new template("default");
if ($CurrentSession->id < 1) {
    $customer_password = $objCustomer->get_password();
    eval("\$register = \"" . $objTemplate->get("register.content") . "\";");
}
else
    $register = "You have logged in already. Please <a href=\"/login.php?logout=1\">Log out</a> to register.";
$content = $register;

// cart
require_once("includes/function.number.php");
require_once("includes/product.php");
require_once("includes/cart.php");
require_once("includes/list.cart.php");
// Navigator
require_once("includes/page.php");
require_once("includes/list.menu.php");

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