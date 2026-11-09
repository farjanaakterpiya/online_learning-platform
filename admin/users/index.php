<?php include_once("./db_config.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - LearnHub</title>
    <link rel="stylesheet" href="./assets/css/style.css">
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
        <?php 
           $rawData =  $conn->query("SELECT * FROM users"); ?>

        <table border="1" style="border-collapse: collapse;">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
            
        </tr>    
        <?php
           while($row = $rawData->fetch_assoc()){ ?>
              <tr>
                <td><?php echo  $row['id'] ?> </td>
                <td><?php echo  $row['name'] ?></td>
                <td><?php echo  $row['email'] ?></td>
                <td><?php echo  $row['phone'] ?></td>
                <td><?php echo  $row['Role'] ?></td>
                <td><?php echo  $row['Status'] ?></td>
                <td><?php echo  $row['Action'] ?></td>
                <td class="action">
                    <a class="edit" aria-label="Edit user" title="Edit user" href="user_edit.php?id=<?php echo  $row['id'] ?>">&#9998;</a> |
                    <a onclick="return confirm('Are you sure to delete')" class="danger" aria-label="Delete student" title="Delete user" href="user_delete.php?id=<?php echo  $row['id'] ?>">&#128465;</a>
                </td>
              </tr>
          <?php 
            }    
        ?>
        </table>
    </div>
</body>
</html>
</body>
</html>