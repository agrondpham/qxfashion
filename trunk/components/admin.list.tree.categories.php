<?php
$category = new Category();
$query = $category->get_parent_categories();
while ($obj = $mysql->fo($query)) {
    $row = Category::get_object($obj);
    if (isset($selected_category)) {
        if ($row->id == $selected_category->parentid)
            $selection = "selected";
        else
            $selection = "";
    }
    $categories .= "<a href=\"?select={$row->id}\">" . $row->name . "</a> <a href=\"?delete={$row->id}\">[-]</a><br/>";
    $category_options .= "<option value=\"{$row->id}\" {$selection}>" . $row->name . "</option>";

    $query_child = $row->get_child();
    if ($mysql->n($query_child) > 0) {
        while ($child_obj = $mysql->fo($query_child)) {
            $child = Category::get_object($child_obj);

            $categories .= "-<a href=\"?select={$child->id}\">" . $child->name . " <a href=\"?delete={$child->id}\">[-]</a><br/>";
            $category_options .= "<option value=\"{$child->id}\" disabled>- {$child->name}</option>";
        }
    }
}
?>