<?php

$cart_product_rows = "";
$total = 0.00;
$objCart = new customers_cart();
$objCart->session_id = $CurrentSession->session_id;
$cart_queries = $objCart->get_session_cart();

while ($obj = $mysql->fo($cart_queries)) {
    $objCart = customers_cart::static_set_object($obj);
    $product = product::get_product($objCart->product_id);

    if ($product->discount > 0) {
        $discount_price = get_discount_price($product->price, $product->discount);
        $total += ($discount_price*$objCart->quantity);
        $product->price = "<!--<del>{$product->price}$</del> -->{$discount_price}$";
    } else {
        $total += ($product->price*$objCart->quantity);  
    }
    eval("\$cart_product_rows .= \"" . $objTemplate->get("body.cart.rows") . "\";");
}
?>