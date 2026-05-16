<?php
include '../includes/connection.php';

if(!isset($_POST['id']) || !isset($_POST['name'])){
    exit;
}

$id = intval($_POST['id']);
$newName = trim($_POST['name']);

if(empty($newName)){
    exit;
}

/* Get old name */
$res = mysqli_query($con, "SELECT name FROM categories WHERE id='$id' LIMIT 1");
$row = mysqli_fetch_assoc($res);

$oldName = $row['name'];

/* Format folder names */
$oldFolderName = preg_replace("/[^a-zA-Z0-9_-]/", "_", strtolower($oldName));
$newFolderName = preg_replace("/[^a-zA-Z0-9_-]/", "_", strtolower($newName));

$oldPath = "../uploads/$oldFolderName";
$newPath = "../uploads/$newFolderName";

/* Check duplicate name */
$check = mysqli_query($con,
    "SELECT * FROM categories 
     WHERE name='$newName' AND id != '$id'"
);

if(mysqli_num_rows($check) > 0){
    echo "exists";
    exit;
}

/* Rename folder if exists */
if(is_dir($oldPath)){
    rename($oldPath, $newPath);
}

/* Update DB */
mysqli_query($con,
    "UPDATE categories SET name='$newName' WHERE id='$id'"
);

echo "success";
?>