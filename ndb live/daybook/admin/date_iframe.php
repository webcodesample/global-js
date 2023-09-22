
<link rel="stylesheet" type="text/css" href="mos-css/mos-style.css"> <!--pemanggilan file css-->
<link type="text/css" href="css/jquery.datepick.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery-min.js" ></script>
<script type="text/javascript" src="js/jquery.datepick.js"></script>
<script>
$(function() {
	 
	$('#payment_date').datepick({dateFormat: 'dd-mm-yyyy'});
	
});
</script>

<form action="" method="post" name="date_form" id="date_form" >
<input type="text" name="payment_date" id="payment_date" value=""  />
</form>

