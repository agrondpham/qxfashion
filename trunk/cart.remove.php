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

$id = isset($_GET['id']) ? intval(sql_injection($_GET['id'])) : 0;
/* ======================================================================
 *                  Render
 * ======================================================================
 */
if (is_int($id) && $id > 0) {
    $objCart = new customers_cart();
    $objCart->session_id = $CurrentSession->session_id;
    $objCart->product_id = $id;
    $objCart->get();
    $objCart->status = cart_status::Deleted;
    $objCart->quantity = 0;
    $objCart->update();
    $mysql->c();
    header("HTTP/1.1 301 Moved Permanently");
    header("location: cart.edit.php");
    exit();
}
require_once("includes/configuration.end.php");
?>