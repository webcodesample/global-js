function set_print_inv()
{
    $.ajax({
        type: 'POST',
        url: 'print_inv-ajax.php',
        dataType: 'json',
        data: {
            'customer': $('#from').val(),
            'issuer': $('#invoice_issuer').val(),
            'inv_type': $('#invoice_type').val(),
            'inv_month': $('#invoice_month').val(),
            'inv_fy': $('#invoice_fy').val(),
            'ndb_sr_no': $('#invoice_idnew').val()
        },
        success: function (result) {
            document.getElementById('invoice_id_print').value = result;
            //alert(result);
        }
    });
}