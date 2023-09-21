function invoice_attach_file_function(id) {
    //alert(id);   
    document.getElementById("attach_div").style.display = "block";
    document.getElementById("attach_file_id").value = id;
    document.getElementById("invoice_flag").value = "1";

}

function gst_due_function(due_id, due_trans, due_invoice, due_amount_pay) {
    //attach_file_id , due_trans_id , due_invoice_id , due_amount_pay
    //due_invoice_id,due_payment_id,due_trans_id
    /// attach_div  ,attach_form ,  attach_form ,  attach_validation , attach_file_id   , invoice_flag    

    document.getElementById("gst_payment_id").value = due_id;

    document.getElementById("gst_trans_id").value = due_trans;
    document.getElementById("gst_invoice_id").value = due_invoice;
    document.getElementById("gst_amount_pay").value = due_amount_pay;
    document.getElementById("gst_due_amount_new_total").value = due_amount_pay;
    document.getElementById("gst_due_amount").value = 0;
    document.getElementById("gst_due_amount_new_due").value = due_amount_pay;
    // alert(due_amount_pay);
    document.getElementById("gst_due_div").style.display = "block";

}
function clear_gst_desc() {
    //clear_gst_due , clear_gstdue_desc , clear_pay_payment_date_gst   TR= clear_date_display_gst , clear_date_display_gst  ,clear_desc_display_gst
    if (document.getElementById('clear_gst_due').checked == true) {
        document.getElementById("clear_gstdue_desc").style.display = "block";

        document.getElementById("clear_date_display_gst").style.display = "block";
        document.getElementById("clear_desc_display_gst").style.display = "block";
    }
    else if (document.getElementById('clear_gst_due').checked == false) {
        document.getElementById("clear_gstdue_desc").style.display = "none";
        document.getElementById("clear_date_display_gst").style.display = "none";
        document.getElementById("clear_desc_display_gst").style.display = "none";
    }
    //clear_gstdue_desc
}
function gst_due_calculation() {
    tot_val = document.getElementById("gst_due_amount_new_total").value;
    tot_get_val = document.getElementById("gst_due_amount").value;
    final_due = Number(tot_val) - Number(tot_get_val);
    document.getElementById("gst_due_amount_new_due").value = final_due;
    if (final_due < 1) {
        // document.getElementById('clear_gst_due').checked = true;
        //document.getElementById("clear_gstdue_desc").style.display="block";
        //document.getElementById("clear_date_display_gst").style.display="block";
        //document.getElementById("clear_desc_display_gst").style.display="block";
    }
    else {
        // document.getElementById('clear_gst_due').checked = false;
        // document.getElementById("clear_gstdue_desc").style.display="none";
        // document.getElementById("clear_date_display_gst").style.display="none";
        // document.getElementById("clear_desc_display_gst").style.display="none";
    }
    // final_amount_show();

}

function gst_validation() {
    if (document.getElementById("gst_due_flag_value").value == "0" && document.getElementById('clear_gst_due').checked == false) {
        //alert("first condition");
        return true;
    } else if (document.getElementById("gst_due_flag_value").value == "0" && document.getElementById('clear_gst_due').checked == true) {
        //alert("second condition");
        if (document.getElementById('clear_gst_due').checked == true) {
            if (document.getElementById("clear_gstdue_desc").value == "") {
                alert("Please enter description for clear due");
                document.getElementById("clear_gstdue_desc").focus();
                return false;
            } else if (document.getElementById("clear_pay_payment_date_gst").value == "") {
                alert("Please enter Payment Date for clear due");
                document.getElementById("clear_pay_payment_date_gst").focus();
                return false;
            }
            else {
                document.getElementById("clearall_due_flag").value = "2";
                return true;
            }
        } else {
            document.getElementById("clearall_due_flag").value = "2";
            return true;
        }
    }
    else if (document.getElementById("gst_due_flag_value").value == "1") { //gst_pay_form, gst_due_date , gst_due_amount ,gst_due_des
        document.getElementById("clearall_due_flag").value = "0";
        if (document.getElementById('clear_gst_due').checked == true && document.getElementById("clear_gstdue_desc").value == "") {
            alert("Please enter description for clear due");
            document.getElementById("clear_gstdue_desc").focus();
            return false;

        } else if (document.getElementById('clear_gst_due').checked == true && document.getElementById("clear_pay_payment_date_gst").value == "") {
            alert("Please enter Payment Date for clear due");
            document.getElementById("clear_pay_payment_date_gst").focus();
            return false;
        } else if (document.getElementById("gst_pay_form").value == "") {
            alert("Please select to the bank name");
            document.getElementById("gst_pay_form").focus();
            return false;
        } else if (document.getElementById("gst_due_date").value == "") {
            alert("Please select payment date");
            document.getElementById("gst_due_date").focus();
            return false;
        } else if (document.getElementById("gst_due_amount").value == "") {
            alert("Please enter received GST amount");
            document.getElementById("gst_due_amount").focus();
            return false;
        } else if (document.getElementById("gst_due_des").value == "") {
            alert("Please enter description");
            document.getElementById("gst_due_des").focus();
            return false;
        } else {
            return true;
        }
    }


}

