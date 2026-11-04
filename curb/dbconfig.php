<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "pwad731";

$conn = new mysqli ($host,$user,$pass,$db);
if(!$conn){
    die("Database connection failed:" . mysqli_connect_error());
}

?>