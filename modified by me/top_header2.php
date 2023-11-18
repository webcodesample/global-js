<?php
ob_start();
session_start();
include_once("set_con.php");

if($_SESSION['username'] == "")
{
	echo '<script>window.location="login.php";</script>';
}
 ?>
    
<header class="u-clearfix u-custom-color-1 u-header u-header" id="sec-558c" style=" padding-botttom:0px;margin-bottom:0px; height:60px;"><div class="u-clearfix u-sheet u-valign-middle u-sheet-1" style="height:70px;">
<a href="http://www.maruti-technology.com" class="u-image u-logo u-image-1">
<img src="images/default-logo.png" class="u-logo-image u-logo-image-1">
</a>
<nav class="u-menu u-menu-dropdown u-offcanvas u-menu-1">
<div class="u-nav-container"  style="padding: 0px 0px;font-size:12px;">
Welcome,<span style="color: #80FFFF;font-size:12px;" ><?php echo $_SESSION['username']; ?></span>
&nbsp;&nbsp;&nbsp;&nbsp;</br>
<a href="logout.php"><span style="color: #FFF;font-size:12px;">Logout</span></a>
</div>
</nav>
</div>
</header>


 
 