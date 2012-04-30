<?php

class IO {

    public $Fp_Count = 0;

    public function __construct() {
        
    }

    public function open($File, $Pipe = 0, $Mode = 'r') {
        if ($Mode != 'r+' or $Mode != 'w' or $Mode != 'w+' or $Mode != 'a' or $Mode != 'a+') {
            $Mode = 'r';
        }

        if ($this->exists($File)) {
            if ($Pipe) {
                $iFP = popen($File, $Mode);
            } else {
                $iFP = fopen($File, $Mode);
            }

            $this->Fp_Count++;
        } else {
            Die("Error when open this file: <b>$File</b>");
        }
        return $iFP;
    }

    public function get_dir($path) {
        return opendir($path);
    }

    public function close($fp, $Pipe = 0) {
        if ($Pipe) {
            pclose($fp);
        } else {
            fclose($fp);
        }
    }

    public function get_data($fp, $File) {
        @flock($fp, LOCK_SH);
        $Content = fread($fp, filesize($File));
        return $Content;
    }

    public function exists($File) {
        if (file_exists($File)) {
            return true;
        } else {
            return false;
        }
    }

    public function exists_folder($folder) {
        if (is_dir($folder))
            return true; else
            return false;
    }

    public function get_row($File, $Lenght, $StrStart) {
        $Content = fgetcsv($File, $Lenght, $StrStart) or die("Can't not read file:$File");
        return $Content;
    }

    public function get_file_size($File) {
        return filesize($File);
    }

    public function get_file_type($filename) {
        preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);

        switch (strtolower($fileSuffix[1])) {
            case "js" :
                return "application/x-javascript";

            case "json" :
                return "application/json";

            case "jpg" :
            case "jpeg" :
            case "jpe" :
                return "image/jpg";

            case "png" :
            case "gif" :
            case "bmp" :
            case "tiff" :
                return "image/" . strtolower($fileSuffix[1]);

            case "css" :
                return "text/css";

            case "xml" :
                return "application/xml";

            case "doc" :
            case "docx" :
                return "application/msword";

            case "xls" :
            case "xlt" :
            case "xlm" :
            case "xld" :
            case "xla" :
            case "xlc" :
            case "xlw" :
            case "xll" :
                return "application/vnd.ms-excel";

            case "ppt" :
            case "pps" :
                return "application/vnd.ms-powerpoint";

            case "rtf" :
                return "application/rtf";

            case "pdf" :
                return "application/pdf";

            case "html" :
            case "htm" :
            case "php" :
                return "text/html";

            case "txt" :
                return "text/plain";

            case "mpeg" :
            case "mpg" :
            case "mpe" :
                return "video/mpeg";

            case "mp3" :
                return "audio/mpeg3";

            case "wav" :
                return "audio/wav";

            case "aiff" :
            case "aif" :
                return "audio/aiff";

            case "avi" :
                return "video/msvideo";

            case "wmv" :
                return "video/x-ms-wmv";

            case "mov" :
                return "video/quicktime";

            case "zip" :
                return "application/zip";

            case "tar" :
                return "application/x-tar";

            case "swf" :
                return "application/x-shockwave-flash";

            default :
                if (function_exists("mime_content_type")) {
                    $fileSuffix = mime_content_type($filename);
                }

                return "unknown/" . trim($fileSuffix[0], ".");
        }
    }

    public function get_extension($file) {
        return strtolower(strrchr($file, "."));
    }

    public function get_name($url) {
        
    }

    //Size must be bytes!
    public function format_size($size, $round = 0) {
        $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        for ($i = 0; $size > 1024 && $i < count($sizes) - 1; $i++)
            $size /= 1024;
        $rounded_size = round($size, $round);
        $s = "";
        if ($rounded_size > 1)
            $s = "s";
        return $rounded_size . " " . $sizes[$i] . $s;
    }

    public function get_folder_size($path) {
        if (!file_exists($path))
            return 0;
        if (is_file($path))
            return filesize($path);
        $ret = 0;
        foreach (glob($path . "/*") as $fn)
            $ret += $this->get_folder_size($fn);
        return $ret;
    }

    public function lock($File, $Type="LOCK_EX") {

        flock($File, $Type) or die("Can not lock file:$File");
    }

    public function write($File, $Value, $Method="w") {
        $FileNum = fopen($File, $Method);
        flock($FileNum, LOCK_EX);
        $File_Data = fwrite($FileNum, $Value);
        fclose($FileNum);
        return $File_Data;
    }

    public function safe_name($name) {
        $except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|');
        return str_replace($except, '', $name);
    }

    public function create_folder($dir, $dirmode=0777) {
        if (!empty($dir)) {
            if (!file_exists($dir)) {
                preg_match_all('/([^\/]*)\/?/i', $dir, $atmp);
                $base = "";
                foreach ($atmp[0] as $key => $val) {
                    $base = $base . $val;
                    if (!file_exists($base))
                        if (!mkdir($base, $dirmode)) {
                            return false;
                        } else {
                            $this->rchmod($base, $dirmode, "0666");
                            return true;
                        }
                }
            } else
            if (!is_dir($dir)) {
                return false;
            }
            else
                return true;
        }
        else
            return false;
    }

    function rchmod($parent, $dmod, $fmod) {
        if (is_dir($parent)) {
            $old = umask(0000);
            chmod($parent, $dmod);
            umask($old);
            if ($handle = opendir($parent)) {
                while (($file = readdir($handle)) !== false) {
                    if ($file === "." or $file === "..") {
                        continue;
                    } elseif (is_dir($parent . '/' . $file)) {
                        rchmod($parent . '/' . $file, $dmod, $fmod);
                    } else {
                        $old = umask(0000);
                        chmod($parent . '/' . $file, $fmod);
                        umask($old);
                    }
                }
                closedir($handle);
            }
        } else {
            $old = umask(0000);
            chmod($parent, $fmod);
            umask($old);
        }
    }

    public function delete_file($file) {
        $do = unlink($file);
        if ($do)
            return true;
        else
            return false;
    }

}

?>