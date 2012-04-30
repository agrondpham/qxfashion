<?php

class cart_status
{
    const Active = "Active";
    const Deleted = "Deleted";
    const Checking = "Checking";
    const Paid = "Paid";
}

class customers_cart
{
    /*
     FIELD        TYPE                                        COLLATION          NULL    KEY     DEFAULT  Extra   PRIVILEGES                       COMMENT
-----------  ------------------------------------------  -----------------  ------  ------  -------  ------  -------------------------------  -------
customer_id  INT(11) UNSIGNED                            (NULL)             NO      PRI     (NULL)           SELECT,INSERT,UPDATE,REFERENCES         
product_id   INT(11) UNSIGNED                            (NULL)             NO      PRI     (NULL)           SELECT,INSERT,UPDATE,REFERENCES         
quantity     INT(11) UNSIGNED                            (NULL)             NO              (NULL)           SELECT,INSERT,UPDATE,REFERENCES         
STATUS       ENUM('Active','Deleted','Checking','Paid')  latin1_swedish_ci  NO              Active           SELECT,INSERT,UPDATE,REFERENCES         
created      DATETIME                                    (NULL)             NO              (NULL)           SELECT,INSERT,UPDATE,REFERENCES         

    */
    
    public $session_id;
    public $customer_id;
    public $product_id;
    public $quantity;
    public $status;
    public $created;
    
    public $connection;
    public $limit;
    public $start;
    
    public function __construct()
    {
        global $mysql;
        
        $this->status = cart_status::Active;
        $this->connection = $mysql;
        $this->customer_id = 0;
        $this->limit = 10;
        $this->start = 0;
    }
    
    public function set_object($obj)
    {
        $this->session_id = $obj->session_id;
        $this->customer_id = $obj->customer_id;
        $this->product_id = $obj->product_id;
        $this->quantity = $obj->quantity;
        $this->status = $obj->status;
    }
    
    public static function static_set_object($obj)
    {
        $cart = new customers_cart();
        $cart->session_id = $obj->session_id;
        $cart->customer_id = $obj->customer_id;
        $cart->product_id = $obj->product_id;
        $cart->quantity = $obj->quantity;
        $cart->status = $obj->status;
        return $cart;
    }
    
    public function get()
    {
        $obj = $this->connection->qfo("SELECT * FROM {$GLOBALS["dbName"]}.`customers_cart` WHERE `session_id` = '{$this->session_id}' AND `product_id` = '{$this->product_id}' LIMIT 1;");
        $this->set_object($obj);
    }
    
    public function get_session_cart()
    {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`customers_cart` WHERE `session_id` = '{$this->session_id}' AND `status` = '{$this->status}' LIMIT {$this->start},{$this->limit};");
    }
    
    public function get_customer_cart()
    {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`customers_cart` WHERE `customer_id` = '{$this->customer_id}' AND `status` = '{$this->status}' LIMIT {$this->start},{$this->limit};");
    }
    
    public function create()
    {
        $this->connection->q("INSERT INTO {$GLOBALS["dbName"]}.`customers_cart`(`session_id`,`customer_id`,`product_id`,`quantity`,`status`,`created`) VALUES ( '$this->session_id','{$this->customer_id}','{$this->product_id}','{$this->quantity}','Active',NOW())
        ON DUPLICATE KEY UPDATE `status`='{$this->status}',`quantity` = `quantity`+1, `customer_id` = '{$this->customer_id}'
        ;");
    }
    
    public function update()
    {
        $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`customers_cart` SET `customer_id` = '{$this->customer_id}',`quantity`='{$this->quantity}',`status`='{$this->status}' WHERE `session_id`='{$this->session_id}' AND `product_id`='{$this->product_id}';");
    }
    
    public function update_customer_cart()
    {
        $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`customers_cart` SET `session_id` = '{$this->session_id}',`quantity`='{$this->quantity}',`status`='{$this->status}' WHERE `customer_id`='{$this->customer_id}' AND `product_id`='{$this->product_id}';");
    }
    
    public function delete()
    {
        $this->connection->q("DELETE FROM {$GLOBALS["dbName"]}.`customers_cart` WHERE `session_id` = '{$this->session_id}' AND `product_id` = '{$this->product_id}';");
    }
    
    public function delete_customer_cart()
    {
        $this->connection->q("DELETE FROM {$GLOBALS["dbName"]}.`customers_cart` WHERE `customer_id`='{$this->customer_id}';");
    }
    
    public function delete_session_cart()
    {
        $this->connection->q("DELETE FROM {$GLOBALS["dbName"]}.`customers_cart` WHERE `session_id`='{$this->session_id}';");
    }
}
?>