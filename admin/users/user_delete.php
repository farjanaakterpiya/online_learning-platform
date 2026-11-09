<?php
include_once("./db_config.php");
$id = $_GET['id'];
$conn->query("DELETE FROM users WHERE id = '$id'");
if ($conn->affected_rows){
    header("Location: index.php");
}
?>