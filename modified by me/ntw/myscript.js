
//check number entered or not
function chk_num()
{
    if (document.getElementById('price').value>0)
        document.getElementById('convert_frm').submit();
    else
        alert("Please enter a valid number");
}

//check inv type
function chk_inv_type()
{
    if (document.getElementById('inv_type').value)
        document.getElementById('convert_frm').submit();
    else
        alert("Please Select A valid Invoice Type");
}
