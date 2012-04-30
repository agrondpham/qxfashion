<?php

require_once("page_content.php");

class page_status {
    const Active = "Active";
    const InActive = "InActive";
}

class page_type {
    const Link = "LinkOnly";
    const Content = "Content";
}

class page extends page_content {

    public $id;
    public $order;
    public $status;
    public $type;
    public $name;
    public $title; // unique title
    public $link;
    public $limit;
    public $start;
    public $connection;

    public function __construct() {
        global $mysql;
        $this->connection = $mysql;
        $this->limit = 10;
        $this->start = 0;
        $this->status = page_status::Active;
        $this->type = page_type::Content;
    }

    public function set_object($obj) {
        $this->id = $obj->id;
        $this->order = $obj->order;
        $this->status = $obj->status;
        $this->type = $obj->type;
        $this->name = $obj->name;
        $this->title = $obj->title;
        $this->link = $obj->link;
    }

    public static function static_set_object($obj) {
        $page = new page();
        $page->id = $obj->id;
        $page->order = $obj->order;
        $page->status = $obj->status;
        $page->type = $obj->type;
        $page->name = $obj->name;
        $page->title = $obj->title;
        $page->link = $obj->link;
        return $page;
    }

    public function get() {
        $obj = $this->connection->qfo("SELECT * FROM {$GLOBALS["dbName"]}.`pages` WHERE `id`='{$this->id}' LIMIT 1;");
        $this->set_object($obj);
    }
    
    public function get_total_pages()
    {
        return $this->connection->qfo("SELECT count(0) as TOTAL FROM {$GLOBALS["dbName"]}.`pages` WHERE `status` = '{$this->status}';");
    }

    public function get_pages() {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`pages` WHERE `status` = '{$this->status}' ORDER BY `order` ASC LIMIT {$this->start},{$this->limit};");
    }

    public function create() {
        $this->connection->q("INSERT INTO {$GLOBALS["dbName"]}.`pages`(`id`,`order`,`status`,`type`,`name`,`title`,`link`) VALUES ( NULL,'{$this->order}','{$this->status}','{$this->type}','{$this->name}','{$this->title}','{$this->link}') ON DUPLICATE title=title + ' duplicated';");
        $this->id = $this->iid();
    }

    public function update() {
        $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`pages` SET `order`='{$this->order}',`name`='{$this->name}','{$this->title}',`link`='{$this->link}' WHERE `id`='{$this->id}';");
    }

    public function delete() {
        $this->connection->q("DELETE FROM {$GLOBALS["dbName"]}.`pages` WHERE `id` = '{$this->id}';");
    }

}

?>