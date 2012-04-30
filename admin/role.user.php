<?php

require_once("../configuration.php");
require_once("global.php");


// show off: template
require_once("../includes/io.php");
require_once("../includes/template.php");
require_once("../includes/role.account.php");
require_once("../includes/function.php");
$title = "Admin - Role Manager - Role User";
$objTemplate = new template("../admin");
$defaulttemplate = new template("../default");


$ar = new account_roles();
$action = isset($_POST['action']) ? sql_injection($_POST['action']) : NULL;

$account_roles_rows = "";
$msg = "";

if ($action == "assignAccount") {
    $RoleID = $_POST['RoleID'];
    $email = $_POST['email'];
    $account = new customer();
    $account->email = $email;
    if ($account->get_customer_by_email()) {
        $ar->RoleID = $RoleID;
        $ar->customer_id = $account->id;
        $ar->get_role();
        if ($ar->hasRole()) {
            $msg = "You have assigned account {$account->email} to have Role {$ar->RoleName}";
        } else {
            $ar->role_id = $ar->RoleID;
            $ar->create_account_role();
            $msg = "You have just assigned account {$account->email} to have Role {$ar->RoleName}";
        }
    }
    else
        $msg = "No username has been assigned";
}
$ar_queries = $ar->get_accounts_roles();
while ($r = $mysql->fo($ar_queries)) {
    eval("\$account_roles_rows .=\"" . $objTemplate->get("role.user.rows") . "\";");
}
$role_queries = $ar->get_roles();
$role_options = "";
while ($r = $mysql->fo($role_queries)) {
    $role_options .= "<option value=\"{$r->RoleID}\">{$r->RoleName}</option>";
}

eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
eval("\$content = \"" . $objTemplate->get("role.user.content") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("../configuration.end.php");
?>