<?php 
include_once("./dbconfig.php");

$id = $_GET['id'];
$conn->query("DELETE FROM courses WHERE id = $id");
if($conn->affected_rows){
header("Location: courses.php");
}
?>