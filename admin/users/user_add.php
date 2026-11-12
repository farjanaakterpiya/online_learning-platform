<?php include_once("../../db_config.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Entry</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="card">
        <h3>User Entry Form</h3>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Data received from entry form
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $role = $_POST['role'];
            $status = $_POST['status'];
            $action = $_POST['action'];
            // include_once("db_config.php"); // Database Connection

            //    echo "INSERT INTO allstudents 
            //     (id, name, email, phone) VALUES 
            //     (NULL, '$name', '$email', '$phone')";
            //     echo "<br>";

            $conn->query("INSERT INTO users 
                (id, name, email, phone,role, status, action) VALUES 
                (NULL, '$name', '$email', '$phone'$role, $status, $action)");

            if ($conn->affected_rows) {
                echo "<div class='message'>Success</div>";
            }
        }
        ?>
        <form action="" method="post">
            <input type="text" name="name" placeholder="Enter name"><br>
            <input type="text" name="email" placeholder="Enter email"><br>
            <input type="text" name="phone" placeholder="Enter phone"><br>
            <input type="text" name="role" placeholder="Enter role"><br>
            <input type="text" name="status" placeholder="Enter status"><br>
            <input type="text" name="action" placeholder="Enter action"><br>
            <input type="submit" name="submit" value="SAVE">
        </form>
        <br>
        <a class="link" href="index.php">Back </a><br><br>
    </div>
</body>

</html>