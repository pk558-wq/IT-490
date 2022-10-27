<?php
   include_once 'config.php';

   function connect_to_db() {
       // Create connection
       $conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE);
       // Check connection
       if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
       } 
       return $conn;
   }

   function close_db($conn) {
      $conn->close();
   }
?>
