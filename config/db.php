<?php 
    class Database {

        public static function connect()
        {
            $host = "localhost";
            $dbname = "bookstore";
            $username = "root";
            $password = "";

            $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        }



    }

 
?>
