<?php

require_once("../configuration.php");
require_once("global.php");


// show off: template
require_once("../includes/io.php");
require_once("../includes/template.php");
require_once("../includes/function.php");
require_once("../includes/role.account.php");
$title = "Admin - Role Manager - User Privileges";
$objTemplate = new template("../admin");
$defaulttemplate = new template("../default");


$roles_td = "";

if (isset($_GET['email'])) {
    $action = $_GET['action'];
    if ($action == "account_privileges" && isset($_GET['email']) && $_GET['email'] != "") {
        $email = sql_injection($_GET['email']);
        $account = new customer();
        $account->email = $email;
        if ($account->get_customer_by_email()) {
            $ar = new account_roles();
            $ar->customer_id = $account->id;

            $account_privilege_queries = $ar->get_account_privileges();
            while ($r = $mysql->fo($account_privilege_queries)) {
                eval("\$roles_td .=\"" . $objTemplate->get("role.user.privilege.rows") . "\";");
            }
        }
        else
            $roles_td = "<tr><td colspan=\"8\" class=\"cell\">None</td></tr>";
    }
}
else {
    $Username = "User";
    $roles_td = "<tr><td colspan=\"8\" class=\"cell\">Search results will appear here</td></tr>";
}

$account_roles = new account_roles();
$ar_queries = $account_roles->get_accounts_roles();
$accounts_list = "";
while ($ar = $mysql->fo($ar_queries)) {
    if ($ar->email == NULL) {
        $ar->email = "Unknown";
    }
    $accounts_list .= "<tr><td class=\"cell\"><a href=\"role.user.privilege.php?action=account_privileges&email={$ar->email}\">{$ar->email}</td><td class=\"cell\">{$ar->RoleName}</td></tr>";
}

eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
eval("\$content = \"" . $objTemplate->get("role.user.privilege.content") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("../configuration.end.php");
?>