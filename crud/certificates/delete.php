<?php

include_once("../../config/dbconfig.php"); // Database Connection

$id = $_GET['id'];

$conn->query("DELETE FROM certificates WHERE id = '$id'");

if($conn->affected_rows){
    header("Location: index.php");
}

?>