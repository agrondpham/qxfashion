<?php

require_once("../configuration.php");
require_once("global.php");
require_once("../includes/function.php");
require_once("../includes/category.php");
// show off: template
require_once("../includes/io.php");
require_once("../includes/template.php");

$role = new account_roles("VIEW", "Category Admin");
if ($role->isAuthenticated()) {

// INITIALIZATION
    $title = "Admin - Category";
    $objTemplate = new template("../admin");
    $defaulttemplate = new template("../default");

    $page = isset($_GET['page']) ? sql_injection($_GET['page']) : 0;
    $category_options = "";
    $categories = "";
    $name = "";
    $parentid = "";
    $selection = "";
    $description = "";
    $button_submit = "Create";

// required settings
    require_once("../includes/setting.php");
    $objSetting = new setting();
   
// required categories 
    $category = new Category();
    $category->limit = $objSetting->category_limit;
    $total_categories = $category->get_total_categories();
    
    require_once("../includes/pagination.php");
    $objPagination = new pagination("category.php?page=",$page, $total_categories, $category->limit);

// set start page and get category queries
    $category->start = $objPagination->start;
    $query = $category->get_categories();
    
    $pagination_html = $objPagination->get_html();

    while ($obj = $mysql->fo($query)) {
        $row = Category::get_object($obj);
         $categories .= "<tr>
                <td><a href=\"/category.php?id={$row->id}\">" . $row->name . "</a> (<font color=\"#AAAAAA\">Fixed</font>)</td>
                <td><a href=\"category.action.php?select={$row->id}\">Update</a> | <a href=\"category.action.php?delete={$row->id}\">Delete</a></td>
            </tr>";
    }

    eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
    eval("\$header = \"" . $objTemplate->get("header") . "\";");
    eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
    eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
    eval("\$content = \"" . $objTemplate->get("category.content") . "\";");
    eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
    eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
    echo ($index); // can use echo $template->compress($index) to compress size of html

    require_once("../configuration.end.php");
} else {
    header("HTTP/1.1 301 Moved Permanently");
    header("location: /admin/");
    $mysql->c();
}
?>