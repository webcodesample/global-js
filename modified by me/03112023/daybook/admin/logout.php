<?php 
session_start(); 
unset($_SESSION['username']);
echo '<script>window.location="login.php";</script>';
?>
