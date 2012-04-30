<?php

$page = new page();
$page_queries = $page->get_pages();


while ($obj = $mysql->fo($page_queries)) {
    $page = page::static_set_object($obj);
    $isAllowed = true;
    switch ($page->name) {
        case "Login":
        case "Register":
            if($CurrentSession->id > 0)
            {
                $isAllowed = false;
            }
            break;
        case "Logout":
        case "View Orders":
        case "Update profile":
            if($CurrentSession->id > 0)
            {
                $isAllowed = true;
            }
            else
                $isAllowed = false;
            break;
    }

    if ($isAllowed) {
        eval("\$menu_rows .= \"" . $objTemplate->get("menu.rows") . "\";");
    }
}
eval("\$menu = \"" . $objTemplate->get("menu") . "\";");
?>