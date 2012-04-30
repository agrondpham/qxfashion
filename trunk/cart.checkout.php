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
require_once("components/com_helper/function.number.php");
require_once("components/com_io/io.php");
require_once("components/com_template/template.php");
require_once("components/com_pagination/pagination.php");

//Include object of database
require_once("libraries/tables/category.php");
require_once("libraries/tables/product.php");
require_once("libraries/tables/page.php");
require_once("libraries/tables/setting.php");
require_once("libraries/tables/cart.php");
require_once("libraries/tables/order.php");
require_once("libraries/tables/order_product.php");
require_once("libraries/tables/creditcard.php");
/* ======================================================================
 *                  Variable delarce
 * ======================================================================
 */
$title = "Qx Fashion Brand|Shopping cart";
// action 
$action = isset($_POST['action']) ? $_POST['action'] : NULL;
$errors = "";
$class_error = "input_error";
$class_shipaddress = "";
$class_name = "";
$class_card_type = "";
$class_card_number = "";
$class_card_owner_name = "";
$class_card_month = "";
$class_card_year = "";
//$categories = "";
//$menu_rows = "";
//$cart_edit_rows = "";
$total = 0.00;
$month = "";
$year = "";
// Declare Object
$objTemplate = new template("default");
$objCart = new customers_cart();
$objCustomer = new customer();
$objCreditcard = new customers_creditcards();
$objCart = new customers_cart();
/* ======================================================================
 *                  Render
 * ======================================================================
 */
