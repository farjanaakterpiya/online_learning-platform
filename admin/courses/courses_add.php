<?php 
include_once("dbconfig.php");

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $instructor = $_POST['instructor'];

    $sql = "INSERT INTO courses (name, description, price, instructor) VALUES ('$name', '$description', '$price', '$instructor')";
    
    if($conn->query( $sql )) {
        header("Location: courses.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Course</title>
</head>
<body>
    <h3>Add New Course</h3>
    <form action="" method="post">
        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" required></textarea><br><br>

        <label>Price:</label><br>
        <input type="number" step="0.01" name="price" required><br><br>

        <label>Instructor:</label><br>
        <input type="text" name="instructor" required><br><br>

        <button type="submit" name="submit">Save Course</button>
        <a href="courses.php">Back</a>
    </form>
</body>
</html>