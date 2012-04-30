<?php

require_once("../configuration.php");
require_once("global.php");

$role = new account_roles("VIEW", "Customer Admin");
if ($role->isAuthenticated()) {

    require_once("../includes/function.php");
// show off: template
    require_once("../includes/io.php");
    require_once("../includes/template.php");
    require_once("../includes/customer.php");    
    
// initialization
    $objTemplate = new template("../admin");
    $defaulttemplate = new template("../default");
    $customers = "";
    $page = isset($_GET['page'])? sql_injection($_GET['page']) : 0;

// setting
    require_once("../includes/setting.php");    
    $objSetting = new setting();
// customer rows
    $objCustomer = new customer();
    $total_customers = $objCustomer->get_total_customers();
    $objCustomer->limit = $objSetting->customer_limit;
// pagination
    require_once("../includes/pagination.php");
    $objPagination = new pagination("customer.php?page=",$page, $total_customers, $objCustomer->limit);
    $objCustomer->start = $objPagination->start;
    $customer_queries = $objCustomer->get_customers();
    
    $pagination_html = $objPagination->get_html();
    while ($obj = $mysql->fo($customer_queries)) {
        $objCustomer = customer::static_set_object($obj);
        eval("\$customers .= \"" . $objTemplate->get("customer.rows") . "\";");
    }
// for display
    $title = "Admin - Customer";

    eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
    eval("\$header = \"" . $objTemplate->get("header") . "\";");
    eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
    eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
    eval("\$content = \"" . $objTemplate->get("customer.content") . "\";");
    eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
    eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
    echo ($index); // can use echo $template->compress($index) to compress size of html

    require_once("../configuration.end.php");
} else {
    $mysql->c();
    header("HTTP/1.1 301 Moved Permanently");
    header("location: /admin/");
}
?>