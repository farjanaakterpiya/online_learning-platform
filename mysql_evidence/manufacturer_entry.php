<?php $db = new mysqli("localhost", "root", "", "mysql_evidence");?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h3>Manufacturer Entry</h3>

    <?php
    if(isset($_POST['submit'])){
        extract($_POST);
        $db-> query ("CALL manufacture_insert_proce('$name', '$address','$contact')");
        if($db->affected_rows){
            echo "success";
        }
    }
    ?>
    <form action="" method="post">
        <input type="text" name="name"><br>
        <textarea name="address" id=""></textarea><br>
        <input type="text" name="contact"><br>
        <input type="submit" name="submit" value="submit">
    </form>
</body>
</html>