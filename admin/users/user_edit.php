<?php include_once("./db_config.php");  // Database Connection ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>user entry</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="card">
        <h3>user update form</h3>
        <?php 
            // Display Student Record
            $id = $_GET['id']; 
            
           $data = $conn->query("SELECT * FROM users WHERE id = '$id'");
           $row = $data->fetch_object();
            
           //echo $row->name;
            
            
            // Update Student Record
            if($_SERVER['REQUEST_METHOD']=='POST'){
               // Data received from entry form
                $name = $_POST['name'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $role = $_POST['role'];
                $status = $_POST['status'];
                $action= $_POST['action'];
               
                //Update Query

                $conn->query("UPDATE students SET name ='$name',email='$email', phone='$phone' role='$role',status='status', action='$action', WHERE id='$id'");

            //    echo "INSERT INTO allstudents 
            //     (id, name, email, phone) VALUES 
            //     (NULL, '$name', '$email', '$phone')";
            //     echo "<br>";

              
                 if($conn->affected_rows){
                    echo "<div class='message'>Success</div>";
                 } 
            
            }
        ?>
        <form action="" method="post">
            <input type="text" name="name" placeholder="Enter name" value="<?php echo $row->name; ?>"><br>
            <input type="text" name="email" placeholder="Enter email" value="<?php echo $row->email; ?>" ><br>
            <input type="text" name="phone" placeholder="Enter phone" value="<?php echo $row->phone; ?>"><br>
            <input type="text" name="role" placeholder="Enter role" value="<?php echo $row->role; ?>"><br>
            <input type="text" name="status" placeholder="Enter status" value="<?php echo $row->status; ?>"><br>
            <input type="text" name="action" placeholder="Enter action" value="<?php echo $row->action; ?>"><br>
            <input type="submit" name="submit" value="UPDATE">
        </form>
        <br>
        <a class="link" href="index.php">Back </a><br><br>
    </div>
</body>
</html>