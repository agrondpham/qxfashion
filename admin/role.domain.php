<?php

require_once("../configuration.php");
require_once("global.php");
// show off: template
require_once("../includes/io.php");
require_once("../includes/template.php");
require_once("../includes/function.php");
require_once("../includes/roles.domains.php");


// INITIALIZATION
$title = "Admin - Role Manager - Role Domain";
$objTemplate = new template("../admin");
$defaulttemplate = new template("../default");
$domain_option_list = "";
$object_option_list = "";
$create = isset($_POST['create']) ? sql_injection($_POST['create']) : NULL;
$assign = isset($_POST['assign']) ? sql_injection($_POST['assign']) : NULL;
if ($create == "domain") {
    $Name = sql_injection($_POST['Name']);
    $Description = sql_injection($_POST['Description']);
    $isSingular = sql_injection($_POST['isSingular']);

    $domain = new roles_domains();
    $domain->DomainName = $Name;
    $domain->DomainDescription = $Description;
    $domain->DomainisSingular = $isSingular;

    if (!$domain->DomainName || !$domain->DomainisSingular) {
        header("location: role.domain.php?#invalid1");
        exit();
    }
    $domain->create_domain();



    if ($domain->DomainisSingular == "Yes") {
        $domain->DomainID = $domain->mysql->iid();
        $domain->ObjectName = $domain->DomainName;
        $domain->ObjectDescription = $domain->PrivilegeDescription;
        $domain->create_object();
        $domain->ObjectID = $domain->mysql->iid();
        $domain->create_domain_object();
    }
    header("location: role.domain.php?#success");
    exit();
} elseif ($create == "object") {
    $Name = sql_injection($_POST['Name']);
    $Description = sql_injection($_POST['Description']);
    $domain = new roles_domains();
    $domain->ObjectName = $Name;
    $domain->ObjectDescription = $Description;
    if ($domain->ObjectName && $domain->ObjectDescription)
        $domain->create_object();
    header("location: role.domain.php?#sucess");
}
elseif ($assign == "object") {
    $domain = new roles_domains();
    $domain->ObjectID = sql_injection($_POST['ObjectID']);
    $domain->DomainID = sql_injection($_POST['DomainID']);
    if ($domain->ObjectID && $domain->DomainID)
        $domain->create_domain_object();
    header("location: role.domain.php?#success");
}


$domain = new roles_domains();
$domain_queries = $domain->get_domains();
$domain_list = "";
while ($domain_object = $mysql->fo($domain_queries)) {
    $domain_list .= "<tr><td class=\"cell\">{$domain_object->Name}</td>
						<td class=\"cell\">{$domain_object->Description}</td>
						<td class=\"cell\" align=\"center\">{$domain_object->isSingular}</td>
					</tr>
	";
    $domain_option_list .= "<option value=\"{$domain_object->ID}\">{$domain_object->Name} - {$domain_object->isSingular}</option>";
}

$domain = new roles_domains();
$object_queries = $domain->get_objects();
$object_list = "";
while ($object = $mysql->fo($object_queries)) {
    $object_list .= "<tr><td class=\"cell\">{$object->Name}</td>
						<td class=\"cell\">{$object->Description}</td>
					</tr>
	";
    $object_option_list .= "<option value=\"{$object->ID}\">{$object->Name}</option>";
}



$domain = new roles_domains();
$domains_objects_queries = $domain->get_domains_objects();
$domains_objects_list = "";
while ($domains_objects = $mysql->fo($domains_objects_queries)) {
    $domains_objects_list .= "
		<tr>
			<td>{$domains_objects->DomainName}</td>
			<td>{$domains_objects->ObjectName}</td>
			<td>{$domains_objects->isSingular}</td>
		</tr>
	";
}

eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
eval("\$content = \"" . $objTemplate->get("role.domain.content") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("../configuration.end.php");
?>