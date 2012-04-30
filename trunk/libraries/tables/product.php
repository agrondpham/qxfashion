<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * FIELD        TYPE           COLLATION          NULL    KEY     DEFAULT  Extra           PRIVILEGES                       COMMENT
  -----------  -------------  -----------------  ------  ------  -------  --------------  -------------------------------  -------
  id           INT(11)        (NULL)             NO      PRI     (NULL)   AUTO_INCREMENT  SELECT,INSERT,UPDATE,REFERENCES
  NAME         VARCHAR(45)    latin1_swedish_ci  NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  stock        INT(11)        (NULL)             NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  price        VARCHAR(45)    latin1_swedish_ci  NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  discount     DECIMAL(10,2)  (NULL)             NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  description  VARCHAR(300)   latin1_swedish_ci  NO              (NULL)                   SELECT,INSERT,UPDATE,REFERENCES
  category_id  INT(11)        (NULL)             NO      MUL     (NULL)                   SELECT,INSERT,UPDATE,REFERENCES

 */

/**
 * Description of product
 *
 * @author juidan
 */
//require_once("globalVariable.php");
class product {

    //put your code here
    public $id;
    public $category_id;
    public $image_id;
    public $stock;
    public $price;
    public $discount;
    public $name;
    public $description;
    public $modifiedDate;
    public $limit;
    public $start;
    public $connection;
    public $introduction;
    public $type_id;
    
    public function __construct() {
        global $mysql;
        $this->connection = $mysql;
        $this->limit = 10;
        $this->start = 0;
        $this->image_id = 0;
        $this->category_id = 0;
        $this->stock = "NULL";
        $this->price = "NULL";
        $this->discount = "NULL";
        $this->modifiedDate=date('Y-m-d H:i');
        $this->type="";
        $this->introduction="";
        $this->type_id="-1";
    }

    public function set_object($obj) {
        $this->id = $obj->id;
        $this->category_id = $obj->category_id;
        $this->image_id = $obj->image_id;
        $this->stock = $obj->stock;
        $this->price = $obj->price;
        $this->discount = $obj->discount;
        $this->name = $obj->name;
        $this->description = $obj->description;
        $this->modifiedDate= $obj->modifiedDate;
        $this->type=$obj->type;
        $this->introduction=$obj->introduction;
        $this->type_id=$obj->type_id;
                
    }

    public static function static_set_object($obj) {
        $product = new Product();
        $product->set_object($obj);

        return $product;
    }

