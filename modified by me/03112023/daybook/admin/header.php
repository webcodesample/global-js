<?php
ob_start();
session_start();
include_once ("../connection.php");
if($_SESSION['username'] == "")
{
	echo '<script>window.location="login.php";</script>';
}
 ?>

<link rel="stylesheet" type="text/css" href="mos-css/mos-style.css"> <!--pemanggilan file css-->
<link type="text/css" href="css/jquery.datepick.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-min.js" ></script>
<script type="text/javascript" src="js/jquery.datepick.js"></script>


<div id="header">
	<div class="inHeader">
		<div class="mosAdmin">
		Welcome,<span style="color: #80FFFF;" > <?php echo $_SESSION['username']; ?></span><br>
		<a href="logout.php"><span style="color: #FFF;">Logout</span></a>
		</div>
	<div class="clear"></div>
	</div>
</div>




