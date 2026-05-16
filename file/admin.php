<?php 
session_start(); 
include 'includes/connection.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Toast -->
<link rel="stylesheet" href="Tost_Message/style.css?v=<?=time()?>">
<script src="Tost_Message/script.js"></script>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f4f6f9;
    padding:20px;
}

/* HEADER */
h2{
    text-align:center;
    margin-bottom:20px;
    color:#333;
}

/* CARD */
.section{
    background:#fff;
    padding:20px;
    margin-bottom:20px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

/* INPUT */
input, select{
    padding:10px;
    margin:8px 5px 8px 0;
    border:1px solid #ccc;
    border-radius:6px;
}

input:focus, select:focus{
    border-color:#007bff;
}

/* BUTTON */
button{
    padding:10px 15px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    background:#007bff;
    color:white;
    transition:0.3s;
    margin-right:5px;
}

button:hover{
    background:#0056b3;
}

/* CATEGORY ITEM */
.category-item{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:10px;
    border-bottom:1px solid #eee;
}

.category-item span{
    flex:1;
}

/* BUTTON COLORS */
.editCategoryBtn{ background:#ffc107; }
.saveCategoryBtn{ background:#28a745; }
.deleteCategory{ background:#dc3545; }

/* FILE LIST */
#fileList{
    margin-top:10px;
}

/* TABLE STYLE */
#fileList table{
    width:100%;
    border-collapse:collapse;
}

#fileList th, #fileList td{
    padding:10px;
    border-bottom:1px solid #ddd;
}

#fileList tr:hover{
    background:#f9f9f9;
}

/* LOADER */
#uploadOverlay{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:999;
}

.loader{
    background:#fff;
    padding:25px 40px;
    border-radius:10px;
    text-align:center;
}

/* FOOTER */
footer{
    background:#1e1e2f;
    color:#fff;
    padding:25px;
    text-align:center;
    margin-top:30px;
    border-radius:10px;
}

footer h2{
    color:white;
}

.footer-links a{
    color:#00bcd4;
    margin:0 10px;
    text-decoration:none;
}

.footer-links a:hover{
    color:white;
}

.bottom-text{
    margin-top:10px;
    font-size:13px;
    color:#aaa;
}

/* MOBILE */
@media (max-width:768px){

    body{ padding:10px; }

    h2{ font-size:20px; }

    .section{ padding:15px; }

    input, select, button{
        width:100%;
        margin:6px 0;
    }

    .category-item{
        flex-direction:column;
        align-items:flex-start;
        gap:8px;
        background:#fafafa;
        border-radius:8px;
    }

    .category-item span{
        width:100%;
        font-size:14px;
    }

    .category-item button{
        width:100%;
    }

    #fileList{
        font-size:14px;
        overflow-x:auto;
    }

    footer{
        padding:20px 10px;
    }

    .footer-links{
        display:flex;
        flex-direction:column;
        gap:8px;
    }
}
</style>
</head>

<body>

<div id="tostBox"></div>

<?php if(isset($_SESSION['toast'])): ?>
<script>
showTost("<?= $_SESSION['toast']['message']; ?>","<?= $_SESSION['toast']['type']; ?>");
</script>
<?php unset($_SESSION['toast']); endif; ?>

<h2>Admin Dashboard</h2>

<!-- CATEGORY -->
<div class="section">
<h3>Categories</h3>

<input type="text" id="categoryName" placeholder="New Category Name">
<button id="addCategoryBtn"><i class="fas fa-plus"></i> Add</button>

<div id="categoryList">
<?php 
$res = mysqli_query($con,"SELECT * FROM categories ORDER BY id DESC");
while($row=mysqli_fetch_assoc($res)){ 
?>
<div class="category-item" data-id="<?= $row['id'] ?>">
<span class="cat-name"><?= htmlspecialchars($row['name']) ?></span>
<input type="text" class="editCategoryInput" style="display:none;">
<button class="editCategoryBtn"><i class="fas fa-pen"></i></button>
<button class="saveCategoryBtn" style="display:none;"><i class="fas fa-check"></i></button>
<button class="deleteCategory"><i class="fas fa-trash"></i></button>
</div>
<?php } ?>
</div>
</div>

<!-- FILE UPLOAD -->
<div class="section">
<h3>Upload File</h3>

<select id="categorySelect">
<option value="">Select Category</option>
<?php 
$res = mysqli_query($con,"SELECT * FROM categories");
while($row=mysqli_fetch_assoc($res)){
echo "<option value='{$row['id']}'>".htmlspecialchars($row['name'])."</option>";
}
?>
</select>

