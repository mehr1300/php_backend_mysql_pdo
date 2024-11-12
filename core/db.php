<?php
function connect(){
    try {
        $conn = new PDO("mysql:host=".SERVERNAME_DB.";dbname=".DATABASE_DB.";charset=utf8",USERNAME_DB, PASSWORD_DB);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
        //    echo "Connected successfully";
    } catch(PDOException $e) {
        //    echo "Connection failed: " . $e->getMessage();
    }
}
