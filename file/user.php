<?php
session_start();
include 'includes/connection.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Files</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toast -->
    <link rel="stylesheet" href="Tost_Message/style.css?v=<?=time()?>">
    <script src="Tost_Message/script.js"></script>

    <style>
    body {
        font-family: Arial, sans-serif;
        background: #f2f2f2;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 1100px;
        margin: 40px auto;
        padding: 20px;
    }
    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
    }
    /* Category selector */
    .category-selector {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 30px;
        font-size: 16px;
    }
    .category-selector i {
        font-size: 20px;
        color: #007bff;
    }
    select {
        padding: 8px 12px;
        font-size: 15px;
        border-radius: 6px;
        border: 1px solid #ccc;
        outline: none;
        cursor: pointer;
    }
    /* File grid */
    .file-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
    .file-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 3px 15px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .file-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .file-icon {
        font-size: 50px;
        color: #007bff;
        margin-bottom: 15px;
    }
    .file-name {
        font-weight: bold;
        font-size: 15px;
        margin-bottom: 6px;
        word-break: break-word;
    }
    .file-date {
        font-size: 12px;
        color: #666;
        margin-bottom: 12px;
    }
    .file-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    .file-actions a {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: #fff;
        font-size: 13px;
        transition: 0.2s;
    }
    .view-btn {
        background: #28a745;
    }
    .view-btn:hover {
        background: #218838;
    }
    .download-btn {
        background: #007bff;
    }
    .download-btn:hover {
        background: #0056b3;
    }
    </style>
</head>
<body>

<div class="container">
    <h2>User Files</h2>

    <!-- Category Selector -->
    <div class="category-selector">
        <i class="fas fa-folder-open"></i>
        <select id="categorySelect">
            <option value="">Select Category</option>
            <?php
            $res = mysqli_query($con, "SELECT * FROM categories ORDER BY name ASC");
            while($row = mysqli_fetch_assoc($res)){
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>
    </div>

    <!-- File Grid -->
    <div id="fileGrid" class="file-grid">
        <div>Select a category to see files.</div>
    </div>
</div>

<script>
$(document).ready(function(){

    function getFileIcon(ext){
        ext = ext.toLowerCase();
        if(['jpg','jpeg','png','gif','bmp'].includes(ext)) return 'fa-file-image';
        if(['pdf'].includes(ext)) return 'fa-file-pdf';
        if(['doc','docx'].includes(ext)) return 'fa-file-word';
        if(['xls','xlsx'].includes(ext)) return 'fa-file-excel';
        if(['ppt','pptx'].includes(ext)) return 'fa-file-powerpoint';
        if(['zip','rar','7z','tar','gz'].includes(ext)) return 'fa-file-archive';
        if(['mp4','avi','mov','mkv'].includes(ext)) return 'fa-file-video';
        if(['mp3','wav','ogg'].includes(ext)) return 'fa-file-audio';
        return 'fa-file';
    }

    $('#categorySelect').change(function(){
        let id = $(this).val();
        if(!id){
            $('#fileGrid').html('<div>Select a category to see files.</div>');
            return;
        }

        $.post('ajax/get_user_files.php',{id:id},function(res){
            if(res.trim() === ''){
                $('#fileGrid').html('<div>No files in this category.</div>');
                return;
            }

            let files = JSON.parse(res);
            let html = '';

            if(files.length === 0){
                html = '<div>No files in this category.</div>';
            } else {
                files.forEach(file => {
                    let iconClass = getFileIcon(file.original_name.split('.').pop());
                    html += `
                    <div class="file-card">
                        <i class="fas ${iconClass} file-icon"></i>
                        <div class="file-name">${file.original_name}</div>
                        <div class="file-date">${file.uploaded_at}</div>
                        <div class="file-actions">
                            <a class="view-btn" href="${file.url}" target="_blank"><i class="fas fa-eye"></i> View</a>
                            <a class="download-btn" href="${file.url}" download><i class="fas fa-download"></i> Download</a>
                        </div>
                    </div>`;
                });
            }

            $('#fileGrid').html(html);
        });
    });

});
</script>

</body>
</html>


<style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

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
      

       

        

       
    </style>
</head>

<body>

<footer>
<h2>File Sharing System</h2>

<div class="footer-links">
<a href="#">Home</a>
<a href="https://www.instagram.com/sam.rid69/?hl=en" target="_blank" rel="noopener noreferrer">About</a>
<a href="https://bookhotelbysuman.gamer.gd/" target="_blank" rel="noopener noreferrer">Services</a>
<a href="https://bookhotelbysuman.gamer.gd/member/index.php"target="_blank" rel="noopener noreferrer">Contact</a>
</div>

<p>Providing quality service and user experience.</p>

<div class="bottom-text">
© 2026 All Rights Reserved | Powered by <strong>Samriddha</strong>
</div>
</footer>