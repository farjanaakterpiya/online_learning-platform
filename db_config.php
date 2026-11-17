<?php
    
    $host = "localhost";
    $user =  "root";
    $pass = "";
    $db = "online_learning";

    
    $conn = new mysqli("localhost", "root", "", "online_learning");

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
     } 
   
?>