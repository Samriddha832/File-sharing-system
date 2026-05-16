<?php
include '../includes/connection.php';

if(!isset($_POST['id'])){
    echo json_encode(["status"=>"error"]);
    exit;
}

$id = intval($_POST['id']);

/* Get category name for folder deletion */
$resCat = mysqli_query($con, "SELECT name FROM categories WHERE id='$id' LIMIT 1");
if(mysqli_num_rows($resCat) == 0){
    echo json_encode(["status"=>"error"]);
    exit;
}
$catRow = mysqli_fetch_assoc($resCat);
$categoryName = preg_replace("/[^a-zA-Z0-9_-]/", "_", strtolower($catRow['name']));

/* Folder path */
$folder = "../uploads/{$categoryName}";

/* Recursive delete folder function */
function deleteFolder($dir) {
    if(!is_dir($dir)) return;
    $items = scandir($dir);
    foreach($items as $item){
        if($item == '.' || $item == '..') continue;
        $path = $dir . '/' . $item;
        if(is_dir($path)){
            deleteFolder($path);
        } else {
            unlink($path);
        }
    }
    rmdir($dir);
}

/* Delete files from DB */
mysqli_query($con, "DELETE FROM files WHERE category_id='$id'");

/* Delete category folder and its contents */
deleteFolder($folder);

/* Delete category from DB */
mysqli_query($con, "DELETE FROM categories WHERE id='$id'");

/* Return success */
echo json_encode(["status"=>"success", "id"=>$id]);
exit;
?>