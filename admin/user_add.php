<?php
require_once "_auth.php";

$error = "";
if (isset($_POST["save"])) {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "student";
    $status = $_POST["status"] ?? "active";

    if ($name === "" || $email === "" || $password === "") {
        $error = "Name, email and password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (!in_array($role, ["admin", "instructor", "student"], true)) {
        $error = "Invalid role selected.";
    } elseif (!in_array($status, ["active", "inactive", "suspended"], true)) {
        $error = "Invalid status selected.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $hash, $phone, $role, $status);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: users.php?message=" . urlencode("User added successfully."));
            exit;
        } else {
            $error = ($conn->errno === 1062) ? "This email already exists." : "Could not add user. Please check the database.";
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Add User</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="admin-layout">
    <aside class="sidebar"><h2>Learn<span>Hub</span></h2><p class="side-label">ADMIN MENU</p><a href="admin_dashboard.php">Dashboard</a><a href="users.php">Manage Users</a><a class="active" href="user_add.php">Add New User</a><a href="../logout.php">Logout</a></aside>
    <main class="admin-main">
        <p class="muted">Admin / Users / Add</p><h1>Add New User</h1>
        <?php if ($error): ?><p class="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form class="panel form-panel" method="POST">
            <label>Full Name</label><input type="text" name="name" required value="<?= htmlspecialchars($_POST["name"] ?? "") ?>">
            <label>Email</label><input type="email" name="email" required value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
            <label>Phone</label><input type="text" name="phone" value="<?= htmlspecialchars($_POST["phone"] ?? "") ?>">
            <label>Password</label><input type="password" name="password" required minlength="6">
            <label>Role</label><select name="role"><option value="student">Student</option><option value="instructor">Instructor</option><option value="admin">Admin</option></select>
            <label>Status</label><select name="status"><option value="active">Active</option><option value="inactive">Inactive</option><option value="suspended">Suspended</option></select>
            <button class="btn" type="submit" name="save">Save User</button>
            <a class="btn secondary" href="users.php">Cancel</a>
        </form>
    </main>
</div>
</body>
</html>