<?php

require_once("../configuration.php");
require_once("global.php");
// show off: template
require_once("../includes/io.php");
require_once("../includes/template.php");
require_once("../includes/customer.php");
require_once("../includes/function.php");
//
$select = isset($_GET['select']) ? intval(sql_injection($_GET['select'])) : NULL;
$delete = isset($_GET['delete']) ? intval(sql_injection($_GET['delete'])) : NULL;
// initialization
$title = "Admin - Customer - Actions perform";
$objTemplate = new template("../admin");
$defaulttemplate = new template("../default");
$errors = "";
$class_name = "";
$class_password = "";
$class_repassword = "";
$class_email = "";
$class_dob = "";
$class_shipaddress = "";
$class_register = "";
$repassword = "";
$submit_button = "Create";
$customer_password = "";
// actions
// views
if ($delete != null && is_int($delete)) {
    $objCustomer = new customer();
    $objCustomer->id = $delete;
    $objCustomer->delete();
    header("location: customer.php");
} else
if ($select != null && is_int($select)) {
    $submit_button = "Update";
    $objCustomer = new customer();
    $objCustomer->id = $select;
    if (isset($_POST['register']) && $_POST['register'] == "Update") {
        
        $objCustomer->id = $select;
        $check_email = 0;
        require_once("../includes/customer.action.check.php");
        
        if (!isset($ErrorMessage)) {
            $customer_password = $objCustomer->get_password();
            $objCustomer->update();

            if ($objCustomer->id > 0) {
                $errors = "you have updated successfully, <a href=\"customer.php\">click here</a> to go back.";
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
        $objCustomer->get();
        $customer_password = $objCustomer->get_password();
        $repassword = $objCustomer->get_password();
    }
} else
if (isset($_POST['register']) && $_POST['register'] == "Create") {

    $objCustomer = new customer();
    require_once("../includes/customer.action.check.php");
    if (!isset($ErrorMessage)) {
        $customer_password = $objCustomer->get_password();
        $objCustomer->create();
        
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

eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
eval("\$content = \"" . $objTemplate->get("customer.action.content") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("../configuration.end.php");
?>