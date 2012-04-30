<?php

require_once("../configuration.php");
require_once("global.php");
require_once("../includes/function.php");
require_once("../includes/product.php");
require_once("../includes/category.php");
require_once("../includes/product.php");
require_once("../includes/function.number.php");

$role = new account_roles("VIEW", "Product Admin");
if ($role->isAuthenticated()) {

// VARIABLES
    $page = isset($_GET['page'])? sql_injection($_GET['page']) : NULL;
    $products = "";
    $category_options = "";
    $button_submit = "Create";
    $product_rows = "";
    $product_form = new Product(); // reset product to its default values;
// ACTIONs
    if (isset($_GET['import'])) {
        
    } else
    if (isset($_GET['delete'])) {
        $objProduct = new Product();
        $objProduct->id = (isset($_GET['delete'])) ? sql_injection($_GET['delete']) : 0;
        $objProduct->delete();
        $mysql->c();
        header("location: /admin/product.php");
        exit();
    } else
    if (isset($_GET['edit'])) {
        // action for updating product values
        if (isset($_POST['create'])) {
            $objProduct = new product();
            $objProduct->id = (isset($_GET['edit'])) ? sql_injection($_GET['edit']) : NULL;
            $objProduct->name = (isset($_POST['name'])) ? sql_injection($_POST['name']) : NULL;
            $objProduct->description = (isset($_POST['description'])) ? sql_injection($_POST['description']) : NULL;
            $objProduct->stock = (isset($_POST['stock'])) ? sql_injection($_POST['stock']) : NULL;
            $objProduct->discount = (isset($_POST['discount'])) ? sql_injection($_POST['discount']) : NULL;
            $objProduct->price = (isset($_POST['price'])) ? sql_injection($_POST['price']) : NULL;
            $objProduct->category_id = (isset($_POST['category_id'])) ? sql_injection($_POST['category_id']) : NULL;
            $objProduct->modifiedDate= date('Y-m-d H:i');//AG
            $objProduct->type_id=(isset($_POST['type_id'])) ? sql_injection($_POST['type_id']) : NULL;
            $objProduct->update();
            $mysql->c();
            header("location: /admin/product.php");
            exit();
        }

        $product_form = new Product();
        $product_form->id = (isset($_GET['edit'])) ? sql_injection($_GET['edit']) : 0;
        $product_form->get();

        $selection = $product_form->category_id;
        $itemTypeSelection= $product_form->type_id;
        $button_submit = "Edit";
    } else
    if (isset($_POST['create'])) {
        $objProduct = new Product();
        $objProduct->name = (isset($_POST['name'])) ? sql_injection($_POST['name']) : NULL;
        $objProduct->description = (isset($_POST['description'])) ? sql_injection($_POST['description']) : NULL;
        $objProduct->stock = (isset($_POST['stock'])) ? sql_injection($_POST['stock']) : NULL;
        $objProduct->discount = (isset($_POST['discount'])) ? sql_injection($_POST['discount']) : NULL;
        $objProduct->price = (isset($_POST['price'])) ? sql_injection($_POST['price']) : NULL;
        $objProduct->category_id = (isset($_POST['category_id'])) ? sql_injection($_POST['category_id']) : NULL;
        $objProduct->modifiedDate= date('Y-m-d H:i');//AG
        $objProduct->type_id=(isset($_POST['type_id'])) ? sql_injection($_POST['type_id']) : NULL;
        $objProduct->create();

        $mysql->c();
        header("location: /admin/product.php?" . time());
        exit();
    }


//ISSET Settings
// SHOW OFF: TEMPLATE
    require_once("../includes/io.php");
    require_once("../includes/template.php");

    $title = "Admin - Product";
    $objTemplate = new template("../admin");
    $defaulttemplate = new template("../default");

// required settings
    require_once ("../includes/setting.php");
    $objSetting = new setting();
    
// PRODUCT SECTION
    $objProduct = new Product();
    $objProduct->limit = $objSetting->product_limit;
    $total_products = $objProduct->get_total_products();
// required pagination
    require_once ("../includes/pagination.php");
    $objPagination = new pagination("product.php?page=",$page, $total_products, $objProduct->limit);
// set the start to get products    
    $objProduct->start = $objPagination->start;    
    $query_products = $objProduct->get_items();
  
    $pagination_html = $objPagination->get_html();
    while ($obj = $mysql->fo($query_products)) {
        $objProduct = Product::static_set_object($obj);
        if ($objProduct->discount > 0)
            $price_after_discount = get_discount_price($objProduct->price, $objProduct->discount);
        else
            $price_after_discount = $objProduct->price;
        eval("\$products .= \"" . $objTemplate->get("product.rows") . "\";");
    }

// CATEGORIES SECTION
//    $category = new Category();
//    $query = $category->get_parent_categories();
//    while ($obj = $mysql->fo($query)) {
//        $row = Category::get_object($obj);
//
//        $query_child = $row->get_child();
//        $total_child = $mysql->n($query_child);
//
//        if ($total_child != 0)
//            $hasDisabled = "disabled";
//        else
//            $hasDisabled = "";
//
//        $category_options .= "<option value=\"{$row->id}\" {$hasDisabled}>" . $row->name . "</option>";
//
//        if ($total_child > 0) {
//            while ($child_obj = $mysql->fo($query_child)) {
//                $child = Category::get_object($child_obj);
//
//                if (!isset($selection)) {
//                    $selected = "selected";
//                    $selection = "";
//                } else {
//                    if ($child->id == $selection)
//                        $selected = "selected";
//                    else
//                        $selected = "";
//                }
//                $category_options .= "<option value=\"{$child->id}\" {$selected}>- {$child->name}</option>";
//            }
//        }
//    }

    eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
    eval("\$header = \"" . $objTemplate->get("header") . "\";");
    eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
    eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
    eval("\$content = \"" . $objTemplate->get("product.content") . "\";");
    eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
    eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
    echo ($index); // can use echo $template->compress($index) to compress size of html

    require_once("../configuration.end.php");
}
else {
    $mysql->c();
    header("HTTP/1.1 301 Moved Permanently");
    header("location: login.php");
}
?>