function setDateFormat(event, event_field) {
    //code by amit
    if (event.key !== 'Backspace' && event.key !== 'Tab') {
        if (isNaN(event.key) || event.key == ' ') {
            alert("Please press a numeric key");
            event.preventDefault();
        }
        else {
            if (document.getElementById(event_field).value.length == 2)
                document.getElementById(event_field).value = document.getElementById(event_field).value + '-';

            if (document.getElementById(event_field).value.length == 5)
                document.getElementById(event_field).value = document.getElementById(event_field).value + '-';
        }
    }
    else if (event.key == 'Tab') {
        if (document.getElementById(event_field).value.length == 1) {
            document.getElementById(event_field).value = 0 + document.getElementById(event_field).value + '-';
            event.preventDefault();
        }
        else if (document.getElementById(event_field).value.length == 4) {
            const dateArray = document.getElementById(event_field).value.split("-");
            if (dateArray[1].length == 1) {
                dateArray[1] = 0 + dateArray[1];
            }
            document.getElementById(event_field).value = dateArray.join("-");
            document.getElementById(event_field).value = document.getElementById(event_field).value + "-";
            event.preventDefault();
        }
        else if (document.getElementById(event_field).value.length > 7) {
            const dateArray = document.getElementById(event_field).value.split("-");
            if (dateArray[2].length == 3) {
                alert("Date format should be dd/mm/yy OR dd/mm/yyyy");
                event.preventDefault();
            }

            if (dateArray[2].length == 2) {
                let current_year = new Date().getFullYear().toString().substr(2);
                if (dateArray[2] >= 0 && dateArray[2] <= current_year) {
                    dateArray[2] = 20 + dateArray[2];
                    document.getElementById(event_field).value = dateArray.join("-");
                }
                else {
                    dateArray[2] = 19 + dateArray[2];
                    document.getElementById(event_field).value = dateArray.join("-");
                }
            }
        }
        else {
            alert("Date format should be dd/mm/yy OR dd/mm/yyyy");
            event.preventDefault();
        }

    }
}

//for checking shart name availability

function check_shortname() {
    //function coded by amit
    $('#short_name').val($('#short_name').val().toUpperCase());

    if ($('#short_name').val().length < 2) {
        $('#sn_msg').html('Must have min 2 chars');
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

//function for set printable invoice number

function setPrintInvoiceNo() {
    //function coded by amit
    CustomerShortName = "";
    InvoiceIssuer = "";
    InvoiceType = "";
    InvoiceMonth = document.getElementById('invoice_month').value;
    InvoiceFY = document.getElementById('invoice_fy').value;

    if (document.getElementById('invoice_type').value == "M") {
        InvoiceType = "/M";
    }

    if (document.getElementById('invoice_type').value == "S") {
        InvoiceMonth = "/" + document.getElementById('invoice_idnew').value;
    }

    if (document.getElementById('invoice_type').value == "RN") {
        document.getElementById('inv_label').innerHTML = "Reimbursement Note";
    }
    else {
        document.getElementById('inv_label').innerHTML = "Invoice No.";
    }

    if (document.getElementById('invoice_issuer').value) {
        if (document.getElementById('invoice_type').value == "M" || document.getElementById('invoice_type').value == "S")
            InvoiceIssuer = "/" + document.getElementById('invoice_issuer').value.substring(0, 2);
        else
            InvoiceIssuer = "/" + document.getElementById('invoice_issuer').value.substring(0, 3);
    }

    if (document.getElementById('from').value)
    {
        const customerNameArray = document.getElementById('from').value.split(" - ");
        CustomerShortName = customerNameArray[2];
    }

    document.getElementById('invoice_id_print').value = CustomerShortName + InvoiceIssuer + InvoiceType + InvoiceMonth + InvoiceFY;
}