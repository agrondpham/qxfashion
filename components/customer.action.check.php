<?php

$objCustomer->name = (isset($_POST['name'])) ? sql_injection($_POST['name']) : NULL;
if (isset($_POST['password'])) {
    $objCustomer->set_password(sql_injection($_POST['password']));
} else {
    $objCustomer->set_password(NULL);
}
$objCustomer->email = (isset($_POST['email'])) ? sql_injection($_POST['email']) : NULL;
$objCustomer->dob = (isset($_POST['dob'])) ? sql_injection($_POST['dob']) : NULL;
$objCustomer->shipaddress = (isset($_POST['shipaddress'])) ? sql_injection($_POST['shipaddress']) : NULL;
$class_error = "input_error";

if (isNull($objCustomer->name)) {
    $ErrorMessage[] = "Full Name is invalid";
    $class_name = $class_error;
} else {
    if (strlen($objCustomer->name) < 4) {
        $ErrorMessage[] = "Full Name is too short.";
        $class_name = $class_error;
    }
}

if (isNull($objCustomer->get_password())) {
    $ErrorMessage[] = "Password is invalid";
    $class_password = $class_error;
} else {
    if (strlen($objCustomer->get_password()) < 3) {
        $ErrorMessage[] = "Your password is too short. At least 3 characters above.";
        $class_password = $class_error;
        $class_repassword = $class_error;
    }
}

if (isNull($_POST['repassword'])) {
    $class_repassword = $class_error;
    $ErrorMessage[] = "Confirm password is empty";
} else {
    $repassword = $_POST['repassword'];
}

if ($objCustomer->get_password() != $_POST['repassword']) {
    $ErrorMessage[] = "Confirm password does not match with password";
    $class_repassword = $class_error;
    $class_password = $class_error;
} else {
    
}

// check from database for existence of customer
if ($objCustomer->get_password() != NULL && $objCustomer->email != NULL) {
    if ($objCustomer->has_customer_email() && !isset($check_email)) {
        $ErrorMessage[] = "The email address is already registed. Please use other email addresses";
        $class_email = $class_error;
    }
}

if (isNull($objCustomer->email)) {
    $ErrorMessage[] = "Email is invalid";
    $class_email = $class_error;
} else {
    if (!(preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $objCustomer->email))) {
        $ErrorMessage[] = "Email is invalid.";
        $class_email = $class_error;
    }
}

if (isNull($objCustomer->dob)) {
    $ErrorMessage[] = "Date of birth is invalid";
    $class_dob = $class_error;
} else {
    if (preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $objCustomer->dob, $regs)) {
        // correct format
    } else {
        $ErrorMessage[] = "Date of birth is invalid";
        $class_dob = $class_error;
    }
}

if (isNull($objCustomer->shipaddress)) {
    $ErrorMessage[] = "Shipping Address is invalid";
    $class_shipaddress = $class_error;
}
?>