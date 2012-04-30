<?php

// show off: template
require_once("configuration.php");
require_once("includes/function.php");
require_once("includes/io.php");
require_once("includes/template.php");
require_once("includes/category.php");
require_once("includes/product.php");

// action
$action = isset($_POST['action']) ? $_POST['action'] : NULL;
$class_error = "input_error";
$errors = "";
$class_name = "";
$class_shipaddress = "";
$class_newpassword = "";
$class_oldpassword = "";
$class_repassword = "";
$class_dob = "";

if ($action) {
    $objCustomer = new customer();
    $objCustomer->name = isset($_POST['name']) ? sql_injection($_POST['name']) : NULL;
    $objCustomer->shipaddress = isset($_POST['shipaddress']) ? sql_injection($_POST['shipaddress']) : NULL;
    $newpassword = isset($_POST['newpassword']) ? sql_injection($_POST['newpassword']) : NULL;
    $oldpassword = isset($_POST['oldpassword']) ? sql_injection($_POST['oldpassword']) : NULL;
    $repassword = isset($_POST['repassword']) ? sql_injection($_POST['repassword']) : NULL;
    $objCustomer->dob = isset($_POST['dob']) ? sql_injection($_POST['dob']) : NULL;
    $objCustomer->set_password($oldpassword);
    if (isNull($objCustomer->name)) {
        $ErrorMessage[] = "Full name is not valid";
        $class_name = $class_error;
    }

    if (isNull($objCustomer->dob)) {
        $ErrorMessage[] = "date of birth is not valid";
        $class_dob = $class_error;
    }

    if (isNull($oldpassword)) {
        $ErrorMessage[] = "Old password is required.";
        $class_oldpassword = $class_error;
    } else {

        if ($oldpassword != $CurrentSession->get_password()) {
            $ErrorMessage[] = "Old password is not matched.";
            $class_oldpassword = $class_error;
        }
    }
    
    if(isNull($objCustomer->shipaddress))
    {
        $ErrorMessage[] = "Your ship address is empty";
        $class_shipaddress = $class_error;
    }

    if (!isNull($newpassword)) {
        if (strlen($newpassword) > 0 && $newpassword != $repassword) {
            $ErrorMessage[] = "New password and repassword is not the same.";
            $class_newpassword = $class_error;
        }
    }

    if (!isset($ErrorMessage)) {
        if (!isNull($newpassword)) {
            if (strlen($newpassword) > 0) {
                $objCustomer->set_password($newpassword);
            }
        }
        $CurrentSession->name = $objCustomer->name;
        $CurrentSession->dob = $objCustomer->dob;
        $CurrentSession->set_password($objCustomer->get_password());
        $CurrentSession->shipaddress = $objCustomer->shipaddress;
        $CurrentSession->update();
        $_SESSION['customer'] = $CurrentSession;
        $errors = "<div id=\"notice\">You have updated your profile successfully.</div>";
    } else {
        $errors = "";
        $errors .= "<ol id=\"notice\">";
        $i = 1;
        foreach ($ErrorMessage as $name => $value) {
            $errors .= "<li>{$i}. {$value}</li>";
            $i++;
        }
        $errors .= "</ol><br/>";
    }
}

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

eval("\$body = \"" . $objTemplate->get("profile.update.content") . "\";");

// layout

eval("\$header = \"" . $objTemplate->get("header") . "\";");

eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("configuration.end.php");
?>