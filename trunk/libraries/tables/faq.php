<?php
class faq {
    public $id;
    public $name;
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
    
    public static function static_set_object($obj)
    {
        $faq = new faq();
        $faq->id = $obj->id;
        $faq->name = $obj->name;
        $faq->content = $obj->content;
        
        return $faq;
    }
    
    public function set_object($obj)
    {
        $this->id = $obj->id;
        $this->name = $obj->name;
        $this->content = $obj->content;
    }
    
    public function get()
    {
        $obj = $this->connection->qfo("SELECT * FROM {$GLOBALS["dbName"]}.`faqs` WHERE id = '{$this->id}';");
        $this->set_object($obj);
    }
    
    public function get_total_faqs()
    {
        return $this->connection->qfo("SELECT count(0) as TOTAL FROM {$GLOBALS["dbName"]}.`faqs`")->TOTAL;
    }
    
    public function get_faqs()
    {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`faqs` LIMIT {$this->start},{$this->limit}");
    }
    
    public function create()
    {
        $this->connection->q("INSERT INTO {$GLOBALS["dbName"]}.`faqs`(`id`,`name`,`content`) VALUES ( NULL,'{$this->name}','{$this->content}');");
        $this->id = $this->connection->iid();
    }
    
    public function update()
    {
        $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`faqs` SET `name`='{$this->name}',`content`='{$this->content}' WHERE `id`='{$this->id}';");
    }
    
    public function delete()
    {
        $this->connection->q("DELETE FROM {$GLOBALS["dbName"]}.`faqs` WHERE `id`='{$this->id}';");
    }
}
?>