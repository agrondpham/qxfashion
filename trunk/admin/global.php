<?php

$rolemanager_html = "";
require_once("../includes/role.account.php");

if ($CurrentSession->id > 0) {
// roles in menu
    
    $rolemanager = new account_roles("VIEW", "Role Manager");
    $boolean = $rolemanager->isAuthenticated();
    if ($rolemanager->isAuthenticated()) {
        $rolemanager_html = "<li><a href=\"/admin/role.manager.php\">Role Manager(Advanced)</a></li>";
    }
    $role_managepage = new account_roles("VIEW", "Pages Admin");
    if ($role_managepage->isAuthenticated()) {
        $rolemanager_html .= "<li><a href=\"/admin/page.php\">Pages Manager</a></li>";
    }
}
else
{

}
?>