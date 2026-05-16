<?php
session_start();
include '../includes/connection.php';

/* Nepal Time */
date_default_timezone_set('Asia/Kathmandu');

if(!isset($_POST['category_id'])){
    echo "<div>No category selected.</div>";
    exit;
}

$category_id = intval($_POST['category_id']);

/* Get category name */
$resCat = mysqli_query($con, "SELECT name FROM categories WHERE id='$category_id' LIMIT 1");
if(mysqli_num_rows($resCat) == 0){
    echo "<div>Category not found.</div>";
    exit;
}

$catRow = mysqli_fetch_assoc($resCat);
$categoryName = preg_replace("/[^a-zA-Z0-9_-]/", "_", strtolower($catRow['name']));
$folder = "../uploads/{$categoryName}/";

/* Upload file */
if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){

    $original_name = $_FILES['file']['name'];
    $ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

    /* Allowed file types */
    $allowed = [
        'jpg','jpeg','png','gif','bmp',
        'pdf','doc','docx','xls','xlsx','ppt','pptx',
        'zip','rar','7z',
        'mp4','avi','mov','mkv','mp3','wav','md','html'
    ];

    if(!in_array($ext, $allowed)){
        echo "<div>File type not allowed.</div>";
        exit;
    }

    /* Large file support (example 500MB) */
    if($_FILES['file']['size'] > 500 * 1024 * 1024){
        echo "<div>File too large. Max 500MB allowed.</div>";
        exit;
    }

    $filename = uniqid() . '.' . $ext;

    if(!is_dir($folder)){
        mkdir($folder, 0777, true);
    }

    if(move_uploaded_file($_FILES['file']['tmp_name'], $folder.$filename)){

        $currentTime = date('Y-m-d H:i:s');

        mysqli_query($con, 
            "INSERT INTO files (category_id, file_name, original_name, uploaded_at) 
             VALUES ('$category_id','$filename','$original_name','$currentTime')");
    }
}

/* Fetch files */
$res = mysqli_query($con, 
    "SELECT * FROM files WHERE category_id='$category_id' ORDER BY uploaded_at DESC"
);

if(mysqli_num_rows($res) == 0){
    echo "<div>No files found.</div>";
    exit;
}

/* Show table */
echo "<h3>Files</h3>";
echo "<table class='file-table'>";
echo "<tr>
        <th>ID</th>
        <th>File Name</th>
        <th>Uploaded At</th>
        <th>Actions</th>
      </tr>";

while($row = mysqli_fetch_assoc($res)){

    $fileUrl = "uploads/{$categoryName}/{$row['file_name']}";

    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['original_name']}</td>
            <td>".date('d-m-Y h:i A', strtotime($row['uploaded_at']))."</td>
            <td>
                <a href='$fileUrl' target='_blank' class='view-btn'>
                    <i class='fas fa-eye'></i>
                </a>
                <a href='$fileUrl' download class='download-btn'>
                    <i class='fas fa-download'></i>
                </a>
                <button class='deleteFile' data-id='{$row['id']}'>
                    <i class='fas fa-trash'></i>
                </button>
            </td>
          </tr>";
}

echo "</table>";
?>