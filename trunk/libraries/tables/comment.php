<?php

/*
 * Created by Agrond Pham
 * 
 * AG       4/1/2012    
 * 
 * 
 * 
 */


class Comment{
    public $id;
    public $prdid;
    public $comments;
    public $connection;
    public $name;
    public $email;
    public $modifiedDate;
    
    public function __construct() {
        global $mysql;
        $this->connection= $mysql;
        $this->prid = 0;
        $this->id=0;
        $this->comments="";
        $this->name="Anonymous";
        $this->email="";
        $this->modifiedDate="2012-01-01 12:00";
    }
    public function set_object($obj) {
        $this->id = $obj->id;
        $this->prdid = $obj->prdid;
        $this->comments = $obj->comments;
        $this->name= $obj->name;
        $this->email=$obj->email;
        $this->modifiedDate=$obj->modifiedDate;
    }
    public static function static_set_object($obj) {
        $comment = new Comment ();
        $comment->set_object($obj);

        return $comment;
    }
    public function create() {
        $this->connection->q("INSERT INTO `comments`(`id`,`prdid`,`comment`) 
                                VALUES ( NULL,'{$this->prdid}','{$this->comment}');");
        $this->id = $this->connection->iid();
    }

    public function get() {
        $obj = $this->connection->qfo("SELECT 
    t_comments.id,
    t_comments.name,
    t_comments.email,
     DATE_FORMAT(t_comments.modifiedDate,'%d %b %Y   at  %H:%i') as 'modifiedDate',
    t_comments.comments,
    t_comments.prdid
    FROM
        t_comments
	WHERE prdid = '{$this->prdid}'
    ORDER BY t_comments.modifiedDate DESC;");
        $this->set_object($obj);
    }
    public function get_comment_cout() {
        $obj = $this->connection->qfo("SELECT count(*) FROM `t_comments` WHERE prdid = '{$this->prdid}';");
        $this->set_object($obj);
    }
/*    public static function get_comment($prdid) {
        $comment = new Comment();
        $comment->prdid = $prdid;
        $comment->get();
        return $comment;
    }*/
    public function get_comments($prdid) {
        return $this->connection->q("SELECT 
    t_comments.id,
    t_comments.name,
    t_comments.email,
     DATE_FORMAT(t_comments.modifiedDate,'%d %b %Y   at  %H:%i') as 'modifiedDate',
    t_comments.comments,
    t_comments.prdid
    FROM
        t_comments
    WHERE prdid = '{$prdid}'
	ORDER BY t_comments.modifiedDate DESC;");
    }
    public function update() {
        return $this->connection->q("UPDATE {$GLOBALS["dbName"]}.`t_comments` SET `comments`='{$this->comment}' WHERE `id`='{$this->id}';");
    }
}
?>
