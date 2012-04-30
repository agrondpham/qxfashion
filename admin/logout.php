<?php

require_once("../configuration.php");
require_once("../includes/function.php");
require_once("global.php");
$role = new account_roles("VIEW", "Homepage Admin");

if ($role->isAuthenticated()) {
    $CurrentSession->connection = $mysql;
    $CurrentSession->delete_session();
    $mysql->c();
    session_destroy();
    header("HTTP/1.1 301 Moved Permanently");
    header("location: login.php");
} else {
    require_once("../configuration.end.php");
    header("HTTP/1.1 301 Moved Permanently");
    header("location: ".$_SERVER["HTTP_REFERER"]);
}
?>