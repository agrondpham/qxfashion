<?php

/* AUTHOR
 * 
 * 
 * MAIN CONTENT
 * 
 * Global settings and session or mysql
 * 
 * 
 * LOG
 * 
 * DateTime             Developer               Content
 * 31/03/2012           AP                      Get appName,shortappName to configuration                     
 * 
 */
//ini_set(display_errors,1);
ini_set('SMTP', 'localhost');
ini_set('sendmail_from', 'admin@xxx.com');
//error_reporting(E_ALL);
date_default_timezone_set('ASIA/Singapore');

require_once("libraries/database/mysql.php");  // mysql database class
require_once("libraries/tables/session.php");
require_once("libraries/tables/customer.php");

$mysql = new mysql();
$mysql->appname ="SaleStore";// name of website
$mysql->appshortname="SS";//short name
$mysql->server = "localhost";
$mysql->user = "root";
$dbName="mydb";
$mysql->database = $dbName; //set name of database by global varibale
$mysql->set_password("123456");
$mysql->connect();
$mysql->q("SET NAMES 'UTF8';");


$title = "";
$sidebar = "";
$header = "";
$menu = "";
$body = "";
$footer = "";

session_start();

if (isset($_SESSION['customer'])) {
    $CurrentSession = $_SESSION['customer'];
    $CurrentSession->connection = $mysql;
    $CurrentSession->updated = date("Y-m-d H:i:s",time());
   
        
    if($CurrentSession->id == NULL && $CurrentSession->customer_id == NULL)
    {
        $CurrentSession->id = 0;
        $CurrentSession->customer_id = 0;
    }

} else {
    // first time
    $CurrentSession = new customer();
    $CurrentSession->session_id = session_id();
    $CurrentSession->created = date("Y-m-d H:i:s",time());
    $CurrentSession->updated = date("Y-m-d H:i:s",time());
    $CurrentSession->ip = $_SERVER["REMOTE_ADDR"];
    $CurrentSession->page = $_SERVER["REQUEST_URI"];
    $CurrentSession->create_session();
    
    
    $_SESSION["customer"] = $CurrentSession;  
}


?>