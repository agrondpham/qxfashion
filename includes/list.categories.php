<?php

$class_active = "active";
$category_class = "";

if (!isset($category_id))
{
    $category_class = $class_active;
    $category_id = 0;
}
//$categories .= "<li><a href=\"index.php\" class=\"{$category_class}\">All products</a></li>";             LPT

$category = new Category();
$query = $category->get_parent_categories();
while ($obj = $mysql->fo($query)) {
    $row = Category::get_object($obj);

    if ($row->id == $category_id)
        $class_for_category = $class_active;
    else
        $class_for_category = "";

    //$categories .= "<li><a href=\"category.php?id={$row->id}\" class=\"{$class_for_category}\">" . ucfirst($row->name) . "</a></li>";
    //$parentcategories .= "";
    eval("\$categoryRow .= \"" . $objTemplate->get("category.rows") . "\";");
    $query_child = $row->get_child();
    if ($mysql->n($query_child) > 0) {
        while ($child_obj = $mysql->fo($query_child)) {
            $child = Category::get_object($child_obj);

            if ($child->id == $category_id)
                $class_for_category = $class_active;
            else
                $class_for_category = "";


            //$categories .= "<li><a href=\"category.php?id={$child->id}\" class=\"{$class_for_category}\"> " . ucfirst($child->name) . "</a></li>";
            //$categories .= "";
            eval("\$categoryRowsChild .= \"" . $objTemplate->get("category.rows.child") . "\";");
        }
    }
}
eval("\$categories .= \"" . $objTemplate->get("category") . "\";");
?>