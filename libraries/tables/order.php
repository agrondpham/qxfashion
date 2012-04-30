<?php

class order_status {
    const Active = "Active";
    const InActive = "InActive";
    const Confirmed = "Confirmed";
}

/*
  FIELD           TYPE                       COLLATION          NULL    KEY     DEFAULT  Extra           PRIVILEGES                       COMMENT
  --------------  -------------------------  -----------------  ------  ------  -------  --------------  -------------------------------  -------
  order_id        INT(11) UNSIGNED           (NULL)             NO      PRI     (NULL)   AUTO_INCREMENT  SELECT,INSERT,UPDATE,REFERENCES
  customer_id     INT(11) UNSIGNED           (NULL)             NO      PRI     (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  STATUS          ENUM('Active','InActive')  latin1_swedish_ci  NO              Active                   SELECT,INSERT,UPDATE,REFERENCES
  order_date      DATETIME                   (NULL)             NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  NAME            VARCHAR(45)                latin1_swedish_ci  NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  shipaddress     VARCHAR(300)               latin1_swedish_ci  NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  payment_type    VARCHAR(45)                latin1_swedish_ci  NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  payment_amount  VARCHAR(45)                latin1_swedish_ci  NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES

 */

class order {

    public $order_id;
    public $session_id;
    public $customer_id;
    public $customer_creditcard_id;
    public $status;
    public $order_date;
    public $name;
    public $shipaddress;
    public $payment_type;
    public $payment_amount;

    public function __construct() {
        global $mysql;
        $this->connection = $mysql;
    }

    public function set_object($obj) {
        $this->order_id = $obj->id;
        $this->session_id = $obj->session_id;
        $this->customer_id = $obj->customer_id;
        $this->customer_creditcard_id = $obj->customer_creditcard_id;
        $this->status = $obj->status;
        $this->order_date = $obj->order_date;
        $this->name = $obj->name;
        $this->shipaddress = $obj->shipaddress;
        $this->payment_type = $obj->payment_type;
        $this->payment_amount = $obj->payment_amount;
    }

    public static function static_set_object($obj) {
        $order = new order();
        $order->order_id = $obj->order_id;
        $order->session_id = $obj->session_id;
        $order->customer_id = $obj->customer_id;
        $order->customer_creditcard_id = $obj->customer_creditcard_id;
        $order->status = $obj->status;
        $order->order_date = $obj->order_date;
        $order->name = $obj->name;
        $order->shipaddress = $obj->shipaddress;
        $order->payment_type = $obj->payment_type;
        $order->payment_amount = $obj->payment_amount;

        return $order;
    }

    public function get() {
        $obj = $this->connection->qfo("SELECT * FROM {$GLOBALS["dbName"]}.`orders` WHERE id = '{$this->order_id}'");
        $this->set_object($obj);
    }

    public function get_orders() {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`orders` WHERE `session_id`='{$this->session_id}' ORDER BY `order_id` DESC;");
    }
    
    public function get_customer_orders()
    {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`orders` WHERE `customer_id`='{$this->customer_id}' ORDER BY `order_id` DESC;");
    }

    public function create() {
        $this->connection->q("INSERT INTO {$GLOBALS["dbName"]}.`orders`(`order_id`,`session_id`,`customer_id`,`customer_creditcard_id`,`status`,`order_date`,`name`,`shipaddress`,`payment_type`,`payment_amount`)VALUES
            ( NULL,'$this->session_id','{$this->customer_id}','{$this->customer_creditcard_id}','{$this->status}','{$this->order_date}','{$this->name}','{$this->shipaddress}','{$this->payment_type}','{$this->payment_amount}');");
        $this->order_id = $this->connection->iid();
    }

    public function update() {
        return $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`orders`
            SET
                `session_id` = '{$this->session_id}',
            `customer_id` = '{$this->customer_id}',
            `customer_creditcard_id` = '{$this->customer_creditcard_id}'
            `status` = '{$this->status}',
            `order_date` = '{$this->order_date}',
            `name` = '{$this->name}',
            `shipaddress` = '{$this->shipaddress}',
            `payment_type` = '{$this->payment_type}',
            `payment_amount` = '{$this->payment_amount}'
            WHERE `order_id` = '{$this->order_id}';");
    }

    public function delete() {
        return $this->connection->q("DELETE FROM {$GLOBALS["dbName"]}.`orders` WHERE `id`='{$this->order_id}';");
    }

}

?>