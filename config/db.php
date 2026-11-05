<?php
// Optional database connection. Update these values for your local MySQL setup.
$host = "localhost";
$dbname = "online_learning";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Database connection failed. Please check config/db.php.");
}
?>
