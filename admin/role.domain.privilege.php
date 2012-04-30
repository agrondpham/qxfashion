<?php

require_once("../configuration.php");
require_once("global.php");


// show off: template
require_once("../includes/io.php");
require_once("../includes/template.php");
require_once("../includes/roles.php");
require_once("../includes/roles.domains.privileges.php");
require_once("../includes/function.php");
$title = "Admin - Role Manager - Role Domain Privilege";
$objTemplate = new template("../admin");
$defaulttemplate = new template("../default");
$domains_privileges_list = "";
$role_option_list = "";
$domain_option_list = "";
$privilege_option_list = "";
$account_roles_rows = "";



$create = isset($_POST['create'])? sql_injection($_POST['create']):NULL;
if ($create == "permission") {
    $role = new roles();
    $role->RoleID = $_POST['RoleID'];
    $role->DomainID = $_POST['DomainID'];
    $role->PrivilegeID = $_POST['PrivilegeID'];
    $role->isAllowed = $_POST['isAllowed'];
    if ($role->RoleID && $role->DomainID && $role->PrivilegeID && $role->isAllowed)
        $role->create_domain_privilege();
    header("location: role.domain.privilege.php?#created_permission");
}
elseif ($create == "role") {
    $r = new roles();
    $r->RoleName = sql_injection($_POST['RoleName']);
    $r->RoleDescription = sql_injection($_POST['RoleDescription']);
    $r->RoleImportance = sql_injection($_POST['RoleImportance']);

    if (!$r->RoleName || !$r->RoleDescription || !$r->RoleImportance) {
        header("location: role.domain.privilege.php?#created_role");
    } else {
        $r->create_role();
    }
}
$r = new roles();
$r_queries = $r->get_roles();
$account_roles_rows = "";
while ($r_objects = $mysql->fo($r_queries)) {
    $role_option_list .= "
		<option value=\"{$r_objects->RoleID}\">{$r_objects->RoleName}</option>
	";
    $account_roles_rows .= "<tr><td class=\"cell\">{$r_objects->RoleID}</td><td class=\"cell\">{$r_objects->RoleName}</td>
								<td class=\"cell\">{$r_objects->RoleDescription}</td><td class=\"cell\">{$r_objects->RoleImportance}</td></tr>";
}
$r_domains_privileges_queries = $r->get_domains_privileges();
$domains_privileges_list = "";
while ($dp_objects = $mysql->fo($r_domains_privileges_queries)) {
    $domains_privileges_list .= "
		<tr>
			<td class=\"cell\">{$dp_objects->RoleName}</td>
			<td class=\"cell\">{$dp_objects->DomainName}</td>
			<td class=\"cell\">{$dp_objects->PrivilegeName}</td>
			<td class=\"cell\">{$dp_objects->isAllowed}</td>
			<td class=\"cell\">{$dp_objects->Importance}</td>
		</tr>
	";
}

$privilege = new roles_privileges();
$privilege_queries = $privilege->get_privileges();
$privilege_list = "";
while ($privilege_object = $mysql->fo($privilege_queries)) {
    $privilege_option_list .= "<option value=\"{$privilege_object->ID}\">{$privilege_object->Name} - {$privilege_object->isSingular}</option>";
}

$domain = new roles_domains();
$domain_queries = $domain->get_domains();
$domain_list = "";
while ($domain_object = $mysql->fo($domain_queries)) {
    $domain_option_list .= "<option value=\"{$domain_object->ID}\">{$domain_object->Name}</option>";
}
eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
eval("\$content = \"" . $objTemplate->get("role.domain.privilege.content") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("../configuration.end.php");
?>