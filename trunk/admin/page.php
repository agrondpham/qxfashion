<?php

require_once("../configuration.php");
require_once("../includes/function.php");
require_once("global.php");
$role = new account_roles("VIEW", "Pages Admin");

if ($role->isAuthenticated()) {
// show off: template
    require_once("../includes/io.php");
    require_once("../includes/template.php");
    require_once("../includes/page.php");

    // initialization
    $title = "Admin - Page";
    $page_id = isset($_GET['page'])? sql_injection($_GET['page']) : NULL;
    $objTemplate = new template("../admin");
    $defaulttemplate = new template("../default");
    $role_rows = "";
    $roles_td = "";
    $pages_rows = "";
    
    // setting
    require_once("../includes/setting.php");
    $objSetting = new setting();
    
    // pages
    $page = new page();
    $page->limit = $objSetting->page_limit;
    $total_pages = $page->get_total_pages();
    
    // pagination
    require_once("../includes/pagination.php");
    $objPagination = new pagination("page.php?page=",$page_id, $total_pages, $page->limit);
    
    // set start page and get pages
    $page->start = $objPagination->start;
    $pages_queries = $page->get_pages();
    
    // html
    $pagination_html = $objPagination->get_html();
    while ($obj = $mysql->fo($pages_queries)) {
        $page = page::static_set_object($obj);
        eval("\$pages_rows .= \"" . $objTemplate->get("page.rows") . "\";");
    }

// other layout elements
    eval("\$header = \"" . $objTemplate->get("header") . "\";");
    eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
    eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
    eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
    eval("\$content = \"" . $objTemplate->get("page.content") . "\";");
    eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
    eval("\$index = \"" . $objTemplate->get("index") . "\";");

    // display all
    echo ($index); // can use echo $template->compress($index) to compress size of html

    require_once("../configuration.end.php");
} else {
    $mysql->c();
    header("HTTP/1.1 301 Moved Permanently");
    header("location: index.php");
    exit();
}
?>