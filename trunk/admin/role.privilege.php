<?php

require_once("../configuration.php");
require_once("global.php");


// show off: template
require_once("../includes/io.php");
require_once("../includes/template.php");
require_once("../includes/roles.privileges.php");
require_once("../includes/function.php");
$title = "Admin - Roles";
$objTemplate = new template("../admin");
$defaulttemplate = new template("../default");



$create = isset($_POST['create']) ? $_POST['create'] : NULL;
$assign = isset($_POST['assign']) ? $_POST['assign'] : NULL;
if ($create == "privilege") {
    $Name = sql_injection($_POST['Name']);
    $Description = sql_injection($_POST['Description']);
    $isSingular = sql_injection($_POST['isSingular']);

    $privilege = new roles_privileges();
    $privilege->PrivilegeName = $Name;
    $privilege->PrivilegeDescription = $Description;
    $privilege->PrivilegeisSingular = $isSingular;
    $privilege->create_privilege();

    if ($privilege->PrivilegeisSingular == "Yes") {
        $privilege->PrivilegeID = $privilege->mysql->iid();
        $privilege->ActionName = $privilege->PrivilegeName;
        $privilege->ActionDescription = $privilege->PrivilegeDescription;
        $privilege->create_action();
        $privilege->ActionID = $privilege->mysql->iid();
        $privilege->create_privilege_action();
    }

    header("location: role.privilege.php?");
} elseif ($create == "action") {
    $Name = sql_injection($_POST['Name']);
    $Description = sql_injection($_POST['Description']);
    $privilege = new roles_privileges();
    $privilege->ActionName = $Name;
    $privilege->ActionDescription = $Description;
    $privilege->create_action();
    header("location: role.privilege.php?");
} elseif ($assign == "action") {
    $privilege = new roles_privileges();
    $privilege->ActionID = sql_injection($_POST['ActionID']);
    $privilege->PrivilegeID = sql_injection($_POST['PrivilegeID']);
    $privilege->create_privilege_action();
    header("location: role.privilege.php?");
}

$privilege = new roles_privileges();
$privilege_queries = $privilege->get_privileges();
$privilege_list = "";
$privilege_option_list = "";
while ($privilege_object = $mysql->fo($privilege_queries)) {
    $privilege_list .= "
		<tr>
			<td class=\"cell\">{$privilege_object->Name}</td>
			<td class=\"cell\">{$privilege_object->Description}</td>
			<td class=\"cell\">{$privilege_object->isSingular}</td>
		</tr>
	";
    $privilege_option_list .= "<option value=\"{$privilege_object->ID}\">{$privilege_object->Name} - {$privilege_object->isSingular}</option>";
}

$privilege = new roles_privileges();
$privileges_objects_queries = $privilege->get_privileges_actions();
$privileges_objects_list = "";
while ($privileges_objects = $mysql->fo($privileges_objects_queries)) {
    $privileges_objects_list .= "
		<tr>
			<td class=\"cell\">{$privileges_objects->PrivilegeName}</td>
			<td class=\"cell\">{$privileges_objects->ActionName}</td>
			<td class=\"cell\">{$privileges_objects->isSingular}</td>
		</tr>
	";
}

$privilege = new roles_privileges();
$action_queries = $privilege->get_actions();
$action_list = "";
$action_option_list = "";
while ($action = $mysql->fo($action_queries)) {
    $action_list .= "
		<tr>
			<td class=\"cell\">{$action->Name}</td>
			<td class=\"cell\">{$action->Description}</td>
		</tr>
	";
    $action_option_list .= "<option value=\"{$action->ID}\">{$action->Name}</option>";
}

eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
eval("\$content = \"" . $objTemplate->get("role.privilege.content") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("../configuration.end.php");
?>