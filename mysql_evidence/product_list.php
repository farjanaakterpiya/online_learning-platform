<?php $db = new mysqli("localhost", "root", "", "mysql_evidence");?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h3>product list</h3>
    <?php
    $raw =$db->query ("SELECT *FROM above_5000_products");
    ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Manufacture ID</th>
        </tr>

        <?php
        while($row = $raw->fetch_assoc()){?>
            <tr>
                <td><?php echo $row['id']?></td>
                <td><?php echo $row['name']?></td>
                <td><?php echo $row['price']?></td>
                <td><?php echo $row['manufacture_id']?></td>
            </tr>
        <?php }?>
        
    </table>
</body>
</html>