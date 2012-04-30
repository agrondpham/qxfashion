<?php

class ThumbComponent {

    var $gdver = 2;
    var $errWidthImg = 103;
    var $errHeightImg = 73;
    var $saveDir;
    var $saveImg = false;
    var $saveOverwrite;
    var $sourceDir;
    var $imgDir;
    var $errReturn = false;
    var $cPercent = 100;
    var $fontSize = 2;
    var $bgRGBColor = array(10, 10, 10);
    var $msgRGBColor = array(255, 17, 23);
    var $shadowDir;
    var $dropshadow = false;
    var $shadowImg = array();
    var $transparent = false;
    var $watermark = false;
    var $watermark_opacity = 90;
    var $watermark_image;
    var $rounded_transparent_corner = false;
    var $newx, $new_x, $newy, $new_y;
    var $newname;

    function setErr($msg, $offset) {
        // if $this->saveImg set true, the return must be true if not the error image will show and you will not get a thumb.
        if ($this->saveImg)
            $this->errReturn = true;
        else {
            $bgRGBColor = $this->bgRGBColor;
            $msgRGBColor = $this->msgRGBColor;

            $im = @imagecreate($this->errWidthImg, $this->errHeightImg);
            $bgColor = imagecolorallocate($im, $bgRGBColor[0], $bgRGBColor[1], $bgRGBColor[2]);
            $msgColor = imagecolorallocate($im, $msgRGBColor[0], $msgRGBColor[1], $msgRGBColor[2]);
            imagefilledrectangle($im, 0, 0, 90, 70, $bgColor);
            imagestring($im, $this->fontSize, $offset, 28, $msg, $msgColor);
            header("Content-type: image/jpeg");
            imagejpeg($im, '', $this->cPercent);
            imagedestroy($im);
            exit();
        }
    }