<input type="file" id="fileInput">
<button id="uploadBtn"><i class="fas fa-upload"></i> Upload</button>

<div id="fileList">
<div>No files uploaded in this category.</div>
</div>
</div>

<!-- LOADER -->
<div id="uploadOverlay">
<div class="loader">
<i class="fas fa-spinner fa-spin"></i>
<p>Uploading...</p>
</div>
</div>

<!-- FOOTER -->
<footer>
<h2>File Sharing System</h2>

<div class="footer-links">
<a href="#">Home</a>
<a href="https://www.instagram.com/sam.rid69/" target="_blank">About</a>
<a href="https://bookhotelbysuman.gamer.gd/" target="_blank">Services</a>
<a href="https://bookhotelbysuman.gamer.gd/member/index.php" target="_blank">Contact</a>
</div>

<p>Providing quality service and user experience.</p>

<div class="bottom-text">
© 2026 All Rights Reserved | Powered by <strong>Samriddha</strong>
</div>
</footer>

<!-- SCRIPT -->
<script>
$(function(){

// ADD CATEGORY
$('#addCategoryBtn').click(function(){
let name=$('#categoryName').val().trim();

if(!name){
showTost('Enter category','invalid');
return;
}

$.post('ajax/add_category.php',{name:name},function(res){

if(res==='exists'){
showTost('Exists','invalid');
return;
}

let data=JSON.parse(res);

$('#categoryList').prepend(data.html);

// FIXED LINE ✅
$('#categorySelect').append(`<option value="${data.id}">${data.name}</option>`);

// auto select
$('#categorySelect').val(data.id);

$('#categoryName').val('');
showTost('Added','success');

});
});

// EDIT
$(document).on('click','.editCategoryBtn',function(){
let p=$(this).closest('.category-item');

p.find('.editCategoryInput')
.val(p.find('.cat-name').text())
.show();

p.find('.cat-name').hide();
$(this).hide();
p.find('.saveCategoryBtn').show();
});

// SAVE
$(document).on('click','.saveCategoryBtn',function(){

let p=$(this).closest('.category-item');
let id=p.data('id');
let name=p.find('.editCategoryInput').val().trim();

$.post('ajax/edit_category.php',{id:id,name:name},function(res){

if(res==='success'){
p.find('.cat-name').text(name).show();
p.find('.editCategoryInput').hide();
p.find('.editCategoryBtn').show();
p.find('.saveCategoryBtn').hide();

$('#categorySelect option[value="'+id+'"]').text(name);

showTost('Updated','success');
}
});
});

// DELETE CATEGORY
$(document).on('click','.deleteCategory',function(){

if(!confirm('Delete?')) return;

let p=$(this).closest('.category-item');
let id=p.data('id');

$.post('ajax/delete_category.php',{id:id},function(res){

let d=JSON.parse(res);

if(d.status==='success'){
p.remove();
$('#categorySelect option[value="'+id+'"]').remove();
$('#fileList').html('No files');
showTost('Deleted','success');
}
});
});

// UPLOAD FILE
$('#uploadBtn').click(function(){

let id=$('#categorySelect').val();
let file=$('#fileInput')[0].files[0];

if(!id){
showTost('Select category','invalid');
return;
}

if(!file){
showTost('Select file','invalid');
return;
}

let fd=new FormData();
fd.append('category_id',id);
fd.append('file',file);

$('#uploadOverlay').fadeIn();

$.ajax({
url:'ajax/add_file.php',
type:'POST',
data:fd,
contentType:false,
processData:false,

success:function(res){
$('#fileList').html(res);
$('#uploadOverlay').fadeOut();
$('#fileInput').val('');
showTost('Uploaded','success');
},

error:function(){
$('#uploadOverlay').fadeOut();
showTost('Error','error');
}
});
});

// LOAD FILES
$('#categorySelect').change(function(){

let id=$(this).val();

if(!id){
$('#fileList').html('No files');
return;
}

$.post('ajax/get_files.php',{id:id},function(res){
$('#fileList').html(res);
});
});

// DELETE FILE
$(document).on('click','.deleteFile',function(){

if(!confirm('Delete file?')) return;

let id=$(this).data('id');
let row=$(this).closest('tr');

$.post('ajax/delete_file.php',{id:id},function(res){

if(res==='success'){
row.remove();
showTost('Deleted','success');
}
});
});

});
</script>

</body>
</html>