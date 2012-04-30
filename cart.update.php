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

require_once("libraries/tables/cart.php");
/* ======================================================================
 *                  Variable delarce
 * ======================================================================
 */
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : NULL;
$submit = isset($_POST['submit']) ? $_POST['submit'] : NULL;
/* ======================================================================
 *                  Render
 * ======================================================================
 */
if($submit == "Checkout")
{
    $mysql->c();
    header("HTTP/1.1 301 Moved Permanently");
    header("location: cart.checkout.php");
    exit();
}
if (is_array($quantity) && count($quantity) > 0) {
    $objCart = new customers_cart();
    $objCart->session_id = $CurrentSession->session_id;
    $objCart->status = cart_status::Active;
    
    foreach($quantity as $product_id => $value)
    {
        $objCart->product_id = $product_id;
        $objCart->quantity = $value;
        $objCart->update();
    }
    
    $mysql->c();
    header("HTTP/1.1 301 Moved Permanently");
    header("location: cart.php");
    exit();
}
require_once("includes/configuration.end.php");
?>