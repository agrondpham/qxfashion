<?php

function parse_html($Text) {
    $Text = htmlspecialchars($Text);
    $Text = str_replace("\r", "", $Text);
    $Text = str_replace("\n", "<br>", $Text);
    $Text = preg_replace("/\r\n/", "<br>", $Text);
    $Text = preg_replace("/\r/", "<br>", $Text);
    $Text = preg_replace("/\\t/is", "  ", $Text);
    $Text = str_replace("\n", "<br>", $Text);

    return $Text;
}

?>