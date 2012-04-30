<?php

class uploadimage extends thumb {
    public $width;
    public $height;
    public $tmp_names_array;
    public $names_array;
    public $error_array;
    public $message;
    public $last_names_array;
    public $number_of_files;
    public $multiupload;
    
    public function __construct() {
        parent::__construct();
        $this->may_return_error = true;
        $this->width = 200;
        $this->height = 200;
        $this->number_of_files = 0;

        $this->message = array();
        $this->tmp_names_array = array();
        $this->names_array = array();
        $this->error_array = array();
        $this->last_names_array = array();
        
        

        $this->multiupload = new multiupload();
        $this->multiupload->FILENAME_MAX_LENGTH = 100;
        $this->multiupload->language = "english";
        $this->multiupload->may_create_directory = false;
        $this->multiupload->directory = "files/";
        $this->multiupload->extensions = array(".jpg", ".png");
        $this->multiupload->message[] = $this->multiupload->error_text(4);
        $this->multiupload->may_rename_file = true;
        $this->multiupload->may_check_filename = true;
        $this->multiupload->is_replacable = false;
    }

    public function upload_and_resize_images() {
        $this->multiupload->tmp_names_array = $this->tmp_names_array;
        $this->multiupload->names_array = $this->names_array;
        $this->multiupload->error_array = $this->error_array;
        $this->multiupload->upload_multi_files();
        sleep(1);
        if ($this->multiupload->number_of_files > 0) {
            $this->number_of_files = $this->multiupload->number_of_files;
            $this->last_names_array = $this->multiupload->last_names_array;
            for ($i = 0; $i < $this->multiupload->number_of_files; $i++) {
                if (isset($this->multiupload->last_names_array[$i])) {
                    $file = $this->multiupload->directory . $this->multiupload->last_names_array[$i];
                    if ($this->multiupload->exists($file)) {
                        $this->resize_image($file, $this->width, $this->height);
                        $this->multiupload->message[] = "you have resized <b>{$this->multiupload->last_names_array[$i]}</b> with the dimension {$this->width} and {$this->height}.";
                    } else {
                        for ($x = 0; $x < $i; $x++) {
                            $this->multiupload->delete_file($this->multiupload->directory . $this->multiupload->last_names_array[$x]);
                        }
                    }
                }
            }
            $this->message = $this->multiupload->message;
        }

        
    }

}

?>