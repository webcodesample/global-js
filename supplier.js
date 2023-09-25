$(document).ready(function(){
    $("#export_to_excel").click(function(){
        $("#my_table").table2excel({        
        
            exclude: ".noExl",
            name: "Developer data",
            filename: "Supplier_List",
            fileext: ".xls",        
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true            
        });  
    });
   $( "#supplier_search" ).autocomplete({
            source: "supplier1-ajax.php"
        });
});

function add_div()
{
	$("#adddiv").toggle("slow");
}

function validation()
{
	if($("#fname").val() == "")
	{
		alert("Please enter first name.");
		$("#fname").focus();
		return false;
	}
	else if($("#opening_balance").val() == "")
	{
		alert("Please enter bank opening balance.");
		$("#opening_balance").focus();
		return false;
	}
	else if($("#opening_balance_date").val() == "")
	{
		alert("Please enter bank opening balance date.");
		$("#opening_balance_date").focus();
		return false;
	}
	else
	{
		$("#action_perform").val("add_supplier");
		$("#supplier_form").submit();
		return true;
	}	
}
function account_delete(del_id)
{
	if(confirm("Are you sure want to delete?!!!!!......"))
	{
		$("#action_perform").val("delete_supplier");
		$("#del_id").val(del_id);
		$("#supplier_form").submit();
		return true;
	}
}
function search_valid()
{
	if(document.getElementById("search_type").value=="" && document.getElementById("search_text").value=="")
	{
		alert("Please Select Search Type and Search Text");
		document.getElementById("search_text").focus();
	 	return false;
	}
	else if(document.getElementById("search_type").value=="")
	{
	 alert("Please Select Search Type");
	 document.getElementById("search_type").focus();
	 return false;
	}
	else if(document.getElementById("search_text").value=="")
	{
	 alert("Please enter search text to search");
	 document.getElementById("search_text").focus();
	 return false;
	} 	
}
function show_records(getno)
{
    document.getElementById("page").value=getno;
    document.search_form.submit(); 
}
 function same_address()
 {
 	if(document.getElementById("same_current").checked==true)
	{
		document.getElementById("permanent_address").value = document.getElementById("current_address").value;
	}
	else
	{	
		document.getElementById("permanent_address").value = "";
	}
 }
 
 
function print_data()
{
	var print_header1 = $("#print_header").val();           
	var divToPrint1 = document.getElementById("ledger_data");
	var divToPrint = divToPrint1;
	divToPrint.border = 3;
	divToPrint.cellSpacing = 0;
	divToPrint.cellPadding = 2;
	divToPrint.style.borderCollapse = 'collapse';
	newWin = window.open();
	newWin.document.write("<h3 align='center'>"+print_header1+" </h3>");

	$("#header1").hide();
	$('table tr').find('td:eq(7)').hide();
	newWin.document.write(divToPrint.outerHTML);
	newWin.print();

	$('table tr').find('td:eq(7)').show();
	$("#header1").show();
	newWin.close();
}
