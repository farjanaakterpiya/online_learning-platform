<?php 
include_once("dbconfig.php");

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM courses WHERE id = $id");
$row = $result->fetch_assoc();

if(isset($_POST['update'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $instructor = $_POST['instructor'];

    $sql = "UPDATE courses SET name='$name', description='$description', price='$price', instructor='$instructor' WHERE id=$id";
    
    if($conn->query($sql)) {
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
    <title>Edit Course</title>
</head>
<body>
    <h3>Edit Course</h3>
    <form action="" method="post">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo $row['title']; ?>" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" required><?php echo $row['description']; ?></textarea><br><br>

        <label>Price:</label><br>
        <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>" required><br><br>

        <label>Instructor:</label><br>
        <input type="text" name="instructor" value="<?php echo $row['instructor']; ?>" required><br><br>

        <button type="submit" name="update">Update Course</button>
        <a href="courses.php">Back</a>
    </form>
</body>
</html>