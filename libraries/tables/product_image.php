<?php
class product_image {
    public $product_id;
    public $image_id;
    
    public $connection;
    public $limit;
    public $start;


    public function __construct()
    {
        global $mysql;
        $this->connection = $mysql;
        $this->limit = 10;
        $this->start = 0;
    }
    
    public function set_object($obj)
    {
        $this->product_id = $obj->product_id;
        $this->image_id = $obj->image_id;
    }
    
    public function get()
    {
        $obj = $this->connection->qfo("SELECT * FROM {$GLOBALS["dbName"]}.`products_images` WHERE `product_id` = '{$this->product_id}' AND `image_id` = '{$this->image_id}' LIMIT 1;");
        $this->set_object($obj);
    }
    
    public function get_product_images()
    {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`products_images` WHERE `product_id` = '{$this->product_id}' LIMIT {$this->start}, {$this->limit};");
    }
    
    public function get_image_products()
    {
        return $this->connection->q("SELECT * FROM {$GLOBALS["dbName"]}.`products_images` WHERE `image_id` = '{$this->image_id}' LIMIT {$this->start}, {$this->limit};");
    }
    
    public function create()
    {
        return $this->connection->q("INSERT INTO {$GLOBALS["dbName"]}.`products_images`(`product_id`,`image_id`) VALUES ( '{$this->product_id}','{$this->image_id}');");
    }
    
    public function update($product_image)
    {
        $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`products_images` SET `product_id`='{$product_image->product_id}',`image_id`='{$product_image->image_id}' WHERE `product_id`='{$this->product_id}' AND `image_id`='{$this->image_id}';");
    }
    
    public function delete()
    {
        $this->connection->q("DELETE FROM {$GLOBALS["dbName"]}.`products_images` WHERE `product_id`='{$this->product_id}' AND `image_id`='{$this->image_id}';");
    }
}
?>