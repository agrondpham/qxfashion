<?php

require_once("../configuration.php");

require_once("global.php");
$role = new account_roles("VIEW", "Homepage Admin");

if ($role->isAuthenticated()) {
// show off: template
    require_once("../includes/function.php");
    require_once("../includes/io.php");
    require_once("../includes/template.php");
    
    $title = "Admin - Home";
    $objTemplate = new template("../admin");
    $defaulttemplate = new template("../default");
    $role_rows = "";
    $roles_td = "";
    
    // menu
    eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
    eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
    
    // roles
    $role_queries = $role->get_account_roles();
    while ($role_object = $mysql->fo($role_queries)) {
        eval("\$role_rows .= \"" . $objTemplate->get("index.role.rows") . "\";");
    }
    
    // roles privileges
    $account_privilege_queries = $role->get_account_privileges();
    while ($r = $mysql->fo($account_privilege_queries)) {
        eval("\$roles_td .=\"" . $objTemplate->get("index.user.role.privilege.rows") . "\";");
    }

    eval("\$header = \"" . $objTemplate->get("header") . "\";");
    eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
    eval("\$content = \"" . $objTemplate->get("index.content") . "\";");
    eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
    eval("\$index = \"" . $objTemplate->get("index") . "\";");

    // display all
    echo ($index); // can use echo $template->compress($index) to compress size of html

    require_once("../configuration.end.php");
} else {
    $mysql->c();
    header("HTTP/1.1 301 Moved Permanently");
    header("location: login.php");
}
?>