<?php
require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
define('DB_NAME', $_ENV['DB_NAME']);


function getDbConnection(){
    try{
        return new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }catch(Exception $error){
        echo "Error: " . $error->getMessage();
    }
}


getDbConnection();