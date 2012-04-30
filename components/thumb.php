<?php

class thumb {

    public $GD_VERSION;
    public $FONT_SIZE;
    public $ERROR_IMAGE_WIDTH;
    public $ERROR_IMAGE_HEIGHT;
    public $IMAGE_QUALITY_PERCENTAGE;
    public $WATERMARK_OPACITY;
    public $may_save;
    public $may_overwrite;
    public $may_return_error;
    public $may_use_transparent;
    public $may_use_watermark;
    public $may_use_shadow;
    public $may_use_rounded_corner;
    public $directory_to_save;
    public $directory_of_shadow_image;
    public $directory_to_read;
    public $watermark_image_file;
    public $newname;
    private $background_image_colour = array(10, 10, 10);
    private $background_error_colour = array(255, 17, 23);
    private $shadow_dimension_array = array();
    private $new_original_image_horizontal_x;
    private $new_shadow_image_horizontal_y;
    private $new_original_image_vertical_y;
    private $new_shadow_image_vertical_y;

    public function __construct() {
        $this->GD_VERSION = 2;
        $this->FONT_SIZE = 2;
        $this->ERROR_IMAGE_WIDTH = 103;
        $this->ERROR_IMAGE_HEIGHT = 73;
        $this->IMAGE_QUALITY_PERCENTAGE = 100;
        $this->WATERMARK_OPACITY = 100;

        $this->may_save = true;
        $this->may_overwrite = true;
        $this->may_return_error = true;
        $this->may_use_transparent = false;
        $this->may_use_watermark = false;
        $this->may_use_shadow = false;
        $this->may_use_rounded_corner = false;

        $this->background_image_colour = array(10, 10, 10);
        $this->background_error_colour = array(255, 17, 23);
        $this->shadow_dimension_array = array();

        $this->directory_of_shadow_image = "../default/images/";
    }

    function create_image_error($msg, $offset) {
        if ($this->may_save)
            $this->may_return_error = false;
        else {
            $background_image_colour = $this->background_image_colour;
            $background_error_colour = $this->background_error_colour;

            $im = @imagecreate($this->ERROR_IMAGE_WIDTH, $this->ERROR_IMAGE_HEIGHT);
            $bgColor = imagecolorallocate($im, $background_image_colour[0], $background_image_colour[1], $background_image_colour[2]);
            $msgColor = imagecolorallocate($im, $background_error_colour[0], $background_error_colour[1], $background_error_colour[2]);
            imagefilledrectangle($im, 0, 0, 90, 70, $bgColor);
            imagestring($im, $this->FONT_SIZE, $offset, 28, $msg, $msgColor);
            header("Content-type: image/jpeg");
            imagejpeg($im, '', $this->IMAGE_QUALITY_PERCENTAGE);
            imagedestroy($im);
            exit();
        }
    }

