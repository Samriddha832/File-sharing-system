<?php
session_start();
if(isset($_SESSION['success_msg'])){
    echo $_SESSION['success_msg'];
    unset($_SESSION['success_msg']);
}else{
    echo '';
}
?>