<?php
// Local development connection settings (XAMPP/Laragon default).
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'online_learning';

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset('utf8mb4');
if ($conn->connect_error) {
    die('Database connection failed. Check db_config.php and ensure MySQL is running.');
}
?>
