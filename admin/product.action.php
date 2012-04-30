<?php

require_once("../configuration.php");
require_once("global.php");
require_once("../includes/function.php");
require_once("../includes/product.php");
require_once("../includes/product.image.php");
require_once("../includes/category.php");
require_once("../includes/itemType.php");
require_once("../includes/product.php");
require_once("../includes/image.php");
// SHOW OFF: TEMPLATE
require_once("../includes/io.php");
require_once("../includes/template.php");
// required upload files
require_once("../includes/upload.php");
require_once("../includes/multiupload.php");
require_once("../includes/thumb.php");
require_once("../includes/uploadimage.php");

// VARIABLES
$products = "";
$category_options = "";
$itemType_options = ""; //AG
$product_demonstration = "";
$button_submit = "Create";
$product_rows = "";
$product_image_options = "";
$title = "Admin - Product - Edit";
$objTemplate = new template("../admin");
$defaulttemplate = new template("../default");
$product_form = new Product(); // reset product to its default values;
$image_form = new image();
$image_form->id = 0;
// ACTIONs
if (isset($_GET['import'])) {
    
} else
if (isset($_GET['delete'])) {
    $objProduct = new Product();
    $objProduct->id = (isset($_GET['delete'])) ? sql_injection($_GET['delete']) : 0;
    $objProduct->delete();
    $mysql->c();
    header("location: product.php");
    exit();
} else
if (isset($_GET['edit'])) {
    // action for updating product values
    if (isset($_POST['create'])) {
        $objProduct = new product();
        $objProduct->id = (isset($_GET['edit'])) ? sql_injection($_GET['edit']) : NULL;
        $objProduct->name = (isset($_POST['name'])) ? sql_injection($_POST['name']) : NULL;
        $objProduct->image_id = (isset($_POST['image_id'])) ? sql_injection($_POST['image_id']) : NULL;
        $objProduct->description = (isset($_POST['description'])) ? sql_injection($_POST['description']) : NULL;
        $objProduct->stock = (isset($_POST['stock'])) ? sql_injection($_POST['stock']) : NULL;
        $objProduct->discount = (isset($_POST['discount'])) ? sql_injection($_POST['discount']) : NULL;
        $objProduct->price = (isset($_POST['price'])) ? sql_injection($_POST['price']) : NULL;
        $objProduct->category_id = (isset($_POST['category_id'])) ? sql_injection($_POST['category_id']) : NULL;
        $objProduct->modifiedDate= date('Y-m-d H:i');//AG
        $objProduct->type_id=(isset($_POST['type_id'])) ? sql_injection($_POST['type_id']) : NULL;
        $objProduct->update();

        if (isset($_FILES['file'])) {
            $uploadimage = new uploadimage();
            $uploadimage->multiupload->directory = "../files/images/";
            $uploadimage->tmp_names_array = $_FILES['file']['tmp_name'];
            $uploadimage->names_array = $_FILES['file']['name'];
            $uploadimage->error_array = $_FILES['file']['error'];
            $uploadimage->upload_and_resize_images();


            if ($uploadimage->number_of_files > 0) {
                for ($i = 0; $i < $uploadimage->number_of_files; $i++) {
                    $original_file = $uploadimage->multiupload->directory . $uploadimage->names_array[$i];
                    $latest_file = $uploadimage->multiupload->directory . $uploadimage->last_names_array[$i];
                    if (file_exists($latest_file)) {
                        $io = new IO();

                        $image = new image();
                        $image->name = basename($uploadimage->names_array[$i]);
                        $image->link = "files/images/" . $uploadimage->last_names_array[$i];
                        $image_size = getimagesize($latest_file);
                        $image->mime_type = $image_size["mime"];
                        $image->status = image_status::Active;
                        $image->create();
                        $uploadimage->message[] = "You have inserted into database with image information.";

                        if ($image->id > 0) {
                            $product_image = new product_image();
                            $product_image->product_id = $objProduct->id;
                            $product_image->image_id = $image->id;
                            $product_image->create();
                            $uploadimage->message[] = "You have inserted into database with product image information.";

                            $objProduct->image_id = $image->id;
                            $objProduct->update_image_id();
                            $uploadimage->message[] = "You have updated product cover image id.";
                        }
                    }
                }
            }
        }

        $mysql->c();
        header("location: product.action.php?edit={$product->id}#");
        exit();
    }

    $product_form = new Product();
    $product_form->id = (isset($_GET['edit'])) ? sql_injection($_GET['edit']) : 0;
    $product_form->getall();
    $image_form = new image();
    if ($product_form->image_id > 0) {

        $image_form->id = $product_form->image_id;
        $image_form->get();
    }
    $selection = $product_form->category_id;
    $itemTypeSelection= $product_form->type_id;

    eval("\$product_demonstration = \"" . $objTemplate->get("product.demonstration.content") . "\";");

    $button_submit = "Edit";
} else
if (isset($_POST['create'])) {
    $objProduct = new Product();
    $objProduct->name = (isset($_POST['name'])) ? sql_injection($_POST['name']) : NULL;
    $objProduct->image_id = (isset($_POST['image_id'])) ? sql_injection($_POST['image_id']) : NULL;
    $objProduct->description = (isset($_POST['description'])) ? sql_injection($_POST['description']) : NULL;
    $objProduct->stock = (isset($_POST['stock'])) ? sql_injection($_POST['stock']) : NULL;
    $objProduct->discount = (isset($_POST['discount'])) ? sql_injection($_POST['discount']) : NULL;
    $objProduct->price = (isset($_POST['price'])) ? sql_injection($_POST['price']) : NULL;
    $objProduct->category_id = (isset($_POST['category_id'])) ? sql_injection($_POST['category_id']) : NULL;
    $objProduct->modifiedDate= date('Y-m-d H:i');//AG
    $objProduct->type_id=(isset($_POST['type_id'])) ? sql_injection($_POST['type_id']) : NULL;
    $objProduct->create();

    if (isset($_FILES['file'])) {
        $uploadimage = new uploadimage();
        $uploadimage->tmp_names_array = $_FILES['file']['tmp_name'];
        $uploadimage->names_array = $_FILES['file']['name'];
        $uploadimage->error_array = $_FILES['file']['error'];
        $uploadimage->upload_and_resize_images();
        $uploadimage->multiupload->directory = "../files/images/";

        if ($uploadimage->number_of_files > 0) {
            for ($i = 0; $i < $uploadimage->number_of_files; $i++) {
                $original_file = $uploadimage->multiupload->directory . $uploadimage->names_array[$i];
                $latest_file = $uploadimage->multiupload->directory . $uploadimage->last_names_array[$i];
                if (file_exists($latest_file)) {
                    $io = new IO();

                    $image = new image();
                    $image->name = basename($uploadimage->names_array[$i]);
                    $image->link = "files/images/" . $uploadimage->last_names_array[$i];
                    $image_size = getimagesize($latest_file);
                    $image->mime_type = $image_size["mime"];
                    $image->status = image_status::Active;
                    $image->create();
                    $uploadimage->message[] = "You have inserted into database with image information.";

                    if ($image->id > 0) {
                        $product_image = new product_image();
                        $product_image->product_id = $objProduct->id;
                        $product_image->image_id = $image->id;
                        $product_image->create();
                        $uploadimage->message[] = "You have inserted into database with product image information.";

                        $objProduct->image_id = $image->id;
                        $objProduct->update_image_id();
                        $uploadimage->message[] = "You have updated product cover image id.";
                    }
                }
            }
        }
    }

    $mysql->c();
    header("location: product.php?" . time());

    exit();
} else {
    $product_form = new product();
    $product_form->name = "Your product name";
    $product_form->description = "Your product description goes here.";
    eval("\$product_demonstration = \"" . $objTemplate->get("product.demonstration.content") . "\";");
}


