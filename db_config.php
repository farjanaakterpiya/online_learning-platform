<?php
// Connect to the local online_learning database.
$host = "localhost";
$user = "root";
$pass = "";
$db = "online_learning";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>