function close_gst_div() {
    document.getElementById("gst_due_div").style.display = "none";
}


function close_gst_div2(due_flag_val) {
    // alert(due_flag_val);
    if (due_flag_val == "0") {
        document.getElementById("gst-due_div2").style.display = "none";
        document.getElementById("gst_due_flag_value").value = "0";
    } else if (due_flag_val == "1") {
        document.getElementById("gst-due_div2").style.display = "block";
        document.getElementById("gst_due_flag_value").value = "1";
    }

}

function clear_invoice_desc() {
    //clear_desc_display  ,clear_date_display
    if (document.getElementById('clear_invoice_due').checked == true) {
        document.getElementById("clear_invoicedue_desc").style.display = "block";
        document.getElementById("clear_pay_payment_date").style.display = "block";

        document.getElementById("clear_date_display").style.display = "block";
        document.getElementById("clear_desc_display").style.display = "block";

    }
    else if (document.getElementById('clear_invoice_due').checked == false) {
        document.getElementById("clear_invoicedue_desc").style.display = "none";
        document.getElementById("clear_pay_payment_date").style.display = "none";
        document.getElementById("clear_date_display").style.display = "none";
        document.getElementById("clear_desc_display").style.display = "none";

    }
    //clear_gstdue_desc
}

function invoice_due_function(due_id, due_trans, due_invoice, due_amount_pay) {

    document.getElementById("invoice_payment_id").value = due_id;

    document.getElementById("invoice_trans_id").value = due_trans;
    document.getElementById("invoice_invoice_id").value = due_invoice;
    document.getElementById("invoice_amount_pay").value = due_amount_pay;
    document.getElementById("invoice_due_amount_new_total").value = due_amount_pay;
    document.getElementById("pay_amount").value = 0;
    document.getElementById("invoice_due_amount_new_due").value = due_amount_pay;

    // alert(due_amount_pay);
    document.getElementById("invoice_due_div").style.display = "block";

}


function invoice_due_calculation() {
    tot_val = document.getElementById("invoice_due_amount_new_total").value;
    tot_get_val = document.getElementById("pay_amount").value;
    final_due = Number(tot_val) - Number(tot_get_val);
    document.getElementById("invoice_due_amount_new_due").value = final_due;
    if (final_due < 1) {
        //  document.getElementById('clear_invoice_due').checked = true;
        //  document.getElementById("clear_invoicedue_desc").style.display="block";
        //  document.getElementById("clear_pay_payment_date").style.display="block";
        //  document.getElementById("clear_date_display").style.display="block";
        //  document.getElementById("clear_desc_display").style.display="block";


    }
    else {
        // document.getElementById('clear_invoice_due').checked = false;
        // document.getElementById("clear_invoicedue_desc").style.display="none";
        // document.getElementById("clear_pay_payment_date").style.display="none";
        // document.getElementById("clear_date_display").style.display="none";
        // document.getElementById("clear_desc_display").style.display="none";

    }
    // final_amount_show();

}