    public function resize_image($image_source, $width, $height, $shadow_hex_colour="383938") {

        $shadow_hex_colour = $this->hext_to_rgb($shadow_hex_colour);
        $shadow_hex_colour = explode(",", $shadow_hex_colour);

        $GD_ARRAY = array(1, 2);
        $seperators = "";
        for ($i = 0; $i < count($GD_ARRAY); $i++) {
            if ($this->GD_VERSION != $GD_ARRAY[$i])
                $seperators.="|";
        }
        $exploded_array = explode("|", $seperators);

        (count($exploded_array) == 3) ? $this->create_image_error("GD VER NOT FOUND", 5) : "";


        $image_size = @getimagesize($this->directory_to_read . $image_source);

        if (!$image_size) {
            $this->create_image_error("Image Error", 20);
            if ($this->may_return_error)
                return false;
        }
        else {
            if ($image_size[2] == 1)
                $this->may_use_shadow = false; // disable when image is a gif file
            (is_string($this->may_save)) ? $this->create_image_error("VAR ERR", 30) : "";

            ((!$this->may_overwrite) && !isset($this->directory_to_save) && $this->may_save) ? $this->create_image_error("VAR ERR", 30) : "";

            if (!$width || !$height) {
                $width = $image_size[0];
                $height = $image_size[1];
            }
            if ($width > $height && $height > 0)
                $division = ((float) ($width / $height));
            else
                $division = 1;

            if ($image_size[0] >= $image_size[1] * $division) {
                $this->new_original_image_vertical_y = round(($image_size[1] * (float) ($width / $image_size[0])), 0);
                $this->new_original_image_horizontal_x = $width;
            } else {
                $this->new_original_image_vertical_y = $height;
                $this->new_original_image_horizontal_x = round(($image_size[0] * (float) ($height / $image_size[1])), 0);
            }

            if ($this->may_use_shadow) {
                $this->shadow_dimension_array = array(
                    'left' => @imagecreatefromPNG($this->directory_of_shadow_image . "shadow_image_left.png"),
                    'right' => @imagecreatefromPNG($this->directory_of_shadow_image . "shadow_image_right.png"),
                    'top' => @imagecreatefromPNG($this->directory_of_shadow_image . "shadow_image_top.png"),
                    'bottom' => @imagecreatefromPNG($this->directory_of_shadow_image . "shadow_image_bottom.png"),
                    'tlcorner' => @imagecreatefromPNG($this->directory_of_shadow_image . "shadow_image_top_left_corner.png"),
                    'trcorner' => @imagecreatefromPNG($this->directory_of_shadow_image . "shadow_image_top_rightcorner.png"),
                    'blcorner' => @imagecreatefromPNG($this->directory_of_shadow_image . "shadow_image_bottom_left_corner.png"),
                    'brcorner' => @imagecreatefromPNG($this->directory_of_shadow_image . "shadow_image_bottom_right_corner.png"),
                );

                $this->new_shadow_image_horizontal_y = @imagesx($this->shadow_dimension_array['left']) + @imagesx($this->shadow_dimension_array['right']) + $this->new_original_image_horizontal_x;
                $this->new_shadow_image_vertical_y = @imagesy($this->shadow_dimension_array['top']) + @imagesy($this->shadow_dimension_array['bottom']) + $this->new_original_image_vertical_y;
            }
            ($this->GD_VERSION == 1) ? $destination_image = @imagecreate((!$this->may_use_shadow) ? $this->new_original_image_horizontal_x : $this->new_shadow_image_horizontal_y, (!$this->may_use_shadow) ? $this->new_original_image_vertical_y : $this->new_shadow_image_vertical_y) or $this->create_image_error("GD ERR", 30) : $destination_image = @imagecreatetruecolor((!$this->may_use_shadow) ? $this->new_original_image_horizontal_x : $this->new_shadow_image_horizontal_y, (!$this->may_use_shadow) ? $this->new_original_image_vertical_y : $this->new_shadow_image_vertical_y ) or $this->create_image_error("GD2 ERR", 30);

            @imagefill($destination_image, 0, 0, imagecolorallocate($destination_image, $shadow_hex_colour[0], $shadow_hex_colour[1], $shadow_hex_colour[2]));



            if ($image_size[2] == 1) {
                $sourceimg = imagecreatefromgif($this->directory_to_read . $image_source);
                if ($this->may_use_transparent)
                    $this->create_transparency($destination_image, $sourceimg);
                if ($this->may_use_rounded_corner)
                    $this->create_rounded_corner($destination_image, $sourceimg, $image_size);
                $this->create_drop_shadow($destination_image, $sourceimg, $image_size[0], $image_size[1]);
                if ($this->may_use_watermark)
                    $this->create_watermark($destination_image, $image_size);
                if (!$this->may_save)
                    header("content-type: image/gif");
                ($this->may_save) ? imagegif($destination_image, (($this->may_overwrite) ? $this->directory_to_read . $image_source : $this->directory_to_save . ((strlen($this->newname) > 0) ? $this->newname : $image_source)), $this->IMAGE_QUALITY_PERCENTAGE) : imagegif($destination_image, '', $this->IMAGE_QUALITY_PERCENTAGE);
                return true;
            }
            elseif ($image_size[2] == 2) {
                $sourceimg = imagecreatefromjpeg($this->directory_to_read . $image_source);
                $this->create_drop_shadow($destination_image, $sourceimg, $image_size[0], $image_size[1]);
                if ($this->may_use_watermark)
                    $this->create_watermark($destination_image, $image_size);
                if (!$this->may_save)
                    header("content-type: image/jpeg");
                ($this->may_save) ? imagejpeg($destination_image, (($this->may_overwrite) ? $this->directory_to_read . $image_source : $this->directory_to_save . ((strlen($this->newname) > 0) ? $this->newname : $image_source)), $this->IMAGE_QUALITY_PERCENTAGE) : imagejpeg($destination_image, '', $this->IMAGE_QUALITY_PERCENTAGE);
                return true;
            }
            elseif ($image_size[2] == 3) {
                $sourceimg = imagecreatefrompng($this->directory_to_read . $image_source);
                if ($this->may_use_transparent)
                    $this->create_transparency($destination_image, $sourceimg);
                if ($this->may_use_rounded_corner)
                    $this->create_rounded_corner($destination_image, $sourceimg, $image_size);
                $this->create_drop_shadow($destination_image, $sourceimg, $image_size[0], $image_size[1]);
                if ($this->may_use_watermark)
                    $this->create_watermark($destination_image, $image_size);
                if (!$this->may_save)
                    header("content-type: image/png");
                ($this->may_save) ? imagepng($destination_image, (($this->may_overwrite) ? $this->directory_to_read . $image_source : $this->directory_to_save . ((strlen($this->newname) > 0) ? $this->newname : $image_source)), $this->IMAGE_QUALITY_PERCENTAGE) : imagepng($destination_image, '', $this->IMAGE_QUALITY_PERCENTAGE);
                return true;
            }
            else
                $this->create_image_error("TYPE ERR", 30);
        }

        imagedestroy($destination_image);
        imagedestroy($sourceimg);
    }

