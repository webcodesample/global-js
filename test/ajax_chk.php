<input type="text" name="c_gstin" id="c_gstin" value="" style="width:250px; height: 25px;">
<script src="js/jquery-ui.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>

$(document).ready(function(){    
   $("#c_gstin").autocomplete({
			source: "gstin-ajax.php"
		});
});
</script>