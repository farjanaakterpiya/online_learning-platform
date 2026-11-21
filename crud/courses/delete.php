<?php
session_start();
if (!isset($_SESSION['email']) || !in_array($_SESSION['role'] ?? '', ['admin', 'instructor'], true)) {
    header("Location: ../../login.php");
    exit;
}
include_once("../../db_config.php");
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header("Location: index.php?msg=error");
    exit;
}
$role = $_SESSION['role'];
if ($role === 'admin') {
    $stmt = $conn->prepare("DELETE FROM courses WHERE id=?");
    $stmt->bind_param("i", $id);
} else {
    $identity = $conn->prepare("SELECT id FROM users WHERE email=? AND role='instructor' LIMIT 1");
    $identity->bind_param("s", $_SESSION['email']);
    $identity->execute();
    $user = $identity->get_result()->fetch_assoc();
    if (!$user) {
        header("Location: ../../login.php");
        exit;
    }
    $instructorId = (int) $user['id'];
    $stmt = $conn->prepare("DELETE FROM courses WHERE id=? AND instructor_id=?");
    $stmt->bind_param("ii", $id, $instructorId);
}
try {
    $stmt->execute();
    header("Location: index.php?msg=" . ($stmt->affected_rows ? "deleted" : "notfound"));
} catch (mysqli_sql_exception $exception) {
    error_log($exception->getMessage());
    header("Location: index.php?msg=error");
}
exit;
