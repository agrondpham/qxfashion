<?php
class customers_creditcards
{
    public $id;
    public $customer_id;
    public $name;
    public $owner_name;
    public $number;
    public $year;
    public $month;
    
    public $connection;
    public $start;
    public $limit;
    
    public function __construct()
    {
        global $mysql;
        $this->connection = $mysql;
        $this->start = 0;
        $this->limit = 10;
    }
    
    public function set_object($obj)
    {
        $this->id = $obj->id;
        $this->customer_id = $obj->customer_id;
        $this->name = $obj->name;
        $this->owner_name = $obj->owner_name;
        $this->number = $obj->number;
        $this->year = $obj->year;
        $this->month = $obj->month;
    }
    
    public function get()
    {
        $obj = $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`customers_creditcards` WHERE `id` ='{$this->id}' limit 1;");
        $this->set_object($obj);
    }
    
    public function create()
    {
        $this->connection->q("INSERT INTO {$GLOBALS["dbName"]}.`customers_creditcards`(`id`,`customer_id`,`year`,`month`,`name`,`owner_name`,`number`) VALUES ( NULL,'{$this->customer_id}','{$this->year}','{$this->month}','{$this->name}','{$this->owner_name}','{$this->number}');");
        $this->id = $this->connection->iid();
    }
}
?>
