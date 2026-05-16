<?php
include '../includes/connection.php';

$res = mysqli_query($con,"SELECT * FROM categories");
while($row = mysqli_fetch_assoc($res)){
?>
<div class="category-item" data-id="<?php echo $row['id']; ?>">

    <span class="cat-name"><?php echo $row['name']; ?></span>

    <input type="text" class="editCategoryInput" style="display:none;">

    <button type="button" class="editCategoryBtn">
        <i class="fas fa-pen"></i> Edit
    </button>

    <button type="button" class="saveCategoryBtn" style="display:none;">
        <i class="fas fa-check"></i> Save
    </button>

    <button type="button" class="deleteCategory">
        <i class="fas fa-trash"></i> Delete
    </button>

</div>
<?php } ?>