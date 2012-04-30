<?php

// discounted price function
function get_discount_price($original_price, $discount_percentage) {
    if ($discount_percentage > 0)
        return round($original_price * ( 100 - $discount_percentage ) / 100, 2);
    else
        return $original_price;
}

?>