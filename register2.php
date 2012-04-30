<?php

// show off: template
require_once("configuration.php");
require_once("includes/io.php");
require_once("includes/template.php");
require_once("includes/function.php");
// ACTIONs
if (isset($_POST['register'])) {
    
    $objCustomer = new customer();
    $objCustomer->name = (isset($_POST['name'])) ? sql_injection($_POST['name']) : NULL;
    $objCustomer->password = (isset($_POST['password'])) ? sql_injection($_POST['password']) : NULL;
    $objCustomer->email = (isset($_POST['email'])) ? sql_injection($_POST['email']) : NULL;
    $objCustomer->dob = (isset($_POST['dob'])) ? sql_injection($_POST['dob']) : NULL;
    $objCustomer->address = (isset($_POST['shipaddress'])) ? sql_injection($_POST['shipaddress']) : NULL;
    
    if($objCustomer->password != $_POST['repassword'])
    {
        $ErrorMessage[] = "Confirm password does not match with password";
    }
    
    //$customer->create();
}




$objTemplate = new template("default");

eval("\$register = \"" . $objTemplate->get("register") . "\";");
$main = $register;

eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$body = \"" . $objTemplate->get("body") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("configuration.end.php");
?>