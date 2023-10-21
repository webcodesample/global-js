function check_shortname()
{
	//function coded by amit
	$('#short_name').val($('#short_name').val().toUpperCase());
	//alert($('#short_name').val());

	if ($('#short_name').val().length < 4) {
		$('#sn_msg').html('Must have min 4 chars');
		$('#short_name').val('');
		$('#short_name').focus();
	}
	else {
		$.ajax({
			type: 'POST',
			url: 'shortname-ajax.php',
			dataType: 'json',
			data: { 'short_name': $('#short_name').val() },
			success: function (result) {
				$('#sn_msg').html(result);
			}
		});
	}
}