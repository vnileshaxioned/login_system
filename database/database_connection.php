<?php

define('HOST', 'localhost');
define('USER', 'phpmyadmin');
define('PASSWORD', 'root');
define('DATABASE_NAME', 'login_system');

try{
    $conn = new mysqli(HOST, USER, PASSWORD, DATABASE_NAME);

    if($conn->connect_error) {
        throw new Exception(die ('Database connection failed ' . $conn->connect_error));
    }
} catch (Exception $e) {
    echo $e->getMessage();
}


?>