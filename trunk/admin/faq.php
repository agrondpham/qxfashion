<?php

require_once("../configuration.php");
require_once("global.php");

$role = new account_roles("VIEW", "FAQ Admin");
if ($role->isAuthenticated()) {

    require_once("../includes/function.php");
// show off: template
    require_once("../includes/io.php");
    require_once("../includes/template.php");
    require_once("../includes/faq.php");

    $title = "Admin - Frequently Asked Question List";
    $objTemplate = new template("../admin");
    $defaulttemplate = new template("../default");
    $faqs = "";
    $page = isset($_GET['page'])? sql_injection($_GET['page']) : 0;

// required setting
    require_once("../includes/setting.php");
    $objSetting = new setting();

// FAQs    
    $faq = new faq();
    $faq->limit = $objSetting->faq_limit;
    $total_faqs = $faq->get_total_faqs();
// PAGINATION
    require_once("../includes/pagination.php");
    $objPagination = new pagination("faq.php?page=", $page, $total_faqs, $faq->limit);
    $pagination_html = $objPagination->get_html();
    $faq->start = $objPagination->start;
    $faq_queries = $faq->get_faqs();
    while ($obj = $mysql->fo($faq_queries)) {
        $faq = faq::static_set_object($obj);
        eval("\$faqs .= \"" . $objTemplate->get("faq.rows") . "\";");
    }


    eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
    eval("\$header = \"" . $objTemplate->get("header") . "\";");
    eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
    eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
    eval("\$content = \"" . $objTemplate->get("faq.content") . "\";");
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