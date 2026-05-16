<?php
include '../includes/connection.php';

$id = intval($_POST['id']);

mysqli_query($con,"DELETE FROM files WHERE id='$id'");

echo "success";
?>