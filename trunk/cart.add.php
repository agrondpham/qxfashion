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
/* ======================================================================
 *                  Variable delarce
 * ======================================================================
 */
$id = (isset($_GET['id'])) ? intval(sql_injection($_GET['id'])) : 0;
/* ======================================================================
 *                  Render
 * ======================================================================
 */
// check id is integer
if (is_int($id) && $id > 0) {
    require_once("libraries/tables/cart.php");
    $objCart = new customers_cart();
    $objCart->customer_id = $CurrentSession->customer_id;
    $objCart->session_id  = $CurrentSession->session_id;
    $objCart->product_id = $id;
    $objCart->quantity = 1;
    $objCart->status = cart_status::Active;
    $objCart->create();
   
    $mysql->c();
    header("location: ".$_SERVER["HTTP_REFERER"]."#{$id}");
    exit();
}
require_once("includes/configuration.end.php");
?>