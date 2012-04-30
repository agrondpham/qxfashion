<?php

/*
  FIELD        TYPE          COLLATION          NULL    KEY     DEFAULT  Extra           PRIVILEGES                       COMMENT
  -----------  ------------  -----------------  ------  ------  -------  --------------  -------------------------------  -------
  id           INT(11)       (NULL)             NO      PRI     (NULL)   AUTO_INCREMENT  SELECT,INSERT,UPDATE,REFERENCES
  NAME         VARCHAR(45)   latin1_swedish_ci  NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  shipaddress  VARCHAR(300)  latin1_swedish_ci  NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  dob          DATETIME      (NULL)             NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  email        VARCHAR(45)   latin1_swedish_ci  NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  createdon     DATETIME      (NULL)             NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  PASSWORD     VARCHAR(45)   latin1_swedish_ci  NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  lastlogin    DATETIME      (NULL)             NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES

 */

class customer extends session {

    public $id;
    public $name;
    public $shipaddress;
    public $dob;
    public $email;
    public $createdon;
    private $password;
    public $lastlogin;
    public $connection;

    public function __construct() {
        global $mysql;
        $this->connection = $mysql;
        $this->id = 0;
        $this->customer_id = 0;
        $this->limit = 10;
        $this->start = 0;
        $this->id = 0;
    }

    public function set_object($obj) {
        $this->id = intval($obj->id);
        $this->customer_id = intval($obj->id);
        $this->name = $obj->name;
        $this->shipaddress = $obj->shipaddress;
        $this->dob = $obj->dob;
        $this->email = $obj->email;
        $this->createdon = $obj->createdon;
        $this->password = $obj->password;
        $this->lastlogin = $obj->lastlogin;
    }

    public static function static_set_object($obj) {
        $customer = new customer();
        $customer->set_object($obj);

        return $customer;
    }
    
    public function set_password($password)
    {
        $this->password = $password;
    }
    
    public function get_password()
    {
        return $this->password;
    }

    public function get() {
        $obj = $this->connection->qfo("SELECT * FROM {$GLOBALS["dbName"]}.`customers` WHERE id = {$this->id}");
        $this->set_object($obj);
    }
    
    public function get_total_customers()
    {
        return $this->connection->qfo("SELECT count(0) as TOTAL FROM {$GLOBALS["dbName"]}.`customers`;")->TOTAL;
    }

    public function get_customers() {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`customers` LIMIT {$this->start}, {$this->limit};");
    }
    
    public function get_current_session()
    {
        $this->get_session();
    }

    public function has_customer_email() {
        $obj = $this->connection->qfo("SELECT count(0) as total FROM {$GLOBALS["dbName"]}.`customers` WHERE email = '{$this->email}'");
        if ($obj->total > 0)
            return true;
        else
            return false;
    }

    public function get_customer_by_email_password() {
        $obj = $this->connection->qfo("SELECT * FROM {$GLOBALS["dbName"]}.`customers` WHERE email = '{$this->email}' AND password = '{$this->password}' LIMIT 1;");
        if ($obj != NULL) {
            $this->set_object($obj);
        }
    }

    public function get_customer_by_email() {
        $obj = $this->connection->qfo("SELECT * from {$GLOBALS["dbName"]}.`customers` where `email` = '{$this->email}';");
        if (is_object($obj)) {
            $this->set_object($obj);
            return true;
        }
        else
            return false;
    }

    public function create() {
        $this->connection->q("INSERT INTO {$GLOBALS["dbName"]}.`customers`(`id`,`name`,`shipaddress`,`dob`,`email`,`createdon`,`password`,`lastlogin`) VALUES 
            ( NULL,'{$this->name}','{$this->shipaddress}','{$this->dob}','{$this->email}',NOW(),'{$this->password}',NOW());");

        $this->id = $this->connection->iid();
    }

    public function update() {
        return $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`customers` SET 
                                        `name`='{$this->name}',
                                            `shipaddress`='{$this->shipaddress}',
                                                `dob`='{$this->dob}',
                                                    `email`='{$this->email}',
                                                        `password`='{$this->password}' 
                                                            WHERE `id`='{$this->id}';");
    }

    public function update_lastlogin() {
        return $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`customers` SET 
                                        `lastlogin` = NOW()
                                            WHERE `id`='{$this->id}'");
    }

    public function delete() {
        return $this->connection->q("DELETE FROM {$GLOBALS["dbName"]}.`customers` WHERE `id`='{$this->id}';");
    }

}

?>