//ISSET Settings
// CATEGORIES SECTION
$category = new Category();
$query = $category->get_parent_categories();
while ($obj = $mysql->fo($query)) {
    $row = Category::get_object($obj);

    $query_child = $row->get_child();
    $total_child = $mysql->n($query_child);

    if ($total_child != 0)
        $hasDisabled = "";
    else
        $hasDisabled = "";

    $category_options .= "<option value=\"{$row->id}\">" . $row->name . "</option>";

    if ($total_child > 0) {
        while ($child_obj = $mysql->fo($query_child)) {
            $child = Category::get_object($child_obj);

            if (!isset($selection)) {
                $selected = "selected";
                $selection = "";
            } else {
                if ($child->id == $selection)
                    $selected = "selected";
                else
                    $selected = "";
            }
            $category_options .= "<option value=\"{$child->id}\" {$selected}>- {$child->name}</option>";
        }
    }
}
// TYPES SECTION
$itemType = new ItemType();
$query = $itemType->get();
while ($obj = $mysql->fo($query)) {
    $row = ItemType::get_object($obj);
    if (!isset($itemTypeSelection)) {
        $selected = "selected";
        $itemTypeSelection = "";
    } else {
        if ($child->id == $selection)
            $selected = "selected";
        else
            $selected = "";
    }
    $itemType_options .= "<option value=\"{$row->ItmTypeID}\">" . $row->Name . "</option>";
}
//
//if (isset($_GET['edit'])) {
//    $product_image = new product_image();
//    $product_image->product_id = sql_injection($_GET['edit']);
//    $product_image_queries = $product_image->get_product_images();
//    if ($mysql->n($product_image_queries) > 0) {
//        while ($obj = $mysql->fo($product_image_queries)) {
//            $product_image->set_object($obj);
//            
//            $image = new image();
//            $image->id = $product_image->image_id;
//            $image->get();
//            
//            $product_image_options .= "<option value=\"{$image->id}\">{$image->name}</option>";
//        }
//    }
//}
eval("\$menu_admin_rows = \"" . $objTemplate->get("menu.admin.rows") . "\";");
eval("\$header = \"" . $objTemplate->get("header") . "\";");
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
eval("\$sidebar = \"" . $objTemplate->get("sidebar") . "\";");
eval("\$content = \"" . $objTemplate->get("product.action.content") . "\";");
eval("\$footer = \"" . $objTemplate->get("footer") . "\";");
eval("\$index = \"" . $objTemplate->get("index") . "\";");

// display all
echo ($index); // can use echo $template->compress($index) to compress size of html

require_once("../configuration.end.php");
?>