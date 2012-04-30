<?php

class orderproduct {

    public $order_id; // pk
    public $product_id; // pk
    
    public $quantity;
    public $subtotal;

    public $connection;
    public $start;
    public $limit;
    
    public function __construct() {
        global $mysql;
        $this->connection = $mysql;
        $this->start = 0;
        $this->limit = 10;
    }

    public function set_object($obj) {
        $this->order_id = $obj->order_id;
        $this->product_id = $obj->product_id;
        $this->quantity = $obj->quantity;
        $this->subtotal = $obj->total;
    }

    public static function static_set_object($obj) {
        $op = new orderproduct();
        $op->order_id = $obj->order_id;
        $op->product_id = $obj->product_id;
        $op->quantity = $obj->quantity;
        $op->total = $obj->total;

        return $op;
    }
    
    public function get()
    {
        $this->connection->q("SELECT * FROM `orders_products` WHERE `order_id` = '{$this->order_id}' AND `product_id`='{$this->product_id}' LIMIT 1;");
    }

    public function get_products() {
        return $this->connection->q("SELECT * FROM `orders_products` WHERE `order_id` = '{$this->order_id}' LIMIT {$this->start}, {$this->total};");
    }
    
    public function create()
    {
        $this->connection->q("INSERT INTO `mydb`.`orders_products`(`order_id`,`product_id`,`quantity`,`subtotal`) VALUES ( '{$this->order_id}','{$this->product_id}','{$this->quantity}','{$this->subtotal}'); ");
    }
    
    public function delete()
    {
        $this->connection->q("DELETE FROM `mydb`.`orders_products` WHERE `order_id`='{$this->order_id}' AND `product_id` = '{$this->product_id}'");
    }
    
    



}

?>