function invoice_validation() {
    if (document.getElementById("invoice_due_flag_value").value == "0" && document.getElementById('clear_invoice_due').checked == false) {
        //alert("first condition");
        return true;
    } else if (document.getElementById("invoice_due_flag_value").value == "0" && document.getElementById('clear_invoice_due').checked == true) {
        //alert("second condition");

        if (document.getElementById('clear_invoice_due').checked == true) {
            if (document.getElementById("clear_invoicedue_desc").value == "") {
                alert("Please enter description for clear due");
                document.getElementById("clear_invoicedue_desc").focus();
                return false;
            } else if (document.getElementById("clear_pay_payment_date").value == "") {
                alert("Please enter Payment Date for clear due");
                document.getElementById("clear_pay_payment_date").focus();
                return false;
            } else {
                document.getElementById("clearall_due_flag_invoice").value = "2";
                return true;
            }
        }
        else {
            document.getElementById("clearall_due_flag_invoice").value = "2";
            return true;
        }
    }
    else if (document.getElementById("invoice_due_flag_value").value == "1") {
        //pay_payment_date ,pay_amount,pay_form
        document.getElementById("clearall_due_flag_invoice").value = "0";
        if (document.getElementById('clear_invoice_due').checked == true && document.getElementById("clear_invoicedue_desc").value == "") {
            alert("Please enter description for clear due");
            document.getElementById("clear_invoicedue_desc").focus();
            return false;

        } else if (document.getElementById('clear_invoice_due').checked == true && document.getElementById("clear_pay_payment_date").value == "") {
            alert("Please enter Payment Date for clear due");
            document.getElementById("clear_pay_payment_date").focus();
            return false;
        } else if (document.getElementById("pay_payment_date").value == "") {
            alert("Please select payment date");
            document.getElementById("pay_payment_date").focus();
            return false;
        } else if (document.getElementById("pay_amount").value == "") {
            alert("Please enter received Invoice amount");
            document.getElementById("pay_amount").focus();
            return false;
        } else if (document.getElementById("pay_form").value == "") {
            alert("Please select to the bank name");
            document.getElementById("pay_form").focus();
            return false;
        } else if (document.getElementById("invoice_due_des").value == "") {
            alert("Please enter description");
            document.getElementById("invoice_due_des").focus();
            return false;
        } else {
            return true;
        }
    }


}

function close_invoice_div() {
    document.getElementById("invoice_due_div").style.display = "none";
}


function close_invoice_div2(due_flag_val) {
    // alert(due_flag_val);
    if (due_flag_val == "0") {
        document.getElementById("invoice-due_div2").style.display = "none";
        document.getElementById("invoice_due_flag_value").value = "0";
    } else if (due_flag_val == "1") {
        document.getElementById("invoice-due_div2").style.display = "block";
        document.getElementById("invoice_due_flag_value").value = "1";
    }

}

function checkno_create() {
    document.getElementById('pay_check').style.display = 'block';

}
function checkno_create1() {
    document.getElementById('pay_check').style.display = 'none';
    document.getElementById('pay_checkno').value = "";
}

