<?php

$CurrentSession->page = sql_injection($_SERVER["REQUEST_URI"]);
$CurrentSession->update_session();


$mysql->c();

?>