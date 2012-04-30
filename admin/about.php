<?php

require_once("../configuration.php");

require_once("global.php");
$role = new account_roles("VIEW", "Homepage Admin");
if ($role->isAuthenticated()){


    require_once("../includes/function.php");


// show off: template
    require_once("../includes/io.php");
    require_once("../includes/template.php");

    $title = "Admin - About";
    $objTemplate = new template("../admin");
    $defaulttemplate = new template("../default");
    $about_content = "";
    $about_content_path = $defaulttemplate->folder . "/html/about.content.html";

    if (isset($_POST['action'])) {
        $about_content = isset($_POST['content']) ? $_POST['content'] : NULL;
        if ($about_content != NULL) {
            $file_access = fopen($about_content_path, "w+");
            fwrite($file_access, $about_content);
            fclose($file_access);
        }
    } else {
        if (file_exists($about_content_path)) {
            $about_content = file_get_contents($about_content_path);
        }
    }
    $about_content_textarea = htmlspecialchars($about_content);
    $cart_product_rows = "This is just a demo side bar";
    $total = 999999.99;
    eval("\$cart = \"" . $defaulttemplate->get("body.cart") . "\";");
    eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
    eval("\$header = \"" . $objTemplate->get("header") . "\";");
    eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
    eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
    eval("\$content = \"" . $objTemplate->get("about.content") . "\";");
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