    public function create() {
        $this->connection->q("INSERT INTO `t_items`(`id`,`name`,`stock`,`price`,`discount`,`description`,`CategoryID`,`ImageID`,`modifiedDate`,`ItmTypeID`) 
                                VALUES ( NULL,'{$this->name}',{$this->stock},{$this->price},{$this->discount},'{$this->description}',
                                    '{$this->category_id}','{$this->category_id}','{$this->modifiedDate}',-1);");
        $this->id = $this->connection->iid();
    }

    public function get() {
        $obj = $this->connection->qfo("SELECT 
    t_items.id,
    t_items.stock,
    t_items.price,
    t_items.discount,
    t_items.name,
    t_items.introduction,
    t_items.description,
    DATE_FORMAT(t_items.modifiedDate,'%d %b %Y,  %H:%i') as 'modifiedDate', 
    t_item_type.name as 'type',
    t_item_type.ItmTypeID as 'type_id',
    categories.name as 'category',
    categories.id as 'category_id',
    images.id as 'image_id'
    FROM
        t_items
    LEFT JOIN t_item_type
        ON (t_items.ItmTypeID = t_item_type.ItmTypeID)
    LEFT JOIN categories
        ON (t_items.CategoryID = categories.ID)
    LEFT JOIN images
        ON (t_items.ImageID=images.ID)
    WHERE
        (t_item_type.ItmTypeID=-2)
		AND (t_items.id = '{$this->id}')
	ORDER BY t_items.modifiedDate DESC;");
        $this->set_object($obj);
    }
    public function getnews() {
        $obj = $this->connection->qfo("SELECT 
    t_items.id,
    t_items.stock,
    t_items.price,
    t_items.discount,
    t_items.name,
    t_items.introduction,
    t_items.description,
    DATE_FORMAT(t_items.modifiedDate,'%d %b %Y,  %H:%i') as 'modifiedDate', 
    t_item_type.name as 'type',
    t_item_type.ItmTypeID as 'type_id',
    categories.name as 'category',
    categories.id as 'category_id',
    images.id as 'image_id'
    FROM
        t_items
    LEFT JOIN t_item_type
        ON (t_items.ItmTypeID = t_item_type.ItmTypeID)
    LEFT JOIN categories
        ON (t_items.CategoryID = categories.ID)
    LEFT JOIN images
        ON (t_items.ImageID=images.ID)
    WHERE
        (t_item_type.ItmTypeID=-1) AND (t_items.id = '{$this->id}')
    ORDER BY t_items.modifiedDate DESC;");
        $this->set_object($obj);
    }
    public function getall() {
        $obj = $this->connection->qfo("SELECT 
    t_items.id,
    t_items.stock,
    t_items.price,
    t_items.discount,
    t_items.name,
    t_items.introduction,
    t_items.description,
    DATE_FORMAT(t_items.modifiedDate,'%d %b %Y,  %H:%i') as 'modifiedDate', 
    t_item_type.name as 'type',
    t_item_type.ItmTypeID as 'type_id',
    categories.name as 'category',
    categories.id as 'category_id',
    images.id as 'image_id'
    FROM
        t_items
    LEFT JOIN t_item_type
        ON (t_items.ItmTypeID = t_item_type.ItmTypeID)
    LEFT JOIN categories
        ON (t_items.CategoryID = categories.ID)
    LEFT JOIN images
        ON (t_items.ImageID=images.ID) AND (t_items.id = '{$this->id}')
    ORDER BY t_items.modifiedDate DESC;");
        $this->set_object($obj);
    }
    public static function get_product($id) {
        $product = new product();
        $product->id = $id;
        $product->get();
        return $product;
    }
    
    public function get_total_products()
    {
        return $this->connection->qfo("SELECT count(*) as TOTAL FROM `t_items` WHERE ItmTypeID=-2")->TOTAL;
    }

    public function get_products() {
        return $this->connection->q("SELECT 
    t_items.id,
    t_items.stock,
    t_items.price,
    t_items.discount,
    t_items.name,
    t_items.introduction,
    t_items.description,
    DATE_FORMAT(t_items.modifiedDate,'%d %b %Y,  %H:%i') as 'modifiedDate', 
    t_item_type.name as 'type',
    t_item_type.ItmTypeID as 'type_id',
    categories.name as 'category',
    categories.id as 'category_id',
    images.id as 'image_id'
    FROM
        t_items
    LEFT JOIN t_item_type
        ON (t_items.ItmTypeID = t_item_type.ItmTypeID)
    LEFT JOIN categories
        ON (t_items.CategoryID = categories.ID)
    LEFT JOIN images
        ON (t_items.ImageID=images.ID)
    WHERE
        (t_item_type.ItmTypeID=-2)
    ORDER BY t_items.modifiedDate DESC LIMIT {$this->start},10;");
    }
    public function get_items() {
        return $this->connection->q("SELECT 
    t_items.id,
    t_items.stock,
    t_items.price,
    t_items.discount,
    t_items.name,
    t_items.introduction,
    t_items.description,
    DATE_FORMAT(t_items.modifiedDate,'%d %b %Y,  %H:%i') as 'modifiedDate', 
    t_item_type.name as 'type',
    t_item_type.ItmTypeID as 'type_id',
    categories.name as 'category',
    categories.id as 'category_id',
    images.id as 'image_id'
    FROM
        t_items
    LEFT JOIN t_item_type
        ON (t_items.ItmTypeID = t_item_type.ItmTypeID)
    LEFT JOIN categories
        ON (t_items.CategoryID = categories.ID)
    LEFT JOIN images
        ON (t_items.ImageID=images.ID)
    ORDER BY t_items.modifiedDate DESC LIMIT {$this->start},10;");
    }
    //AG
    public function get_topproduct() {
        return $this->connection->q("SELECT 
    t_items.id,
    t_items.stock,
    t_items.price,
    t_items.discount,
    t_items.name,
    t_items.introduction,
    t_items.description,
    DATE_FORMAT(t_items.modifiedDate,'%d %b %Y,  %H:%i') as 'modifiedDate', 
    t_item_type.name as 'type',
    t_item_type.ItmTypeID as 'type_id',
    categories.name as 'category',
    categories.id as 'category_id',
    images.id as 'image_id'
    FROM
        t_items
    LEFT JOIN t_item_type
        ON (t_items.ItmTypeID = t_item_type.ItmTypeID)
    LEFT JOIN categories
        ON (t_items.CategoryID = categories.ID)
    LEFT JOIN images
        ON (t_items.ImageID=images.ID)
    WHERE
        (t_item_type.ItmTypeID=-2)
    ORDER BY t_items.modifiedDate DESC LIMIT {$this->start},1;");
    }
    //AG
    public function get_news() {
        return $this->connection->q("SELECT 
    t_items.id,
    t_items.stock,
    t_items.price,
    t_items.discount,
    t_items.name,
    t_items.introduction,
    t_items.description,
    DATE_FORMAT(t_items.modifiedDate,'%d %b %Y,  %H:%i') as 'modifiedDate', 
    t_item_type.name as 'type',
    t_item_type.ItmTypeID as 'type_id',
    categories.name as 'category',
    categories.id as 'category_id',
    images.id as 'image_id'
    FROM
        t_items
    LEFT JOIN t_item_type
        ON (t_items.ItmTypeID = t_item_type.ItmTypeID)
    LEFT JOIN categories
        ON (t_items.CategoryID = categories.ID)
    LEFT JOIN images
        ON (t_items.ImageID=images.ID)
    WHERE
        (t_item_type.ItmTypeID=-1)
    ORDER BY t_items.modifiedDate DESC LIMIT {$this->start},10;");
    }

    public function search_product_name($keywords) {
        return $this->connection->q("SELECT 
    t_items.id,
    t_items.stock,
    t_items.price,
    t_items.discount,
    t_items.name,
    t_items.introduction,
    t_items.description,
    DATE_FORMAT(t_items.modifiedDate,'%d %b %Y,  %H:%i') as 'modifiedDate', 
    t_item_type.name as 'type',
    t_item_type.ItmTypeID as 'type_id',
    categories.name as 'category',
    categories.id as 'category_id',
    images.id as 'image_id'
    FROM
        t_items
    LEFT JOIN t_item_type
        ON (t_items.ItmTypeID = t_item_type.ItmTypeID)
    LEFT JOIN categories
        ON (t_items.CategoryID = categories.ID)
    LEFT JOIN images
        ON (t_items.ImageID=images.ID)
    WHERE
        (t_item_type.ItmTypeID=-2) AND (t_items.`name` LIKE  {$keywords})
    ORDER BY t_items.modifiedDate DESC;");
    }
    
    public function get_total_products_category()
    {
        return $this->connection->qfo("SELECT count(0) as TOTAL FROM `t_items` WHERE CategoryID = {$this->category_id} AND ItmTypeID=-2;")->TOTAL;
    }

    public function get_products_category() {
        return $this->connection->q("SELECT 
    t_items.id,
    t_items.stock,
    t_items.price,
    t_items.discount,
    t_items.name,
    t_items.introduction,
    t_items.description,
    DATE_FORMAT(t_items.modifiedDate,'%d %b %Y,  %H:%i') as 'modifiedDate', 
    t_item_type.name as 'type',
    t_item_type.ItmTypeID as 'type_id',
    categories.name as 'category',
    categories.id as 'category_id',
    images.id as 'image_id'
    FROM
        t_items
    LEFT JOIN t_item_type
        ON (t_items.ItmTypeID = t_item_type.ItmTypeID)
    LEFT JOIN categories
        ON (t_items.CategoryID = categories.ID)
    LEFT JOIN images
        ON (t_items.ImageID=images.ID)
    WHERE
        (t_item_type.ItmTypeID=-2) AND(t_items.CategoryID = {$this->category_id})
    ORDER BY t_items.modifiedDate DESC LIMIT {$this->start},10;");
    }

    public function update() {
        return $this->connection->q("UPDATE `t_items` SET `name`='{$this->name}',`stock`='{$this->stock}',
                                        `price`='{$this->price}',`discount`='{$this->discount}',`description`='{$this->description}',
                                            `CategoryID`='{$this->category_id}', `ImageID` = '{$this->image_id}' WHERE `id`='{$this->id}';");
    }
    
    public function update_image_id()
    {
        $this->connection->q("UPDATE `t_Items` SET `ImageID` = '{$this->image_id}' WHERE `id`='{$this->id}';");
    }
    
    public function update_stock()
    {
        $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`t_items` SET `stock` = '{$this->stock}' WHERE `id`='{$this->id}';");
    }

    public function delete() {
        return $this->connection->q("DELETE FROM `t_items` WHERE `id`='{$this->id}';");
    }
}

?>