    public function create_drop_shadow($destination_image, $sourceimg, $w, $h) {
        if (!$this->may_use_shadow) {
            @imagecopyresampled($destination_image, $sourceimg, 0, 0, 0, 0, $this->new_original_image_horizontal_x, $this->new_original_image_vertical_y, $w, $h);
            return true;
        }

        $funcResize = ($this->GD_VERSION == 2) ? "imagecopyresampled" : "imagecopyresize_image";
        @$funcResize($destination_image, $this->shadow_dimension_array['tlcorner'], 0, 0, 0, 0, @imagesx($this->shadow_dimension_array['tlcorner']), @imagesy($this->shadow_dimension_array['tlcorner']), @imagesx($this->shadow_dimension_array['tlcorner']), @imagesy($this->shadow_dimension_array['tlcorner']));
        @$funcResize($destination_image, $this->shadow_dimension_array['top'], @imagesx($this->shadow_dimension_array['left']), 0, 0, 0, $this->new_original_image_horizontal_x, @imagesy($this->shadow_dimension_array['top']), @imagesx($this->shadow_dimension_array['top']), @imagesy($this->shadow_dimension_array['top']));
        @$funcResize($destination_image, $this->shadow_dimension_array['trcorner'], ($this->new_shadow_image_horizontal_y - @imagesx($this->shadow_dimension_array['right'])), 0, 0, 0, @imagesx($this->shadow_dimension_array['trcorner']), @imagesy($this->shadow_dimension_array['trcorner']), @imagesx($this->shadow_dimension_array['trcorner']), @imagesy($this->shadow_dimension_array['trcorner']));
        @$funcResize($destination_image, $this->shadow_dimension_array['left'], 0, @imagesy($this->shadow_dimension_array['top']), 0, 0, @imagesx($this->shadow_dimension_array['left']), $this->new_original_image_vertical_y, @imagesx($this->shadow_dimension_array['left']), @imagesy($this->shadow_dimension_array['left']));
        @$funcResize($destination_image, $this->shadow_dimension_array['right'], ($this->new_shadow_image_horizontal_y - @imagesx($this->shadow_dimension_array['right'])), @imagesy($this->shadow_dimension_array['tlcorner']), 0, 0, @imagesx($this->shadow_dimension_array['right']), $this->new_original_image_vertical_y, @imagesx($this->shadow_dimension_array['right']), @imagesy($this->shadow_dimension_array['right']));
        @$funcResize($destination_image, $this->shadow_dimension_array['blcorner'], 0, ($this->new_shadow_image_vertical_y - @imagesy($this->shadow_dimension_array['bottom'])), 0, 0, @imagesx($this->shadow_dimension_array['blcorner']), @imagesy($this->shadow_dimension_array['blcorner']), @imagesx($this->shadow_dimension_array['blcorner']), @imagesy($this->shadow_dimension_array['blcorner']));
        @$funcResize($destination_image, $this->shadow_dimension_array['bottom'], @imagesx($this->shadow_dimension_array['left']), ($this->new_shadow_image_vertical_y - @imagesy($this->shadow_dimension_array['bottom'])), 0, 0, $this->new_original_image_horizontal_x, @imagesy($this->shadow_dimension_array['bottom']), @imagesx($this->shadow_dimension_array['bottom']), @imagesy($this->shadow_dimension_array['bottom']));
        @$funcResize($destination_image, $this->shadow_dimension_array['brcorner'], ($this->new_shadow_image_horizontal_y - @imagesx($this->shadow_dimension_array['right'])), ($this->new_shadow_image_vertical_y - @imagesy($this->shadow_dimension_array['bottom'])), 0, 0, @imagesx($this->shadow_dimension_array['brcorner']), @imagesy($this->shadow_dimension_array['brcorner']), @imagesx($this->shadow_dimension_array['brcorner']), @imagesy($this->shadow_dimension_array['brcorner']));
        @$funcResize($destination_image, $sourceimg, @imagesx($this->shadow_dimension_array['left']), @imagesy($this->shadow_dimension_array['top']), 0, 0, $this->new_original_image_horizontal_x, $this->new_original_image_vertical_y, $w, $h);
    }

