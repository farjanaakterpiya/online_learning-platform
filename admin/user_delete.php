<?php
require_once "_auth.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: users.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
if (!$id || $id === (int)$_SESSION["user_id"]) {
    header("Location: users.php?message=" . urlencode("You cannot delete this account."));
    exit;
}

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    $message = $stmt->affected_rows > 0 ? "User deleted successfully." : "User was not found.";
} else {
    // Some users may be linked to courses or other records by foreign keys.
    $message = "This user cannot be deleted because related records may exist.";
}
$stmt->close();

header("Location: users.php?message=" . urlencode($message));
exit;
?>