<?php

class DB
{
    static $conn;

    const USERNAME="root";
    const PASSWORD="root666";
    const HOST="localhost";
    const DB="spyshop";

    public static function init(){
        if(!self::$conn) {
            $username = self::USERNAME;
            $password = self::PASSWORD;
            $host = self::HOST;
            $db = self::DB;
            self::$conn = new \PDO("mysql:dbname=$db;host=$host", $username, $password);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
    }
}