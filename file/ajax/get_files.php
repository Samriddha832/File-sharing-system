<?php
include '../includes/connection.php';

$id = intval($_POST['id']);

/* Get category name for folder path */
$resCat = mysqli_query($con, "SELECT name FROM categories WHERE id='$id' LIMIT 1");
if(mysqli_num_rows($resCat) == 0){
    echo "<div>No category found.</div>";
    exit;
}
$catRow = mysqli_fetch_assoc($resCat);
$categoryName = preg_replace("/[^a-zA-Z0-9_-]/", "_", strtolower($catRow['name']));

$res = mysqli_query($con, "SELECT * FROM files WHERE category_id='$id' ORDER BY uploaded_at DESC");

if(mysqli_num_rows($res) == 0){
    echo "<div>No files uploaded in this category.</div>";
    exit;
}

/* Start table */
echo "<table class='file-table'>";
echo "<tr>
        <th>ID</th>
        <th>File Name</th>
        <th>Uploaded At</th>
        <th>Actions</th>
      </tr>";

while($row = mysqli_fetch_assoc($res)){
    $ext = strtolower(pathinfo($row['original_name'], PATHINFO_EXTENSION));
    
    /* Determine file icon */
    $icon = "fa-file";
    if(in_array($ext, ['jpg','jpeg','png','gif','bmp'])) $icon = 'fa-file-image';
    else if(in_array($ext, ['pdf'])) $icon = 'fa-file-pdf';
    else if(in_array($ext, ['doc','docx'])) $icon = 'fa-file-word';
    else if(in_array($ext, ['xls','xlsx'])) $icon = 'fa-file-excel';
    else if(in_array($ext, ['ppt','pptx'])) $icon = 'fa-file-powerpoint';
    else if(in_array($ext, ['zip','rar','7z','tar','gz'])) $icon = 'fa-file-archive';
    else if(in_array($ext, ['mp4','avi','mov','mkv'])) $icon = 'fa-file-video';
    else if(in_array($ext, ['mp3','wav','ogg'])) $icon = 'fa-file-audio';

    /* Use custom category folder name */
    $fileUrl = "uploads/{$categoryName}/{$row['file_name']}";

    echo "<tr>
            <td>{$row['id']}</td>
            <td><i class='fas $icon'></i> {$row['original_name']}</td>
            <td>".date('d-m-Y H:i', strtotime($row['uploaded_at']))."</td>
            <td>
                <a href='$fileUrl' target='_blank' class='view-btn'><i class='fas fa-eye'></i> View</a>
                <a href='$fileUrl' download class='download-btn'><i class='fas fa-download'></i> Download</a>
                <button class='deleteFile' data-id='{$row['id']}'><i class='fas fa-trash'></i></button>
            </td>
          </tr>";
}

echo "</table>";
?>