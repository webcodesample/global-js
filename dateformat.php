<form method="post" action="">
<div id="msg" style="color:red; font-weight:bold;">&nbsp;</div>
<input type="text" name="date" id="date" value="" placeholder="DD-MM-YYYY" onkeydown="set_format(event,this.id)" maxlength="10">
<input type="date" onblur="alert(this.value)">
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">

function set_format(event, event_field) 
{
    //alert(event.key);
    //alert(document.getElementById(event_field).value);
	$.ajax({
        type: 'POST',
        url: 'dateformat-ajax.php',
        dataType: 'json',
        data: { 'key': event.key, 'date': document.getElementById(event_field).value },
        success: function (result) {
            if(result[1]) $("#msg").html(result[1]);
            else $("#msg").html('&nbsp;');
            if(result[0].length<11)
            document.getElementById(event_field).value = result[0];
            document.getElementById(event_field).focus();
        }
    });
}

</script>