    public function resized($src, $desiredw, $desiredh, $shadow_color="383938") {
        //if(!$desiredw||!$desiredh)
        //    $this->setErr("WIDTH|HIGHT ERR",6);

        $shadow_color = $this->toRGB($shadow_color);
        $shadow_color = explode(",", $shadow_color);

        $gdarr = array(1, 2);
        $test = "";
        for ($i = 0; $i < count($gdarr); $i++) {
            if ($this->gdver != $gdarr[$i])
                $test.="|";
        }
        $exp = explode("|", $test);

        (count($exp) == 3) ? $this->setErr("GD VER NOT FOUND", 5) : "";

        $size = @getimagesize($this->sourceDir . $src);

        if (!$size) {
            $this->setErr("Image Error", 20);
            if ($this->errReturn)
                return false;
        }
        else {
            if ($size[2] == 1)
                $this->dropshadow = false; // disable when image is a gif file
            (is_string($this->saveImg)) ? $this->setErr("VAR ERR", 30) : "";

            ((!$this->saveOverwrite) && !isset($this->saveDir) && $this->saveImg) ? $this->setErr("VAR ERR", 30) : "";

            if (!$desiredw || !$desiredh) {
                $desiredw = $size[0];
                $desiredh = $size[1];
            }

            if ($size[0] >= $size[1] * ((float) ($desiredw / $desiredh))) {
                $this->newy = round(($size[1] * (float) ($desiredw / $size[0])), 0);
                $this->newx = $desiredw;
            } else {
                $this->newy = $desiredh;
                $this->newx = round(($size[0] * (float) ($desiredh / $size[1])), 0);
            }

            $this->shadowImg = array(
                'left' => @imagecreatefromPNG($this->shadowDir . "sim_left.png"),
                'right' => @imagecreatefromPNG($this->shadowDir . "sim_right.png"),
                'top' => @imagecreatefromPNG($this->shadowDir . "sim_top.png"),
                'bottom' => @imagecreatefromPNG($this->shadowDir . "sim_bottom.png"),
                'tlcorner' => @imagecreatefromPNG($this->shadowDir . "sim_tlcorner.png"),
                'trcorner' => @imagecreatefromPNG($this->shadowDir . "sim_trcorner.png"),
                'blcorner' => @imagecreatefromPNG($this->shadowDir . "sim_blcorner.png"),
                'brcorner' => @imagecreatefromPNG($this->shadowDir . "sim_brcorner.png"),
            );

            $this->new_x = @imagesx($this->shadowImg['left']) + @imagesx($this->shadowImg['right']) + $this->newx;
            $this->new_y = @imagesy($this->shadowImg['top']) + @imagesy($this->shadowImg['bottom']) + $this->newy;

            ($this->gdver == 1) ? $destimg = @imagecreate((!$this->dropshadow) ? $this->newx : $this->new_x, (!$this->dropshadow) ? $this->newy : $this->new_y) or $this->setErr("GD ERR", 30) : $destimg = @imagecreatetruecolor((!$this->dropshadow) ? $this->newx : $this->new_x, (!$this->dropshadow) ? $this->newy : $this->new_y ) or $this->setErr("GD2 ERR", 30);

            @imagefill($destimg, 0, 0, imagecolorallocate($destimg, $shadow_color[0], $shadow_color[1], $shadow_color[2]));

            if ($this->errReturn)
                return false;

            if ($size[2] == 1) {
                $sourceimg = imagecreatefromgif($this->sourceDir . $src);
                if ($this->transparent)
                    $this->set_transparency($destimg, $sourceimg);
                if ($this->rounded_transparent_corner)
                    $this->rounded_transparent_corner($destimg, $sourceimg, $size);
                $this->dropCreate($destimg, $sourceimg, $size[0], $size[1]);
                if ($this->watermark)
                    $this->watermarking($destimg, $size);
                if (!$this->saveImg)
                    header("content-type: image/gif");
                ($this->saveImg) ? imagegif($destimg, (($this->saveOverwrite) ? $this->sourceDir . $src : $this->saveDir . ((strlen($this->newname) > 0) ? $this->newname : $src)), $this->cPercent) : imagegif($destimg, '', $this->cPercent);
                return true;
            }
            elseif ($size[2] == 2) {
                $sourceimg = imagecreatefromjpeg($this->sourceDir . $src);
                $this->dropCreate($destimg, $sourceimg, $size[0], $size[1]);
                if ($this->watermark)
                    $this->watermarking($destimg, $size);
                if (!$this->saveImg)
                    header("content-type: image/jpeg");
                ($this->saveImg) ? imagejpeg($destimg, (($this->saveOverwrite) ? $this->sourceDir . $src : $this->saveDir . ((strlen($this->newname) > 0) ? $this->newname : $src)), $this->cPercent) : imagejpeg($destimg, '', $this->cPercent);
                return true;
            }
            elseif ($size[2] == 3) {
                $sourceimg = imagecreatefrompng($this->sourceDir . $src);
                if ($this->transparent)
                    $this->set_transparency($destimg, $sourceimg);
                if ($this->rounded_transparent_corner)
                    $this->rounded_transparent_corner($destimg, $sourceimg, $size);
                $this->dropCreate($destimg, $sourceimg, $size[0], $size[1]);
                if ($this->watermark)
                    $this->watermarking($destimg, $size);
                if (!$this->saveImg)
                    header("content-type: image/png");
                ($this->saveImg) ? imagepng($destimg, (($this->saveOverwrite) ? $this->sourceDir . $src : $this->saveDir . ((strlen($this->newname) > 0) ? $this->newname : $src)), $this->cPercent) : imagepng($destimg, '', $this->cPercent);
                return true;
            }
            else
                $this->setErr("TYPE ERR", 30);
        }

        imagedestroy($destimg);
        imagedestroy($sourceimg);
    }