function combind_payment_function() {
    //alert('test');
    //alert(document.getElementById('count_icgst').value);
    //alert(document.getElementById('count_icinvoice').value);
    var count_icgst = document.getElementById('count_icgst').value;
    var count_icinvoice = document.getElementById('count_icinvoice').value;
    var num_count = 0;
    var tot_invoice_combind = 0;
    var tot_gst_combind = 0;
    var description = "";
    var ij = 1;
    $("#myTable_2 tbody tr").remove();
    for (var i = 1; i <= count_icinvoice; i++) {
        if ($("#combine_invoice_check_flag" + i + "").val() == "1") {
            var invoiceid = $("#combine_invoice_invoicen_id" + i + "").val();
            var amount = $("#combine_invoice_amount" + i + "").val();
            var payment_planid = $("#combine_invoice_paymentplan_id" + i + "").val();
            var type = $("#combine_invoice_type" + i + "").val();
            // alert(invoiceid+","+amount+","+payment_planid+","+type);
            tot_invoice_combind = Number(tot_invoice_combind) + Number($("#combine_invoice_amount" + i + "").val());
            num_count = 1;
            if (description == "") {
                description = "(Invoice Paid for invoice :" + invoiceid;
            } else {
                description = description + "," + "Invoice Paid for invoice :" + invoiceid;
            }

            markup = "<tr><td style='display:block;height:20px;padding:0px;'><input type='text' width='100px' readonly='readonly' id='' style='height:20px;border:0px;'  value='" + invoiceid + "(" + type + ")' name=''/><input type='text' id='combind_invoice_amount_pay" + ij + "' style='height:20px;'  value='" + amount + "' name='combind_invoice_amount_pay" + ij + "' readonly/><input type='hidden' id='combind_invoice_invoiceid" + ij + "' value='" + invoiceid + "' name='combind_invoice_invoiceid" + ij + "'/><input type='hidden' id='combind_invoice_amount" + ij + "' value='" + amount + "' name='combind_invoice_amount" + ij + "'/><input type='hidden' id='combind_invoice_payment_planid" + ij + "' value='" + payment_planid + "' name='combind_invoice_payment_planid" + ij + "'/><input type='hidden' id='combind_invoice_type" + ij + "' value='" + type + "' name='combind_invoice_type" + ij + "'/></td></tr>";
            $(' #myTable_2 tbody').append(markup);
            ij++;
        }
    }
    var ij_gst = 1;
    for (var i = 1; i <= count_icgst; i++) {
        if ($("#combine_gst_check_flag" + i + "").val() == "1") {
            var gst_invoiceid = $("#combine_gst_invoicen_id" + i + "").val();
            var gst_amount = $("#combine_gst_amount" + i + "").val();
            var gst_payment_planid = $("#combine_gst_paymentplan_id" + i + "").val();
            var gst_type = $("#combine_gst_type" + i + "").val();
            // alert(gst_invoiceid+","+gst_amount+","+gst_payment_planid+","+gst_type);
            tot_gst_combind = Number(tot_gst_combind) + Number($("#combine_gst_amount" + i + "").val());
            num_count = 1;
            if (description == "") {
                description = "(GST Paid for invoice :" + gst_invoiceid;
            } else {
                description = description + "," + "GST Paid for invoice :" + gst_invoiceid;
            }
            markup = "<tr><td style='display:block;height:20px;padding:0px;'><input type='text'  width='100px' readonly='readonly' id='' value='" + gst_invoiceid + "(" + gst_type + ")' style='height:20px;border:0px;'  name=''/><input type='text' id='combind_gst_amount_pay" + ij_gst + "' style='height:20px;' value='" + gst_amount + "' name='combind_gst_amount_pay" + ij_gst + "' readonly/><input type='hidden' id='combind_gst_invoiceid" + ij_gst + "' value='" + gst_invoiceid + "' name='combind_gst_invoiceid" + ij_gst + "'/><input type='hidden' id='combind_gst_amount" + ij_gst + "' value='" + gst_amount + "' name='combind_gst_amount" + ij_gst + "'/><input type='hidden' id='combind_gst_payment_planid" + ij_gst + "' value='" + gst_payment_planid + "' name='combind_gst_payment_planid" + ij_gst + "'/><input type='hidden' id='combind_gst_type" + ij_gst + "' value='" + gst_type + "' name='combind_gst_type" + ij_gst + "'/></td></tr>";
            $(' #myTable_2 tbody').append(markup);
            ij_gst++;
        }
    }
    //combind_gst_invoiceid ,combind_gst_amount , combind_gst_payment_planid ,combind_gst_type
    markup = "<tr><td style='display:none;'><input type='hidden' id='combind_invoice_count' value='" + ij + "' name='combind_invoice_count'/><input type='hidden' id='combind_gst_count' value='" + ij_gst + "' name='combind_gst_count'/></td></tr>";
    $('#myTable_2 tbody').append(markup);
    if (description != "") {
        description = description + ")";
    }

    var subtotal_combind = Number(tot_invoice_combind) + Number(tot_gst_combind);
    document.getElementById('combind_payment_amount_new_total').value = subtotal_combind;

    document.getElementById('combind_payment_due_des').value = description;
    //alert(num_count);
    if (num_count) {
        document.getElementById("combind_payment_div").style.display = "block";
    }
    else {
        alert("Please select any one payment type checkbox");
    }

}

