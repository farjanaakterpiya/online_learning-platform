<?php
session_start();
if (!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin') { header("Location: ../../login.php"); exit; }
include_once("../../db_config.php");
$id=(int)($_GET['id']??0);
$stmt=$conn->prepare("DELETE FROM certificates WHERE id=?");
$stmt->bind_param("i",$id);
try {
    $stmt->execute();
    header("Location:index.php?msg=deleted");
} catch (mysqli_sql_exception $exception) {
    error_log($exception->getMessage());
    header("Location:index.php?msg=error");
}
exit;