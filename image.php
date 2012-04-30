<?php

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id >= 0) {
    
    if (is_integer($id) == false) {
        exit();
    }
    else
    {
        require_once("includes/configuration.php");
        require_once("libraries/tables/image.php");
    }

    $image = new image();
    
    if($id == 0)
    {
        $image->link = $noimage;// "default/images/noimg.jpg";
    }
    else
    {
        $image->id = $id;
        $image->get();
        if($image->link == "")
            $image->link = $noimage;//"default/images/noimg.jpg";
    }
    $mysql->c();
    
    
    
    if ($image->link) {

        if (file_exists($image->link)) {
            
            $size = getImageSize($image->link);
            $width_img = $size[0];
            $height_img = $size[1];
            $mime = $size["mime"];
            
            header("Content-type: {$mime}");
//            header("Cache-Control: private, max-age=10800, pre-check=10800");
//            header("Pragma: private");
//            header("Expires: " . date(DATE_RFC822, strtotime(" 1 minute")));
//            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
//                // if the browser has a cached version of this image, send 304
//                header('Last-Modified: ' . $_SERVER['HTTP_IF_MODIFIED_SINCE'], true, 304);
//                exit;
//            }
//            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == filemtime($image->link))) {
//                header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($image->link)) . ' GMT+8', true, 304);
//                exit();
//            }
//            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($image->link)) . ' GMT+8');
            ob_start();


            readfile($image->link);
            $data = ob_get_contents();
            $contentlength = ob_get_length();
            ob_end_clean();
            header("Content-Length: " . $contentlength);
            echo $data;
            exit();
        } else {

            exit();
        }
    }
}
else
{
   
}
?>