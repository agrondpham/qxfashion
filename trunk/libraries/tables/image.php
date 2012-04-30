<?php

class image_status {
    const Active = "Active";
    const InActive = "InActive";
}

class image {

    public $id;
    public $status;
    public $mime_type;
    public $name;
    public $link;
    public $limit;
    public $start;
    public $connection;

    function __construct() {
        global $mysql;
        $this->id = 0;
        $this->connection = $mysql;
        $this->limit = 10;
        $this->start = 0;
        $this->status = image_status::Active;
    }

    public function set_object($obj) {
        if (isset($obj->id)) {
            $this->id = $obj->id;
            $this->status = $obj->status;
            $this->mime_type = $obj->mime_type;
            $this->name = $obj->name;
            $this->link = $obj->link;
        }
    }

    public static function static_set_object($obj) {
        $image = new image();
        $image->set_object($obj);
        return $image;
    }

    public function get() {
        $obj = $this->connection->qfo("SELECT * FROM {$GLOBALS["dbName"]}.`images` WHERE `id` = '{$this->id}' LIMIT 1;");
        $this->set_object($obj);
    }

    public function get_images() {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`images`;");
    }

    public function create() {
        $this->connection->q("INSERT INTO {$GLOBALS["dbName"]}.`images`(`id`,`status`,`mime_type`,`name`,`link`) VALUES ( NULL,'{$this->status}','{$this->mime_type}','{$this->name}','{$this->link}');");
        $this->id = $this->connection->iid();
    }

    public function delete() {
        $this->connection->q("DELETE FROM {$GLOBALS["dbName"]}.`images` WHERE `id` = '{$this->id}';");
    }

}

?>