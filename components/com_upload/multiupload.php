<?php

class multiupload extends upload {

    public $number_of_files;
    public $wrong_extensions;
    public $bad_filenames;
    public $names_array;
    public $tmp_names_array;
    public $error_array;
    public $last_names_array;
    public $last_real_name;

    public function __construct() {
        $this->number_of_files = 0;
        $this->wrong_extensions = 0;
        $this->bad_filenames = 0;
        $this->last_names_array = array();
        $this->last_real_name = array();
    }

    function extra_text($msg_num) {
        switch ($this->language) {
            case "english":
            default:
                $extra_msg[1] = "Error for: <b>" . $this->file_name . "</b>";
                $extra_msg[2] = "You have tried to upload " . $this->wrong_extensions . " files with a bad extension, the following extensions are allowed: <b>" . $this->ext_string . "</b>";
                $extra_msg[3] = "Select at least on file.";
                $extra_msg[4] = "Select the file(s) for upload.";
                $extra_msg[5] = "You have tried to upload <b>" . $this->bad_filenames . " files</b> with invalid characters inside the filename.";
        }
        return $extra_msg[$msg_num];
    }

    function count_files() {
        foreach ($this->names_array as $test) {
            if ($test != "") {
                $this->number_of_files++;
            }
        }
        if ($this->number_of_files > 0) {
            return true;
        } else {
            return false;
        }
    }

    function upload_multi_files() {
        $this->message = "";
        if ($this->count_files()) {
            foreach ($this->names_array as $key => $value) {
                if ($value != "") {
                    $this->file_name = $value;
                    $new_name = $this->set_file_name();
                    $this->last_real_name[] = $this->file_name;
                    if ($this->check_file_name($new_name)) {
                        if ($this->validateExtension()) {
                            $this->file_copy = $new_name;
                            $this->temporary_file = $this->tmp_names_array[$key];
                            if (is_uploaded_file($this->temporary_file)) {
                                if ($this->move_upload($this->temporary_file, $this->file_copy)) {
                                    $this->message[] = $this->error_text($this->error_array[$key]);
                                    if ($this->may_rename_file)
                                        $this->message[] = $this->error_text(16);
                                    sleep(1); // wait a seconds to get an new timestamp (if rename is set)
                                }
                                $this->last_names_array[] = $this->file_copy;
                            } else {
                                $this->message[] = $this->extra_text(1);
                                $this->message[] = $this->error_text($this->error_array[$key]);
                                $this->last_names_array[] = $this->file_copy;
                            }
                        } else {
                            $this->wrong_extensions++;
                        }
                    } else {
                        $this->bad_filenames++;
                    }
                }
            }
            if ($this->bad_filenames > 0)
                $this->message[] = $this->extra_text(5);
            if ($this->wrong_extensions > 0) {
                $this->show_extensions();
                $this->message[] = $this->extra_text(2);
            }
        } else {
            $this->message[] = $this->extra_text(3);
        }
    }

}

?>