    public function hext_to_rgb($hex) {
        $_1stHex = $hex[0] . $hex[1];
        $_2ndHex = $hex[2] . $hex[3];
        $_3rdHex = $hex[4] . $hex[5];
        $R = strtoupper(hexdec($_1stHex));
        $G = strtoupper(hexdec($_2ndHex));
        $B = strtoupper(hexdec($_3rdHex));
        return $R . "," . $G . "," . $B;
    }

    public function create_transparency($new_image, $image_source) {
        $transparencyIndex = imagecolormay_use_transparent($image_source);
        $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255);

        if ($transparencyIndex >= 0) {
            $transparencyColor = imagecolorsforindex($image_source, $transparencyIndex);
        }
        $transparencyIndex = imagecolorallocate($new_image, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
        imagefill($new_image, 0, 0, $transparencyIndex);
        imagecolormay_use_transparent($new_image, $transparencyIndex);
    }

    public function create_rounded_corner($new_image, $image_source, $size=array()) {
        $corner = 40;
        //find colorcode
        $palette = imagecreatetruecolor($size[0], $size[1]);
        $found = false;
        while ($found == false) {
            $r = rand(0, 255);
            $g = rand(0, 255);
            $b = rand(0, 255);
            if (imagecolorexact($new_image, $r, $g, $b) != (-1)) {
                $colorcode = imagecolorallocate($palette, $r, $g, $b);
                $found = true;
            }
        }
        //draw corners
        imagearc($new_image, $corner - 1, $corner - 1, $corner * 2, $corner * 2, 180, 270, $colorcode);
        imagefilltoborder($new_image, 0, 0, $colorcode, $colorcode);
        imagearc($new_image, $size[0] - $corner, $corner - 1, $corner * 2, $corner * 2, 270, 0, $colorcode);
        imagefilltoborder($new_image, $size[0], 0, $colorcode, $colorcode);
        imagearc($new_image, $corner - 1, $size[1] - $corner, $corner * 2, $corner * 2, 90, 180, $colorcode);
        imagefilltoborder($new_image, 0, $size[1], $colorcode, $colorcode);
        imagearc($new_image, $size[0] - $corner, $size[1] - $corner, $corner * 2, $corner * 2, 0, 90, $colorcode);
        imagefilltoborder($new_image, $size[1], $size[1], $colorcode, $colorcode);
        imagecolormay_use_transparent($new_image, $colorcode); //make corners may_use_transparent
    }

    public function create_watermark($image_source, $mainsize) {
        $imgMark = imagecreatefromgif($this->watermark_image_file);
        $size[0] = imagesx($imgMark);
        $size[1] = imagesy($imgMark);
        $nX = ((!$this->may_use_shadow) ? $this->new_original_image_horizontal_x : $this->new_shadow_image_horizontal_y);
        $nY = ((!$this->may_use_shadow) ? $this->new_original_image_vertical_y : $this->new_shadow_image_vertical_y);
        $dX = @round($size[0] * ($nX / $mainsize[0]), 0);
        $dY = @round($size[1] * ($nY / $mainsize[1]), 0);
        $dX = ($nX - $dX);
        $dY = ($nY - $dY);
        imagecopymerge($image_source, $imgMark, $dX, $dY, 0, 0, imagesx($imgMark), imagesy($imgMark), $this->WATERMARK_OPACITY);
    }

}

?>