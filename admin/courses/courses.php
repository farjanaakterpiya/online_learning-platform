<?php include_once("./dbconfig.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course List</title>
</head>
<body>
    <h3>Course List</h3>
    <a href="course_add.php" style="display: inline-block; margin-bottom: 15px;">+ Add New Course</a>
    
    <?php 
       $rawData = $conn->query("SELECT * FROM courses"); 
    ?>

    <table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Price</th>
        <th>Instructor</th>
        <th>Action</th>
    </tr>    
    <?php
       while($row = $rawData->fetch_assoc()){ 
    ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo $row['instructor']; ?></td>
            <td>
                <a href="course_edit.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="course_delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure to delete?')">Delete</a>
            </td>
          </tr>
      <?php 
        }    
    ?>
    </table>
</body>
</html>