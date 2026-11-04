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
        <h3>Student Entry Form</h3>
        <?php 
            if($_SERVER['REQUEST_METHOD']=='POST'){
               // Data received from entry form
                $name = $_POST['name'];
                 $name = $_POST['address'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                include_once("dbconfig.php"); // Database Connection

            //    echo "INSERT INTO allstudents 
            //     (id, name, email, phone) VALUES 
            //     (NULL, '$name', '$email', '$phone')";
            //     echo "<br>";

               $conn->query("INSERT INTO students 
                (id, name, email, phone) VALUES 
                (NULL, '$name', '$email', '$phone')");

                 if($conn->affected_rows){
                    echo "<div class='message'>Success</div>";
                 } 
            
            }
        ?>
        <form action="" method="post">
            <input type="text" name="name" placeholder="Enter name"><br>
            <input type="text" name="address" placeholder="Enter address"><br>
            <input type="text" name="email" placeholder="Enter email"><br>
            <input type="text" name="phone" placeholder="Enter phone"><br>
            <input type="submit" name="submit" value="SAVE">
        </form>
        <br>
        <a class="link" href="index.php">Back to Student List</a><br><br>
    </div>
</body>
</html>