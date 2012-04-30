<?php

/*
  FIELD    TYPE          COLLATION          NULL    KEY     DEFAULT  Extra   PRIVILEGES                       COMMENT
  -------  ------------  -----------------  ------  ------  -------  ------  -------------------------------  -------
  id       VARCHAR(50)   latin1_swedish_ci  NO      PRI     (NULL)           SELECT,INSERT,UPDATE,REFERENCES
  ip       VARCHAR(15)   latin1_swedish_ci  NO              (NULL)           SELECT,INSERT,UPDATE,REFERENCES
  created  DATETIME      (NULL)             NO              (NULL)           SELECT,INSERT,UPDATE,REFERENCES
  updated  DATETIME      (NULL)             NO              (NULL)           SELECT,INSERT,UPDATE,REFERENCES
  page     VARCHAR(255)  latin1_swedish_ci  NO              (NULL)           SELECT,INSERT,UPDATE,REFERENCES

 */

class session {

    public $session_id;
    public $customer_id;
    public $ip;
    public $created;
    public $updated;
    public $page;
    public $connection;
    public $limit;
    public $start;

    public function __construct() {
        global $mysql;
        $this->connection = $mysql;
        $this->limit = 10;
        $this->start = 0;
        $this->customer_id = 0;
    }

    public function set_session_object($obj) {
        $this->session_id = $obj->id;
        $this->customer_id = $obj->customer_id;
        $this->ip = $obj->ip;
        $this->created = $obj->created;
        $this->updated = $obj->updated;
        $this->page = $obj->page;
    }

    public static function static_set_object($obj) {
        $session = new session();
        $session->session_id = $obj->id;
        $session->customer_id = $obj->customer_id;
        $session->ip = $obj->ip;
        $session->created = $obj->created;
        $session->updated = $obj->updated;
        $session->page = $obj->page;
        return $session;
    }

    public function get_session() {
        $obj = $this->connection->qfo("SELECT * FROM {$GLOBALS["dbName"]}.`sessions` WHERE `id` = '{$this->session_id}' LIMIT 1;");
        $this->set_object($obj);
    }

    public function get_sessions() {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`sessions` LIMIT {$this->start}, {$this->limit};");
    }

    public function create_session() {
        $this->connection->q("INSERT INTO {$GLOBALS["dbName"]}.`sessions`(`id`,`customer_id`,`ip`,`created`,`updated`,`page`) VALUES ( '{$this->session_id}','{$this->customer_id}','{$this->ip}','{$this->created}','{$this->updated}','{$this->page}') ON DUPLICATE KEY UPDATE `id` = '{$this->session_id}', `updated` = '{$this->updated}';");
    }

    public function update_session() {
        $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`sessions` SET `customer_id` = '{$this->customer_id}',`ip`='{$this->ip}', `updated` = '{$this->updated}', `page`='{$this->page}' WHERE `id`='{$this->session_id}';");
    }

    public function update_page() {
        $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`sessions` SET `page`='{$this->page}' WHERE `id`='{$this->session_id}'");
    }

    public function delete_session() {
        $this->connection->q("DELETE FROM {$GLOBALS["dbName"]}.`sessions` WHERE `id`='{$this->session_id}';");
    }

}

?>