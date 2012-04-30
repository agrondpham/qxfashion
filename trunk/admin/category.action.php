<?php
require_once("../configuration.php");
require_once("global.php");
require_once("../includes/function.php");
require_once("../includes/category.php");
// show off: template
require_once("../includes/io.php");
require_once("../includes/template.php");

// INITIALIZATION
$title = "TEST";
$objTemplate = new template("../admin");
$defaulttemplate = new template("../default");

$category_options = "";
$categories = "";
$name = "";
$parentid = "";
$selection = "";
$description = "";
$button_submit = "Create";

// security
// action : update category
if (isset($_GET['select']) && isset($_POST['create'])) {
    $category = new Category();
    $category->id = sql_injection($_GET['select']);
    $category->name = sql_injection($_POST['name']);
    $category->description = sql_injection($_POST['description']);
    $category->parentid = sql_injection($_POST['parentid']);
    $category->update();
    $mysql->c();
    header('location: /admin/category.php?' . time());
    exit();
} else if (isset($_POST['create'])) { // action: create category

    $category = new Category();
    $category->name = sql_injection($_POST['name']);
    $category->description = sql_injection($_POST['description']);
    $category->parentid = sql_injection($_POST['parentid']);
    $category->create();
    $mysql->c();
    header('location: /admin/category.php?' . time());
    exit();
} else if (isset($_GET['delete'])) {
    $category = new Category();
    $category->id = sql_injection($_GET['delete']);
    $category->delete();
    $mysql->c();
    header('location: /admin/category.php');
    exit();
}

if (isset($_GET['select'])) {
    $selected_categoryid = (isset($_GET['select'])) ? sql_injection($_GET['select']) : 0;
    $selected_category = new Category();
    $selected_category->id = $selected_categoryid;
    $selected_category->get();

    $name = $selected_category->name;
    $description = $selected_category->description;
    
    $button_submit = "Update";
}

$category = new Category();
$query = $category->get_parent_categories();
while ($obj = $mysql->fo($query)) {
    $row = Category::get_object($obj);
    if (isset($selected_category)) {
        if ($row->id == $selected_category->parentid)
        {
            
            $selection = "selected";
        }
        else
            $selection = "";
    }

    $category_options .= "<option value=\"{$row->id}\" {$selection}>" . $row->name . "</option>";

    $query_child = $row->get_child();
    if ($mysql->n($query_child) > 0) {
        while ($child_obj = $mysql->fo($query_child)) {
            $child = Category::get_object($child_obj);
            
            $category_options .= "<option value=\"{$child->id}\" disabled>- {$child->name}</option>";
        }
    }
}
eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
eval("\$content = \"" . $objTemplate->get("category.action.content") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("../configuration.end.php");
?>