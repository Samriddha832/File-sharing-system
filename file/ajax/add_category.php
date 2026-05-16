<?php
include '../includes/connection.php';

$name = trim($_POST['name']);

if(empty($name)){
    exit;
}

$check = mysqli_query($con,"SELECT * FROM categories WHERE name='$name'");
if(mysqli_num_rows($check)>0){
    echo "exists";
    exit;
}

mysqli_query($con,"INSERT INTO categories(name) VALUES('$name')");
$id = mysqli_insert_id($con);

/* Build New Category HTML */
$html = '
<div class="category-item" data-id="'.$id.'">
    <span class="cat-name">'.$name.'</span>
    <input type="text" class="editCategoryInput" style="display:none;">

    <button type="button" class="editCategoryBtn">
        <i class="fas fa-pen"></i>
    </button>

    <button type="button" class="saveCategoryBtn" style="display:none;">
        <i class="fas fa-check"></i>
    </button>

    <button type="button" class="deleteCategory">
        <i class="fas fa-trash"></i>
    </button>
</div>';

/* Return JSON */
echo json_encode([
    "id" => $id,
    "name" => $name,
    "html" => $html
]);