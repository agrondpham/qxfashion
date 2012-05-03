<?php

class roles_actions {

    public $ActionID;
    public $ActionName;
    public $ActionDescription;
    public $mysql;

    public function __construct() {
        global $mysql;
        $this->mysql = $mysql;
    }

    /**
     * @return the $ActionID
     */
    public function getActionID() {
        return $this->ActionID;
    }

    /**
     * @return the $ActionName
     */
    public function getActionName() {
        return $this->ActionName;
    }

    /**
     * @return the $ActionDescription
     */
    public function getActionDescription() {
        return $this->ActionDescription;
    }

    /**
     * @param $ActionID the $ActionID to set
     */
    public function setActionID($ActionID) {
        $this->ActionID = $ActionID;
    }

    /**
     * @param $ActionName the $ActionName to set
     */
    public function setActionName($ActionName) {
        $this->ActionName = $ActionName;
    }

    /**
     * @param $ActionDescription the $ActionDescription to set
     */
    public function setActionDescription($ActionDescription) {
        $this->ActionDescription = $ActionDescription;
    }

    public function convert_action_details($object) {
        $this->ActionID = $object->ID;
        $this->ActionName = $object->Name;
        $this->ActionDescription = $object->Description;
    }

    public function get_action() {
        $sql = "select * from roles_actions where ID='{$this->ActionID}'";
        $sql_query = $this->mysql->q($sql);
        if ($this->mysql->n($sql_query) > 0) {
            $sql_object = $this->mysql->fo($sql_query);
            $this->convert_action_details($sql_object);
            return true;
        }
        else
            return false;
    }

    public function get_actions() {
        $sql = "select * from {$GLOBALS["dbName"]}.roles_actions";
        return $this->mysql->q($sql);
    }

    public function create_action() {
        $sql = "INSERT INTO {$GLOBALS["dbName"]}.roles_actions (ID, `Name`, Description) VALUES (NULL, '{$this->ActionName}','{$this->ActionDescription}')";
        return $this->mysql->q($sql);
    }

    public function update_action() {
        $sql = "UPDATE {$GLOBALS["dbName"]}.roles_actions SET `Name`='{$this->ActionName}', Description ='{$this->Description}' WHERE ID='{$this->ActionID}'";
        return $this->mysql->q($sql);
    }

    public function delete_object() {
        
    }

}

?>