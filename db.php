<?php

define('HOST', "sql101.ezyro.com");
define('DB_NAME', "ezyro_38518506_parser");
define('USERNAME', "ezyro_38518506");
define('PASSWORD', "");

define('TABLE_NAME', "topcoin_test_task");


$DB = null;
try {
    $DB = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME . ';charset=utf8', USERNAME, PASSWORD);
} catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}