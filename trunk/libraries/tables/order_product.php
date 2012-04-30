<?php

class order_product {

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
        $this->subtotal = $obj->subtotal;
    }

    public static function static_set_object($obj) {
        $op = new order_product();
        $op->order_id = $obj->order_id;
        $op->product_id = $obj->product_id;
        $op->quantity = $obj->quantity;
        $op->subtotal = $obj->subtotal;

        return $op;
    }
    
    public function get()
    {
        $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`orders_products` WHERE `order_id` = '{$this->order_id}' AND `product_id`='{$this->product_id}' LIMIT 1;");
    }

    public function get_products() {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`orders_products` WHERE `order_id` = '{$this->order_id}' LIMIT {$this->start}, {$this->limit};");
    }
    
    public function create()
    {
        $this->connection->q("INSERT INTO {$GLOBALS["dbName"]}.`orders_products`(`order_id`,`product_id`,`quantity`,`subtotal`) VALUES ( '{$this->order_id}','{$this->product_id}','{$this->quantity}','{$this->subtotal}'); ");
    }
    
    public function delete()
    {
        $this->connection->q("DELETE FROM {$GLOBALS["dbName"]}.`orders_products` WHERE `order_id`='{$this->order_id}' AND `product_id` = '{$this->product_id}'");
    }
    
    



}

?>