if ($action == "Process Payment") {
    
    $objCustomer->name = isset($_POST['name']) ? sql_injection($_POST['name']) : NULL;
    $objCustomer->shipaddress = isset($_POST['shipaddress']) ? sql_injection($_POST['shipaddress']) : NULL;
    $card_type = isset($_POST['card_type']) ? sql_injection($_POST['card_type']) : NULL;
    $card_number = isset($_POST['card_number']) ? sql_injection($_POST['card_number']) : NULL;
    $card_owner_name = isset($_POST['card_owner_name']) ? sql_injection($_POST['card_owner_name']) : NULL;
    $card_month = isset($_POST['card_month']) ? sql_injection($_POST['card_month']) : NULL;
    $card_year = isset($_POST['card_year']) ? sql_injection($_POST['card_year']) : NULL;
    $total = isset($_POST['total']) ? sql_injection($_POST['total']) : NULL;

    if (isNull($objCustomer->name)) {
        $ErrorMessage[] = "Owner name is not valid";
        $class_name = $class_error;
    }

    if (isNull($objCustomer->shipaddress)) {
        $ErrorMessage[] = "Shipping address is not valid";
        $class_shipaddress = $class_error;
    }

    if (isNull($card_type)) {
        $ErrorMessage[] = "Credit card type is not valid";
        $class_card_type = $class_error;
    }

    if (isNull($card_number)) {
        $ErrorMessage[] = "Credit card number is not valid";
        $class_card_number = $class_error;
    } else {
        $card_number = str_replace(" ", "", $card_number);
    }

    if (isNull($card_owner_name)) {
        $ErrorMessage[] = "Credit card owner name is not valid";
        $class_card_owner_name = $class_error;
    }

    if (isNull($card_month)) {
        $ErrorMessage[] = "Card expired month is not valid";
        $class_card_month = $class_error;
    }

    if (isNull($card_year)) {
        $ErrorMessage[] = "Card expired year is not valid";
        $class_card_year = $class_error;
    }


    if (preg_match('/^(?:[4-9]{1}[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6011[0-9]{12}|3(?:0[0-5]|[68][0-9])[0-9]{11}|3[47][0-9]{13})$/', $card_number)) {
        // ok
    } else {
        $ErrorMessage[] = "Your credit card number is not valid " . $card_number;
        $class_card_number = $class_error;
    }

    if (!isset($ErrorMessage)) {

        $objCreditcard->customer_id = $CurrentSession->id;
        $objCreditcard->name = $card_type;
        $objCreditcard->number = $card_number;
        $objCreditcard->owner_name = $card_owner_name;
        $objCreditcard->month = $card_month;
        $objCreditcard->year = $card_year;

        $objCreditcard->create();

        $order = new order();
        $order->session_id = $CurrentSession->session_id;
        $order->customer_id = $CurrentSession->id;
        $order->address = $objCustomer->shipaddress;
        $order->payment_amount = $total;
        $order->payment_type = $card_type;
        $order->name = $objCustomer->name;
        $order->shipaddress = $objCustomer->shipaddress;
        $order->status = order_status::Active;
        $order->order_date = date("Y-m-d H:i:s", time());
        $order->customer_creditcard_id = $objCreditcard->id;
        $order->create();


        $objCart->session_id = $CurrentSession->session_id;
        $cart_queries = $objCart->get_session_cart();
        $total = 0.00;
        while ($obj = $mysql->fo($cart_queries)) {
            $objCart = customers_cart::static_set_object($obj);
            $objProduct = product::get_product($objCart->product_id);
            $original_price = $objProduct->price;
            
            if ($objProduct->discount > 0) {
                $discount_price = get_discount_price($objProduct->price, $objProduct->discount);
                $total += $discount_price * $objCart->quantity;
                $objProduct->price = "{$discount_price}";
                $product_total_price = $discount_price * $objCart->quantity;
            } else {
                $total += $objProduct->price * $objCart->quantity;
                $product_total_price = $objProduct->price * $objCart->quantity;
            }

            $order_product = new order_product();
            $order_product->order_id = $order->order_id;
            $order_product->product_id = $objProduct->id;
            $order_product->quantity = $objCart->quantity;
            $order_product->subtotal = $product_total_price;
            $order_product->create();
            
            // update stock after purchases
            $objProduct->stock = $objProduct->stock - $objCart->quantity;
            $objProduct->update_stock();
            
        }

        $objCart = new customers_cart();
        $objCart->session_id = $CurrentSession->session_id;
        $objCart->delete_session_cart();

        $mysql->c();
        header ('HTTP/1.1 301 Moved Permanently');
        header("location: order.view.php");
        exit();
    } else {
        $errors = "";
        $errors .= "<ol style=\"padding: 6px 12px; margin-left: 10px; border: 1px solid #c60;\">";
        $i = 1;
        foreach ($ErrorMessage as $name => $value) {
            $errors .= "<li>{$i}. {$value}</li>";
            $i++;
        }
        $errors .= "</ol>";
    }
}


// Categories section
require_once("includes/list.categories.php");



$objCart->session_id = $CurrentSession->session_id;
$cart_queries = $objCart->get_session_cart();



for ($i = 1; $i <= 12; $i++) {
    $month .= "<option value=\"{$i}\">{$i}</option>";
}

for ($i = 2012; $i <= 2050; $i++) {
    $year .= "<option value=\"{$i}\">{$i}</option>";
}

while ($obj = $mysql->fo($cart_queries)) {
    $objCart = customers_cart::static_set_object($obj);
    $objProduct = product::get_product($objCart->product_id);
    $original_price = $objProduct->price;

    if ($objProduct->discount > 0) {
        $discount_price = get_discount_price($objProduct->price, $objProduct->discount);
        $total += $discount_price * $objCart->quantity;
        $objProduct->price = "{$discount_price}";
        $product_total_price = $discount_price * $objCart->quantity;
    } else {
        $total += $objProduct->price * $objCart->quantity;
        $product_total_price = $objProduct->price * $objCart->quantity;
    }
    eval("\$cart_edit_rows .= \"" . $objTemplate->get("cart.edit.preview.rows") . "\";");
}

eval("\$cart_edit_form = \"" . $objTemplate->get("cart.edit.preview") . "\";");
// Navigator
require_once("includes/list.menu.php");
// layout


eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$body = \"" . $objTemplate->get("cart.checkout.content") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("includes/configuration.end.php");
?>