<?php

class upload extends IO {

    public $file_name;
    public $temporary_file;
    public $directory;
    public $extensions;
    public $ext_string;
    public $language;
    public $FILENAME_MAX_LENGTH;
    public $file_error;
    public $is_replacable;
    public $may_check_filename;
    public $may_rename_file;
    public $may_create_directory;
    public $file_copy;
    public $message;

    public function __construct() {
        $this->language = "english";
        $this->may_rename_file = false;
        $this->may_create_directory = true;
        $this->may_check_filename = true;
        $this->ext_string = "";
        $this->FILENAME_MAX_LENGTH = 100;
        $this->message = array();
    }

    public function show_error_string() {
        $msg_string = "";
        foreach ($this->message as $value) {
            $msg_string .= $value . "<br>\n";
        }
        return $msg_string;
    }

    public function set_file_name($new_name = "") {
        if ($this->may_rename_file) {
            if ($this->file_name == "")
                return;
            $name = ($new_name == "") ? md5($this->file_name . strtotime("now") . microtime(false)) : $new_name;
            $name = $name . parent::get_extension($this->file_name);
        } else {
            $name = $this->file_name;
        }
        return $name;
    }

    public function upload($to_name = "") {
        $new_name = $this->set_file_name($to_name);
        if ($this->check_file_name($new_name)) {
            if ($this->validateExtension()) {
                if (is_uploaded_file($this->temporary_file)) {
                    $this->file_copy = $new_name;
                    if ($this->move_upload($this->temporary_file, $this->file_copy)) {
                        $this->message[] = $this->error_text($this->file_error);
                        if ($this->may_rename_file)
                            $this->message[] = $this->error_text(16);
                        return true;
                    }
                } else {
                    $this->message[] = $this->error_text($this->file_error);
                    return false;
                }
            } else {
                $this->show_extensions();
                $this->message[] = $this->error_text(11);
                return false;
            }
        } else {
            return false;
        }
    }

    public function check_file_name($the_name) {
        if ($the_name != "") {
            if (strlen($the_name) > $this->FILENAME_MAX_LENGTH) {
                $this->message[] = $this->error_text(13);
                return false;
            } else {
                if ($this->may_check_filename == true) {
                    if (preg_match("/^[a-z0-9_]*\.(.){1,5}$/i", $the_name)) {
                        return true;
                    } else {
                        $this->message[] = $this->error_text(12);
                        return false;
                    }
                } else {
                    return true;
                }
            }
        } else {
            $this->message[] = $this->error_text(10);
            return false;
        }
    }

    public function validateExtension() {
        $extension = parent::get_extension($this->file_name);
        $ext_array = $this->extensions;
        if (in_array($extension, $ext_array)) {
            return true;
        } else {
            return false;
        }
    }

    public function show_extensions() {
        $this->ext_string = implode(" ", $this->extensions);
    }

