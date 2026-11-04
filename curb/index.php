<?php
include_once("dbconfig.php");

$perPage = 20;
$countResult = $conn->query("SELECT COUNT(*) AS total FROM students");
$totalRows = (int) $countResult->fetch_assoc()["total"];
$totalPages = max(1, (int) ceil($totalRows / $perPage));
$requestedPage = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
$currentPage = is_int($requestedPage)
    ? max(1, min($requestedPage, $totalPages))
    : 1;
$offset = ($currentPage - 1) * $perPage;

$studentQuery = $conn->prepare("SELECT * FROM students ORDER BY id LIMIT ? OFFSET ?");
$studentQuery->bind_param("ii", $perPage, $offset);
$studentQuery->execute();
$rawData = $studentQuery->get_result();
$firstRow = $totalRows === 0 ? 0 : $offset + 1;
$lastRow = min($offset + $perPage, $totalRows);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <h3>Student List</h3>
            <a class="btn" href="student_new.php">New Entry</a>
        </div>

        <p>Showing <?php echo $firstRow; ?>–<?php echo $lastRow; ?> of <?php echo $totalRows; ?> students</p>

        <table border="1" style="border-collapse: collapse;">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>    
        <?php
           while($row = $rawData->fetch_assoc()){ ?>
              <tr>
                <td><?php echo  $row['id'] ?> </td>
                <td><?php echo  $row['name'] ?></td>
                <td><?php echo  $row['address'] ?></td>
                <td><?php echo  $row['email'] ?></td>
                <td><?php echo  $row['phone'] ?></td>
                <td class="action">
                    <a class="edit" aria-label="Edit student" title="Edit student" href="student_edit.php?id=<?php echo  $row['id'] ?>">&#9998;</a> |
                    <a onclick="return confirm('Are you sure to delete')" class="danger" aria-label="Delete student" title="Delete student" href="student_delete.php?id=<?php echo  $row['id'] ?>">&#128465;</a>
                </td>
              </tr>
          <?php 
            }    
        ?>
        </table>

        <?php if ($totalPages > 1) { ?>
            <nav aria-label="Student list pages">
                <?php if ($currentPage > 1) { ?>
                    <a href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous page">Previous</a>
                <?php } ?>

                <?php for ($page = 1; $page <= $totalPages; $page++) { ?>
                    <?php if ($page === $currentPage) { ?>
                        <strong aria-current="page"><?php echo $page; ?></strong>
                    <?php } else { ?>
                        <a href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                    <?php } ?>
                <?php } ?>

                <?php if ($currentPage < $totalPages) { ?>
                    <a href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next page">Next</a>
                <?php } ?>
            </nav>
        <?php } ?>
    </div>
</body>
</html>