<?php

$categories .= "<li><a href=\"index.php\" class=\"active\">All products</a></li>";

$category = new Category();
$query = $category->get_parent_categories();
while ($obj = $mysql->fo($query)) {
    $row = Category::get_object($obj);
    $categories .= "<li><a href=\"category.php?id={$row->id}\">" . ucfirst($row->name) . "</a></li>";

    $query_child = $row->get_child();
    if ($mysql->n($query_child) > 0) {
        while ($child_obj = $mysql->fo($query_child)) {
            $child = Category::get_object($child_obj);
            
            $categories .= "<li><a href=\"category.php?id={$child->id}\"> " . ucfirst($child->name) . "</a></li>";
        }
    }
}
?>