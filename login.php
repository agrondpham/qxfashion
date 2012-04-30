<?php

// show off: template
require_once("configuration.php");
require_once("includes/io.php");
require_once("includes/template.php");
require_once("includes/function.php");
require_once("includes/category.php");

// INIT VARIABLES
$categories = "";

require_once("includes/list.categories.php");
$menu_rows = "";
$errors = "";
$class_error = "input_error";
$class_email = "";
$class_password = "";
$class_login = "";

$objCustomer = new customer();
// ACTIONs
// login
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    $CurrentSession->delete_session();
    $CurrentSession->connection->close();
    session_destroy();
    header("HTTP/1.1 301 Moved Permanently");
    header("location: login.php?" . time());
    exit();
} else
if (isset($_POST['login'])) {

    $objCustomer->email = (isset($_POST['email'])) ? sql_injection($_POST['email']) : NULL;
    $objCustomer->set_password((isset($_POST['password'])) ? sql_injection($_POST['password']) : NULL);

    if ($objCustomer->email == NULL) {
        $ErrorMessage[] = "Email Address is empty";
        $class_email = $class_error;
    }

    if ($objCustomer->get_password() == NULL) {
        $ErrorMessage[] = "Password is empty";
        $class_password = $class_error;
    }

    if (!isset($ErrorMessage)) {
        $customer_query = $objCustomer->get_customer_by_email_password();

        if ($objCustomer->id > 0) {

            $CurrentSession->set_object($objCustomer);
            $CurrentSession->connection->connect();
            $_SESSION['customer'] = $CurrentSession;
            
        } else {
            $class_email = $class_error;
            $class_password = $class_error;
            $errors = "Please check your username or password. the information is incorrect.";
        }
    } else {
        $class_login = $class_error;
        $errors .= "<ol style=\"padding: 6px 12px; margin-left: 10px; border: 1px solid #c60;\">";
        $i = 1;
        foreach ($ErrorMessage as $name => $value) {
            $errors .= "<li>{$i}. {$value}</li>";
            $i++;
        }
        $errors .= "</ol>";
    }
}

$objTemplate = new template("default");

if ($CurrentSession->id < 1)
    eval("\$login = \"" . $objTemplate->get("login.content") . "\";");
else
    $login = "You've logged in as {$CurrentSession->name}, <a href=\"login.php?logout=1\">Logout</a>";

$content = $login;

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
eval("\$body = \"" . $objTemplate->get("body") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("configuration.end.php");
?>