    public function move_upload($tmp_file, $new_file) {
        if ($this->existing_file($new_file)) {
            $newfile = $this->directory . $new_file;
            if ($this->check_dir($this->directory)) {
                if (move_uploaded_file($tmp_file, $newfile)) {
                    if ($this->is_replacable == true) {
                        //system("chmod 0777 $newfile"); // maybe you need to use the system command in some cases...
                        parent::rchmod($newfile, 0777, 0777);
                    } else {
                        // system("chmod 0755 $newfile");
                        parent::rchmod($newfile, 0777, 0755);
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                $this->message[] = $this->error_text(14);
                return false;
            }
        } else {
            $this->message[] = $this->error_text(15);
            return false;
        }
    }

    public function check_dir($directory) {
        if (!parent::exists_folder($directory)) {
            if ($this->may_create_directory) {
                parent::create_folder($directory, 0777);
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function existing_file($file_name) {
        if ($this->is_replacable == true) {
            return true;
        } else {
            if (parent::exists($this->directory . $file_name)) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function get_uploaded_file_info($name) {
        $str = "File name: " . basename($name) . "\n";
        $str .= "File size: " . filesize($name) . " bytes\n";
        if (function_exists("mime_content_type")) {
            $str .= "Mime type: " . mime_content_type($name) . "\n";
        }
        if ($img_dim = getimagesize($name)) {
            $str .= "Image dimensions: x = " . $img_dim[0] . "px, y = " . $img_dim[1] . "px\n";
        }
        return $str;
    }

    // this method was first located inside the foto_upload extension
    public function del_temp_file($file) {
        $delete = @unlink($file);
        clearstatcache();
        if (@file_exists($file)) {
            $filesys = preg_replace("/", "\\", $file);
            $delete = @system("del $filesys");
            clearstatcache();
            if (@file_exists($file)) {
                $delete = @chmod($file, 0775);
                $delete = @unlink($file);
                $delete = @system("del $filesys");
            }
        }
    }

    public function error_text($err_num) {
        switch ($this->language) {
            case 'english':
            default:
                // start http errors
                $error[0] = "File: <b>" . $this->file_name . "</b> is successfully uploaded!";
                $error[1] = "The uploaded file exceeds the max. upload filesize directive in the server configuration.";
                $error[2] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form.";
                $error[3] = "The uploaded file was only partially uploaded";
                $error[4] = "No file was uploaded";
                $error[6] = "Missing a temporary folder.";
                $error[7] = "Failed to write file to disk";
                $error[8] = "File upload stopped by extension";
                // end  http errors
                $error[10] = "Please select a file for upload.";
                $error[11] = "Only files with the following extensions are allowed: <b>" . $this->ext_string . "</b>";
                $error[12] = "Sorry, the filename contains invalid characters. Use only alphanumerical chars and separate parts of the name (if needed) with an underscore. <br>A valid filename ends with one dot followed by the extension.";
                $error[13] = "The filename exceeds the maximum length of " . $this->FILENAME_MAX_LENGTH . " characters.";
                $error[14] = "Sorry, the upload directory ({$this->directory}) doesn't exist!";
                $error[15] = "Uploading <b>" . $this->file_name . "...Error!</b> Sorry, a file with this name already exitst.";
                $error[16] = "The uploaded file has been renamed to <b>" . $this->file_copy . "</b>.";
                break;
        }
        return $error[$err_num];
    }

}

/*
 * Simple upload example

  $upload = new upload;
  $upload->directory = "files/";
  $upload->extensions = array(".rar");
  $upload->message[] = $upload->error_text(4);
  $upload->may_rename_file = true;
  $upload->may_check_filename = true;
  $upload->is_replacable = false;
  if (isset($_FILES['file'])) {
  $upload->temporary_file = $_FILES['file']['tmp_name'];
  $upload->file_name = $_FILES['file']['name'];
  $upload->file_error = $_FILES['file']['error'];
  $upload->upload();
  }
 * 
 */

/*
 * Extreme upload example ( multiple upload )
 */
/*
  $multiupload = new multiupload();
  $multiupload->directory = "files/";
  $multiupload->extensions = array(".jpg", ".png");
  $multiupload->message[] = $multiupload->error_text(4);
  $multiupload->may_rename_file = true;
  $multiupload->may_check_filename = true;
  $multiupload->is_replacable = false;
  if (isset($_FILES['file'])) {
  $multiupload->tmp_names_array = $_FILES['file']['tmp_name'];
  $multiupload->names_array = $_FILES['file']['name'];
  $multiupload->error_array = $_FILES['file']['error'];
  $multiupload->upload_multi_files();

  $total_files = $multiupload->number_of_files;

  if ($total_files > 0) {
  for ($i = 0; $i < $total_files; $i++) {
  if (isset($multiupload->last_names_array[$i])) {
  if ($multiupload->exists($multiupload->directory . $multiupload->last_names_array[$i])) {
  $name = $multiupload->last_real_name[$i];
  $extension = $multiupload->get_extension($multiupload->last_names_array[$i]);
  $link = $multiupload->directory . $multiupload->last_names_array[$i];
  $size = $multiupload->get_file_size($multiupload->directory . $multiupload->last_names_array[$i]);
  } else {
  for ($x = 0; $x < $i; $x++) {
  $multiupload->delete_file($multiupload->directory . $multiupload->last_names_array[$x]);
  }

  die("failed to upload :<strong>" . $multiupload->directory . $multiupload->last_names_array[$i] . "</strong>");
  }
  }
  }
  }
  }

  $uploadimage = new uploadimage();
  if (isset($_FILES['file'])) {
  $uploadimage->tmp_names_array = $_FILES['file']['tmp_name'];
  $uploadimage->names_array = $_FILES['file']['name'];
  $uploadimage->error_array = $_FILES['file']['error'];
  $uploadimage->upload_and_resize_images();
  }
  ?>
  <form name="threadform"  method="POST" enctype="multipart/form-data" id="panel7" style="">
  <input type="hidden" name="MAX_FILE_SIZE" value="104857600">
  <input type="file" name="file[]"><br/>
  <input type="file" name="file[]"><br/>
  <input type="file" name="file[]"><br/>
  <input type="submit" name="submit" value="upload"/>
  </form>
  <?php
  if (isset($_FILES['file'])) {
  foreach ($uploadimage->message as $key => $value) {
  echo "Message[{$key}]: " . $value . "<br/>";
  }
  }
 * 
 */
?>