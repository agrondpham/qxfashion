<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ItemType {
    public $ItmTypeID;
    public $Name;
    
    public function __construct()
    {
        global $mysql;
        $this->connection = $mysql;
        $this->limit = 10;
        $this->start = 0;
    }
    
    public static function get_object($obj)
    {
        $ItemType = new ItemType();
        $ItemType->ItmTypeID = (isset($obj->ItmTypeID))? $obj->ItmTypeID : "";
        $ItemType->Name = (isset($obj->Name))? $obj->Name : "";
        return $ItemType;
    }
    
    
    public function get()
    {
        return $this->connection->q("SELECT * FROM `t_item_type`");
    }
    
    
}
?>
