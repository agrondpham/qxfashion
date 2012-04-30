<?php

require_once("../configuration.php");
require_once("global.php");
$role = new account_roles("VIEW", "About Admin");
if ($role->isAuthenticated()) {

    require_once("../includes/function.php");


// show off: template
    require_once("../includes/io.php");
    require_once("../includes/template.php");

    $title = "Admin - Contact";
    $objTemplate = new template("../admin");
    $defaulttemplate = new template("../default");
    $contact_content = "";
    $contact_content_path = $defaulttemplate->folder . "/html/contact.content.html";

    if (isset($_POST['action'])) {
        $contact_content = isset($_POST['content']) ? $_POST['content'] : NULL;
        if ($contact_content != NULL) {
            $file_access = fopen($contact_content_path, "w+");
            fwrite($file_access, $contact_content);
            fclose($file_access);
        }
    } else {
        if (file_exists($contact_content_path)) {
            $contact_content = file_get_contents($contact_content_path);
        }
    }
    $contact_content_textarea = htmlspecialchars($contact_content);
    $cart_product_rows = "";
    $total = "99999.99";
    eval("\$cart = \"" . $defaulttemplate->get("body.cart") . "\";");
    eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
    eval("\$header = \"" . $objTemplate->get("header") . "\";");
    eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
    eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
    eval("\$content = \"" . $objTemplate->get("contact.content") . "\";");
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