    public function dropCreate($destimg, $sourceimg, $w, $h) {
        if (!$this->dropshadow) {
            @imagecopyresampled($destimg, $sourceimg, 0, 0, 0, 0, $this->newx, $this->newy, $w, $h);
            return true;
        }

        $funcResize = ($this->gdver == 2) ? "imagecopyresampled" : "imagecopyresized";
        @$funcResize($destimg, $this->shadowImg['tlcorner'], 0, 0, 0, 0, @imagesx($this->shadowImg['tlcorner']), @imagesy($this->shadowImg['tlcorner']), @imagesx($this->shadowImg['tlcorner']), @imagesy($this->shadowImg['tlcorner']));
        @$funcResize($destimg, $this->shadowImg['top'], @imagesx($this->shadowImg['left']), 0, 0, 0, $this->newx, @imagesy($this->shadowImg['top']), @imagesx($this->shadowImg['top']), @imagesy($this->shadowImg['top']));
        @$funcResize($destimg, $this->shadowImg['trcorner'], ($this->new_x - @imagesx($this->shadowImg['right'])), 0, 0, 0, @imagesx($this->shadowImg['trcorner']), @imagesy($this->shadowImg['trcorner']), @imagesx($this->shadowImg['trcorner']), @imagesy($this->shadowImg['trcorner']));
        @$funcResize($destimg, $this->shadowImg['left'], 0, @imagesy($this->shadowImg['top']), 0, 0, @imagesx($this->shadowImg['left']), $this->newy, @imagesx($this->shadowImg['left']), @imagesy($this->shadowImg['left']));
        @$funcResize($destimg, $this->shadowImg['right'], ($this->new_x - @imagesx($this->shadowImg['right'])), @imagesy($this->shadowImg['tlcorner']), 0, 0, @imagesx($this->shadowImg['right']), $this->newy, @imagesx($this->shadowImg['right']), @imagesy($this->shadowImg['right']));
        @$funcResize($destimg, $this->shadowImg['blcorner'], 0, ($this->new_y - @imagesy($this->shadowImg['bottom'])), 0, 0, @imagesx($this->shadowImg['blcorner']), @imagesy($this->shadowImg['blcorner']), @imagesx($this->shadowImg['blcorner']), @imagesy($this->shadowImg['blcorner']));
        @$funcResize($destimg, $this->shadowImg['bottom'], @imagesx($this->shadowImg['left']), ($this->new_y - @imagesy($this->shadowImg['bottom'])), 0, 0, $this->newx, @imagesy($this->shadowImg['bottom']), @imagesx($this->shadowImg['bottom']), @imagesy($this->shadowImg['bottom']));
        @$funcResize($destimg, $this->shadowImg['brcorner'], ($this->new_x - @imagesx($this->shadowImg['right'])), ($this->new_y - @imagesy($this->shadowImg['bottom'])), 0, 0, @imagesx($this->shadowImg['brcorner']), @imagesy($this->shadowImg['brcorner']), @imagesx($this->shadowImg['brcorner']), @imagesy($this->shadowImg['brcorner']));
        @$funcResize($destimg, $sourceimg, @imagesx($this->shadowImg['left']), @imagesy($this->shadowImg['top']), 0, 0, $this->newx, $this->newy, $w, $h);
    }

    public function toRGB($hex) {
        $_1stHex = $hex[0] . $hex[1];
        $_2ndHex = $hex[2] . $hex[3];
        $_3rdHex = $hex[4] . $hex[5];
        $R = strtoupper(hexdec($_1stHex));
        $G = strtoupper(hexdec($_2ndHex));
        $B = strtoupper(hexdec($_3rdHex));
        return $R . "," . $G . "," . $B;
    }

    public function set_transparency($new_image, $image_source) {
        $transparencyIndex = imagecolortransparent($image_source);
        $transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255);

        if ($transparencyIndex >= 0) {
            $transparencyColor = imagecolorsforindex($image_source, $transparencyIndex);
        }
        $transparencyIndex = imagecolorallocate($new_image, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
        imagefill($new_image, 0, 0, $transparencyIndex);
        imagecolortransparent($new_image, $transparencyIndex);
    }

    public function rounded_transparent_corner($new_image, $image_source, $size=array()) {
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
        imagecolortransparent($new_image, $colorcode); //make corners transparent
    }

    public function watermarking($image_source, $mainsize) {
        $imgMark = imagecreatefromgif($this->watermark_image);
        $size[0] = imagesx($imgMark);
        $size[1] = imagesy($imgMark);
        $nX = ((!$this->dropshadow) ? $this->newx : $this->new_x);
        $nY = ((!$this->dropshadow) ? $this->newy : $this->new_y);
        $dX = @round($size[0] * ($nX / $mainsize[0]), 0);
        $dY = @round($size[1] * ($nY / $mainsize[1]), 0);
        $dX = ($nX - $dX);
        $dY = ($nY - $dY);
        imagecopymerge($image_source, $imgMark, $dX, $dY, 0, 0, imagesx($imgMark), imagesy($imgMark), $this->watermark_opacity);
    }

}

?>