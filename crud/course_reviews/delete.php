<?php

include_once("../../config/dbconfig.php"); // Database Connection

$id = $_GET['id'];

$conn->query("DELETE FROM course_reviews WHERE id = '$id'");

if($conn->affected_rows){
    header("Location: index.php");
}

?>