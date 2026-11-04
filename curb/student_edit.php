<?php  include_once("dbconfig.php"); // Database Connection ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Entry</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="card">
        <h3>Student Update Form</h3>
        <?php 
            // Display Student Record
            $id = $_GET['id']; 
            
           $data = $conn->query("SELECT * FROM students WHERE id = '$id'");
           $row = $data->fetch_object();
            
           //echo $row->name;
            
            
            // Update Student Record
            if($_SERVER['REQUEST_METHOD']=='POST'){
               // Data received from entry form
                $name = $_POST['name'];
                $address = $_POST['address'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
               
                //Update Query

                $conn->query("UPDATE students SET name ='$name',address= '$address', email='$email', phone='$phone' WHERE id='$id'");

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
            <input type="text" name="address" placeholder="Enter address" value="<?php echo $row->address; ?>"><br>
            <input type="text" name="email" placeholder="Enter email" value="<?php echo $row->email; ?>" ><br>
            <input type="text" name="phone" placeholder="Enter phone" value="<?php echo $row->phone; ?>"><br>
            <input type="submit" name="submit" value="UPDATE">
        </form>
        <br>
        <a class="link" href="index.php">Back to Student List</a><br><br>
    </div>
</body>
</html>