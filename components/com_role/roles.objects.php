<?php

class roles_objects {

    public $ObjectID;
    public $ObjectDescription;
    public $ObjectName;
    public $mysql;

    public function __construct() {
        global $mysql;
        $this->mysql = $mysql;
    }

    /**
     * @param $ObjectName the $ObjectName to set
     */
    public function setObjectName($ObjectName) {
        $this->ObjectName = $ObjectName;
    }

    /**
     * @param $ObjectDescription the $ObjectDescription to set
     */
    public function setObjectDescription($ObjectDescription) {
        $this->ObjectDescription = $ObjectDescription;
    }

    /**
     * @param $ObjectID the $ObjectID to set
     */
    public function setObjectID($ObjectID) {
        $this->ObjectID = $ObjectID;
    }

    /**
     * @return the $ObjectName
     */
    public function getObjectName() {
        return $this->ObjectName;
    }

    /**
     * @return the $ObjectDescription
     */
    public function getObjectDescription() {
        return $this->ObjectDescription;
    }

    /**
     * @return the $ObjectID
     */
    public function getObjectID() {
        return $this->ObjectID;
    }

    public function convert_object_details($object) {
        $this->ObjectID = $object->ID;
        $this->ObjectDescription = $object->Description;
        $this->ObjectName = $object->Name;
    }

    public function get_object() {
        $sql = "SELECT * FROM {$GLOBALS["dbName"]}.roles_objects WHERE ID='{$this->ObjectID}'";
        $sql_query = $this->mysql->q($sql);
        if ($this->mysql->n($sql) > 0) {
            $sql_object = $this->mysql->fo($sql_query);
            $this->convert_object_details($sql_object);
            return true;
        }
        else
            return false;
    }

    public function get_object_by_name() {
        $sql = "SELECT * FROM {$GLOBALS["dbName"]}.roles_objects WHERE `Name`='{$this->ObjectName}'";
        $sql_query = $this->mysql->q($sql);
        if ($this->mysql->n($sql) > 0) {
            $sql_object = $this->mysql->fo($sql_query);
            $this->convert_object_details($sql_object);
            return true;
        }
        else
            return false;
    }

    public function get_objects() {
        $sql = "SELECT * FROM {$GLOBALS["dbName"]}.roles_objects";
        return $this->mysql->q($sql);
    }

    public function create_object() {
        $sql = "
		INSERT INTO {$GLOBALS["dbName"]}.roles_objects (ID, `Name`, `Description`) VALUES (NULL, '{$this->ObjectName}','{$this->isSingular}');
		";

        return $this->mysql->q($sql);
    }

    public function update_object() {
        $sql = "UPDATE {$GLOBALS["dbName"]}.roles_object SET `Name`='{$this->ObjectName}',Description='{$this->ObjectDescription}' WHERE ID='{$this->ObjectID}'";
        $this->mysql->q($sql);
    }

    public function delete_object() {
        
    }

}

?>