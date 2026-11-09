<?php
require_once "_auth.php";

$sql = "SELECT id, name, email, phone, role, status, created_at FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - LearnHub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="admin-layout">
    <aside class="sidebar">
        <h2>Learn<span>Hub</span></h2>
        <p class="side-label">ADMIN MENU</p>
        <a href="admin_dashboard.php">Dashboard</a>
        <a class="active" href="users.php">Manage Users</a>
        <a href="user_add.php">Add New User</a>
        <a href="../logout.php">Logout</a>
    </aside>
    <main class="admin-main">
        <div class="page-head">
            <div><p class="muted">Admin / Users</p><h1>Manage Users</h1></div>
            <a class="btn" href="user_add.php">+ Add User</a>
        </div>
        <?php if (isset($_GET["message"])): ?><p class="success"><?= htmlspecialchars($_GET["message"]) ?></p><?php endif; ?>
        <div class="panel table-wrap">
            <table>
                <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$user["id"] ?></td>
                        <td><?= htmlspecialchars($user["name"]) ?></td>
                        <td><?= htmlspecialchars($user["email"]) ?></td>
                        <td><?= htmlspecialchars($user["phone"] ?? "") ?></td>
                        <td><?= htmlspecialchars(ucfirst($user["role"])) ?></td>
                        <td><?= htmlspecialchars(ucfirst($user["status"])) ?></td>
                        <td class="actions">
                            <a class="small-btn" href="user_edit.php?id=<?= (int)$user["id"] ?>">Edit</a>
                            <?php if ((int)$user["id"] !== (int)$_SESSION["user_id"]): ?>
                            <form method="POST" action="user_delete.php" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <input type="hidden" name="id" value="<?= (int)$user["id"] ?>">
                                <button class="small-btn danger" type="submit">Delete</button>
                            </form>
                            <?php else: ?><span class="muted">Your account</span><?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7">No users found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <p><a href="admin_dashboard.php">← Back to Dashboard</a></p>
    </main>
</div>
</body>
</html>