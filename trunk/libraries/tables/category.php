<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Category {
    public $id;
    public $name;
    public $description;
    public $parentid = 0;
    public $image;
    
    public $start;
    public $limit;
    
    public $connection;
    
    public function __construct()
    {
        global $mysql;
        $this->connection = $mysql;
        $this->limit = 10;
        $this->start = 0;
    }
    
    public static function get_object($obj)
    {
        $category = new Category();
        $category->id = (isset($obj->id))? $obj->id : "";
        $category->name = (isset($obj->name))? $obj->name : "";
        $category->description = (isset($obj->description))? $obj->description : "";
        $category->parentid = (isset($obj->parentid))? $obj->parentid : "";
        $category->image=(isset($obj->image))? $obj->image : "";
        return $category;
    }
    
    
    public function get()
    {
        $obj = $this->connection->qfo("SELECT * FROM {$GLOBALS["dbName"]}.`categories` WHERE id = {$this->id};");
        $this->name = $obj->name;
        $this->parentid = $obj->parentid;
        $this->description = $obj->description;
        $this->image=$obj->image;
    }
    
    public function get_total_categories()
    {
        return $this->connection->qfo("SELECT count(0) as TOTAL FROM {$GLOBALS["dbName"]}.`categories`;")->TOTAL;
    }
    
    public function get_categories()
    {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`categories` LIMIT {$this->start}, {$this->limit};");
    }
//    public function get_categories()
//    {
//        return $this->connection->q("SELECT  `categories`.`id`,`categories`.`name`,`categories`.`description`,`categories`.`parentid`,`images`.`link` as 'image' FROM `categories` INNER JOIN `t_image_category` on `categories`.`id`= `t_image_category`.`categoryID` INNER JOIN `images` on `images`.`id`=`t_image_category`.`imageID`");
//    }
    
    public function get_parent_categories()
    {
        return $this->connection->q("SELECT  `categories`.`id`,`categories`.`name`,`categories`.`description`,`categories`.`parentid`,`images`.`link` as 'image' FROM `categories` INNER JOIN `t_image_category` on `categories`.`id`= `t_image_category`.`categoryID` INNER JOIN `images` on `images`.`id`=`t_image_category`.`imageID` WHERE parentid = 0;");
    }
    
    public function get_child()
    {
        
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`categories` WHERE parentid = {$this->id};");
    }
    
    public function create()
    {
        $this->connection->q("INSERT INTO {$GLOBALS["dbName"]}.`categories` ( `name`, `parentid`) VALUES ('{$this->name}','{$this->parentid}');");
        $this->id = $this->connection->iid();
    }

    public function update()
    {
        $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`categories` SET `name`='{$this->name}', `description`='{$this->description}',`parentid`='{$this->parentid}' WHERE id={$this->id}");
    }
    
    public function delete()
    {
        $this->connection->q("DELETE FROM {$GLOBALS["dbName"]}.`categories` WHERE id = {$this->id};");
    }
}




?>