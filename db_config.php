<?php
    
    $host = "localhost";
    $user =  "root";
    $pass = "";
    $db = "online_learning";

    
    $conn = new mysqli($host, $user, $pass, $db);

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
     } 
   
?>