<?php

function sql_injection($value) {
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    //check if this function exists
    if (function_exists("mysql_real_escape_string")) {
        $value = mysql_real_escape_string($value);
    }
    else {
        $value = addslashes($value);
    }
    return $value;
}

function isNull($something)
{
    if($something == NULL)
        return true;
    return false;
}

?>