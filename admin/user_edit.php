<?php include_once("../../db_config.php"); ?>
<?php
require_once "_auth.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: users.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, name, email, phone, role, status FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: users.php");
    exit;
}

$error = "";
if (isset($_POST["update"])) {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "";
    $status = $_POST["status"] ?? "";

    if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a name and valid email.";
    } elseif (!in_array($role, ["admin", "instructor", "student"], true) ||
              !in_array($status, ["active", "inactive", "suspended"], true)) {
        $error = "Invalid role or status.";
    } else {
        if ($password !== "") {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, role=?, status=?, password=? WHERE id=?");
            $stmt->bind_param("ssssssi", $name, $email, $phone, $role, $status, $hash, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, role=?, status=? WHERE id=?");
            $stmt->bind_param("sssssi", $name, $email, $phone, $role, $status, $id);
        }

        if ($stmt->execute()) {
            $stmt->close();
            if ($id === (int)$_SESSION["user_id"]) {
                $_SESSION["name"] = $name;
                $_SESSION["email"] = $email;
                $_SESSION["role"] = $role;
            }
            header("Location: users.php?message=" . urlencode("User updated successfully."));
            exit;
        } else {
            $error = ($conn->errno === 1062) ? "This email is already used by another account." : "Could not update user.";
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Edit User</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="admin-layout">
    <aside class="sidebar"><h2>Learn<span>Hub</span></h2><p class="side-label">ADMIN MENU</p><a href="admin_dashboard.php">Dashboard</a><a class="active" href="users.php">Manage Users</a><a href="user_add.php">Add New User</a><a href="../logout.php">Logout</a></aside>
    <main class="admin-main">
        <p class="muted">Admin / Users / Edit</p><h1>Edit User</h1>
        <?php if ($error): ?><p class="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <form class="panel form-panel" method="POST">
            <label>Full Name</label><input type="text" name="name" required value="<?= htmlspecialchars($_POST["name"] ?? $user["name"]) ?>">
            <label>Email</label><input type="email" name="email" required value="<?= htmlspecialchars($_POST["email"] ?? $user["email"]) ?>">
            <label>Phone</label><input type="text" name="phone" value="<?= htmlspecialchars($_POST["phone"] ?? ($user["phone"] ?? "")) ?>">
            <label>New Password (leave blank to keep current password)</label><input type="password" name="password" minlength="6">
            <label>Role</label><select name="role">
                <?php foreach (["admin"=>"Admin","instructor"=>"Instructor","student"=>"Student"] as $value=>$label): ?>
                <option value="<?= $value ?>" <?= (($_POST["role"] ?? $user["role"]) === $value) ? "selected" : "" ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <label>Status</label><select name="status">
                <?php foreach (["active"=>"Active","inactive"=>"Inactive","suspended"=>"Suspended"] as $value=>$label): ?>
                <option value="<?= $value ?>" <?= (($_POST["status"] ?? $user["status"]) === $value) ? "selected" : "" ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn" type="submit" name="update">Update User</button>
            <a class="btn secondary" href="users.php">Cancel</a>
        </form>
    </main>
</div>
</body>
</html>