function close_combind_payment_div() {
    document.getElementById("combind_payment_div").style.display = "none";
}
function combine_gst_checke_allow(nm) {
    if (document.getElementById("combine_gst_check" + nm).checked == true) {
        document.getElementById("combine_gst_check_flag" + nm).value = 1;
    }
    else {
        document.getElementById("combine_gst_check_flag" + nm).value = 0;
    }
}
function combine_invoice_checke_allow(nm) {
    if (document.getElementById("combine_invoice_check" + nm).checked == true) {
        document.getElementById("combine_invoice_check_flag" + nm).value = 1;
    }
    else {
        document.getElementById("combine_invoice_check_flag" + nm).value = 0;
    }
}


function search_valid() {
    if (document.getElementById("from_date").value == "") {
        alert("Please enter from date");
        document.getElementById("from_date").focus();
        return false;
    }
    else if (document.getElementById("to_date").value == "") {
        alert("Please enter to ");
        document.getElementById("to_date").focus();
        return false;
    }
}

function account_transaction_combind(trans_id, payment_id) {
    if (confirm("Are you sure want to delete?!!!!!......")) {
        $("#trans_id_combind").val(trans_id);
        $("#payment_id").val(payment_id);
        $("#trans_form_combind").submit();
        return true;
    }
}

function account_transaction_invoice_multisale(invoice_id) {
    //alert("hello");
    if (confirm("Are you sure want to delete?!!!!!......")) {

        $("#trans_t_name").val("instmulti_sale_goods");

        $("#invoice_id").val(invoice_id);
        $("#invoice_form").submit();
        return true;
    }
}


function account_transaction_invoice(trans_id_1) {
    if (confirm("Are you sure want to delete?!!!!!......")) {
        $("#trans_id_invoice").val(trans_id_1);
        $("#trans_form_invoice").submit();
        return true;
    }
}

function account_transaction_invoice_pay(invoice_id) {
    if (confirm("Are you sure want to delete?!!!!!......")) {
        $("#trans_t_name").val("instmulti_receive_payment");
        $("#invoice_id").val(invoice_id);
        $("#invoice_form").submit();
        return true;
    }
}

function account_transaction_invoice_gst(invoice_id, payment_id, type) {
    if (confirm("Are you sure want to delete?!!!!!......")) {
        $("#trans_t_name").val("instmulti_receive_gst_tds");
        $("#invoice_id").val(invoice_id);
        $("#del_payment_id").val(payment_id);
        $("#del_type").val(type);
        $("#invoice_form").submit();
        return true;
    }
}


function account_transaction_1(trans_id_1) {
    if (confirm("Are you sure want to delete?!!!!!......")) {
        $("#trans_id_1").val(trans_id_1);
        $("#trans_form_1").submit();
        return true;
    }
}
function account_transaction_gst(trans_id_1) {
    if (confirm("Are you sure want to delete?!!!!!......")) {
        $("#trans_id_gst").val(trans_id_1);
        $("#trans_form_gst").submit();
        return true;
    }
}

function edit_gst_function(info_tb_id, trsns_pname, invoice_no, payment_id) {
    document.getElementById("info_tb_id_gst").value = info_tb_id;
    document.getElementById("trsns_pname_gst").value = trsns_pname;
    document.getElementById("invoice_no_gst").value = invoice_no;
    document.getElementById("payment_id_gst").value = payment_id;
    $("#edit_gst_form").submit();
}

function edit_invoice_function(info_tb_id, trsns_pname, invoice_no, payment_id) {
    document.getElementById("info_tb_id_invoice").value = info_tb_id;
    document.getElementById("trsns_pname_invoice").value = trsns_pname;
    document.getElementById("invoice_no_invoice").value = invoice_no;
    document.getElementById("payment_id_invoice").value = payment_id;
    $("#edit_invoice_form").submit();
}

function profile_display() {
    //profile_display, profile_div_1 ,profile_check_val
    prof_val = document.getElementById("profile_check_val").value;
    if (prof_val == 0) {

        document.getElementById("profile_div_1").style.display = "block";
        document.getElementById("profile_check_val").value = 1;
    } else {
        document.getElementById("profile_div_1").style.display = "none";
        document.getElementById("profile_check_val").value = 0;
    }
}