<?php
class page_content
{
    public $page_id;
    public $content;
    
    public $connection;
    public $limit;
    public $start;
    
    public function __construct()
    {
        global $mysql;
        $this->connection = $mysql;
        $this->start = 0;
        $this->limit = 10;
    }
    
    public function set_object_pages_content($obj)
    {
        $this->page_id = $obj->page_id;
        $this->content = $obj->content;
    }
    
    public static function static_set_object_pages_content($obj)
    {
        $pages_content = new pages_content();
        
        $pages_content->content = $obj->content;
        $pages_content->id = $obj->id;
        
        return $pages_content;
    }
    
    public function get_content()
    {
        $obj = $this->connection->qfo("SELECT * FROM {$GLOBALS["dbName"]}.`pages_content` WHERE `page_id` = '{$this->page_id}' LIMIT 1;");
        $this->set_object_pages_content($obj);
    }
    
    public function get_contents()
    {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`pages_content` LIMIT {$this->start},{$this->limit};");
    }
    
    public function create_content()
    {
        $this->connection->q("INSERT INTO {$GLOBALS["dbName"]}.`pages_content`(`page_id`,`content`) VALUES ( '{$this->page_id}','{$this->content}');");
    }
    
    public function update_content()
    {
        $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`pages_content` SET `content` = '{$this->content}' WHERE `page_id` = '{$this->page_id}';");
    }
    
    public function delete_content()
    {
        $this->connection->q("DELETE FROM {$GLOBALS["dbName"]}.`pages_content` WHERE `page_id` = '{$this->page_id}';");
    }
}
?>