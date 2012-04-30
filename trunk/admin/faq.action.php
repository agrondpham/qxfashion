<?php

require_once("../configuration.php");
require_once("global.php");
// show off: template
require_once("../includes/io.php");
require_once("../includes/template.php");
require_once("../includes/faq.php");
require_once("../includes/function.php");

$class_name = "";
$class_content = "";
$class_submit = "";
$errors = "";
$submit_button = "Create";
$faq = new faq();

$select = isset($_GET['select']) ? intval(sql_injection($_GET['select'])) : NULL;
$delete = isset($_GET['delete']) ? intval(sql_injection($_GET['delete'])) : NULL;

if ($select != NULL && is_integer($select)) {
    $faq->id = $select;
    $submit_button = "Update";
    if (isset($_POST['action']) && $_POST['action'] == "Update") {
        $class_error = "input_error";
        $faq->name = isset($_POST['name']) ? sql_injection($_POST['name']) : NULL;
        $faq->content = isset($_POST['content']) ? sql_injection($_POST['content']) : NULL;

        if (isNull($faq->name)) {
            $ErrorMessage[] = "Question is invalid.";
            $class_name = $class_error;
        }

        if (isNull($faq->content)) {
            $ErrorMessage[] = "Content is invalid.";
            $class_content = $class_error;
        }

        if (!isset($ErrorMessage)) {
            $faq->update();
            $errors = "You have updated successfully the question <b>{$faq->name}</b>. <a href=\"faq.php\">Click here</a> to go back.";
        } else {
            $class_submit = $class_error;
            $errors .= "<ol style=\"padding: 6px 12px; margin-left: 10px; border: 1px solid #c60;\">";
            $i = 1;
            foreach ($ErrorMessage as $name => $value) {
                $errors .= "<li>{$i}. {$value}</li>";
                $i++;
            }
            $errors .= "</ol>";
        }
    } else {
        $faq->get();
    }
} else if (isset($_POST['action']) && $_POST['action'] == "Create") {
    $faq->name = isset($_POST['name']) ? sql_injection($_POST['name']) : NULL;
    $faq->content = isset($_POST['content']) ? sql_injection($_POST['content']) : NULL;
    if (isNull($faq->name)) {
        $ErrorMessage[] = "Question is invalid.";
        $class_name = $class_error;
    }

    if (isNull($faq->content)) {
        $ErrorMessage[] = "Content is invalid.";
        $class_content = $class_error;
    }

    if (!isset($ErrorMessage)) {
        $faq->create();
        $errors = "You have created successfully the question <b>{$faq->name}</b>. <a href=\"faq.php\">Click here</a> to go back.";
    } else {
        $class_submit = $class_error;
        $errors .= "<ol style=\"padding: 6px 12px; margin-left: 10px; border: 1px solid #c60;\">";
        $i = 1;
        foreach ($ErrorMessage as $name => $value) {
            $errors .= "<li>{$i}. {$value}</li>";
            $i++;
        }
        $errors .= "</ol>";
    }
}
else if($delete != NULL && is_int($delete))
{
    $faq->id = $delete;
    $faq->delete();
    $mysql->c();
    header("location: faq.php");
    exit();
}

$title = "Admin - Action - {$submit_button}";
$objTemplate = new template("../admin");
$defaulttemplate = new template("../default");
eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
eval("\$content = \"" . $objTemplate->get("faq.action.content") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("../configuration.end.php");
?>