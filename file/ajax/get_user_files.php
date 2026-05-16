<?php
include '../includes/connection.php';

$id = intval($_POST['id']);

/* Get category name */
$resCat = mysqli_query($con,"SELECT name FROM categories WHERE id='$id' LIMIT 1");
if(mysqli_num_rows($resCat) == 0){
    echo json_encode([]);
    exit;
}

$catRow = mysqli_fetch_assoc($resCat);
$categoryName = preg_replace("/[^a-zA-Z0-9_-]/","_", strtolower($catRow['name']));

$res = mysqli_query($con,"SELECT * FROM files WHERE category_id='$id' ORDER BY uploaded_at DESC");
$files = [];

while($row = mysqli_fetch_assoc($res)){

    /* correct file path */
    $file_url = "uploads/".$categoryName."/".$row['file_name'];

    $files[] = [
        'id' => $row['id'],
        'category_id' => $row['category_id'],
        'file_name' => $row['file_name'],
        'original_name' => $row['original_name'],
        'url' => $file_url,
        'uploaded_at' => date('d-m-Y H:i', strtotime($row['uploaded_at']))
    ];
}

echo json_encode($files);
?>