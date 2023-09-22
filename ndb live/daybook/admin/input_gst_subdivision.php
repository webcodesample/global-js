<?php session_start();
include_once("../connection.php");
if($_REQUEST['msg'] != "")
{
    $msg = $_REQUEST['msg'];
}
else
{
    $msg = "";
}
if($_REQUEST['error_msg'] != "")
{
    $error_msg = $_REQUEST['error_msg'];
}
else
{
    $error_msg = "";
}


/*     Create  Account   */


if(trim($_REQUEST['action_perform']) == "add_user")
{
    /*echo '<pre>';
    print_r($_REQUEST);
    exit;*/
    $gst_subdivision=mysql_real_escape_string(trim($_REQUEST['gst_subdivision']));
    $gst_type=mysql_real_escape_string(trim($_REQUEST['gst_type']));
    
    $quuerrr="select id from gst_subdivision where name = '".$gst_subdivision."'";
    
    $sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
    $no=mysql_num_rows($sql);
    if($no > 0)
    {
        
        
        $error_msg = "gst_subdivision name already exist try another";
        
    }
    else
    {
        $query="insert into gst_subdivision set name = '".$gst_subdivision."' ,type='".$gst_type."'";
        $result= mysql_query($query) or die('error in query '.mysql_error().$query);
        $msg = "GST subdivision added successfully.";
    }
    
}

/*     Deletion  Account   */

if($_REQUEST['action_perform'] == "delete_user")
{
    $del_id = $_REQUEST['del_id'];
    $del_query = "delete from gst_subdivision where id = '".$del_id."'";
    $del_result = mysql_query($del_query) or die("error in delete query ".mysql_error());
    $msg = "GST ubdivision Deleted Successfully.";
    
}
if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
    $query = "select * from gst_subdivision where name LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' and type='output_gst' ORDER BY name ASC";
    $result = mysql_query($query) or die('error in query select gst_subdivision query '.mysql_error().$query);
    $total_row = mysql_num_rows($result);
}
else
{
    $query = "select * from gst_subdivision where type='output_gst' ORDER BY name ASC";
    $result = mysql_query($query) or die('error in query select gst_subdivision query '.mysql_error().$query);
    $total_row = mysql_num_rows($result);
}

$page = $_REQUEST['page'];
if ($page < 1) $page = 1;
$numberOfPages = numberofpages();
$resultsPerPage = resultperpage();
$startResults = ($page - 1) * $resultsPerPage;
$totalPages = ceil($total_row / $resultsPerPage);


if(mysql_real_escape_string(trim($_REQUEST['search_text'])) != "")
{
    $select_query = "select * from gst_subdivision where name LIKE '%".mysql_real_escape_string(trim($_REQUEST['search_text']))."%' and type='output_gst' ORDER BY name ASC LIMIT $startResults, $resultsPerPage";
    $select_result = mysql_query($select_query) or die('error in query select gst_subdivision query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
}
else
{
    $select_query = "select * from gst_subdivision where type='output_gst' ORDER BY name ASC LIMIT $startResults, $resultsPerPage";
    $select_result = mysql_query($select_query) or die('error in query select gst_subdivision query '.mysql_error().$select_query);
    $select_total = mysql_num_rows($select_result);
}

$halfPages = floor($numberOfPages / 2);
$range = array('start' => 1, 'end' => $totalPages);
$isEven = ($numberOfPages % 2 == 0);
$atRangeEnd = $totalPages - $halfPages;

if($isEven) $atRangeEnd++;

if($totalPages > $numberOfPages)
{
    if($page <= $halfPages)
        $range['end'] = $numberOfPages;
    elseif ($page >= $atRangeEnd)
        $range['start'] = $totalPages - $numberOfPages + 1;
    else
    {
        $range['start'] = $page - $halfPages;
        $range['end'] = $page + $halfPages;
        if($isEven) $range['end']--;
    }
}



?>

<!DOCTYPE html>
<?php include_once ("top_header1.php"); ?>   
<script src="js/datetimepicker_css.js"></script>
<body  data-home-page-title="" class="u-body u-xl-mode" data-lang="en">
  <?php include_once ("top_header2.php"); ?> 
  <?php include_once ("top_menu.php"); ?>
  <?php include_once("main_heading_open.php") ?>
  
	<table style="width:100%;" border="0" style="padding:0px 0px; margin-top:-10px;">
    <tr>
        <td style="align:top;" align="left">
        <h4 class="u-text-2 u-text-palette-1-base " style="padding:0px; margin:0px;">
        GST Subdivision</h4>
  </td>
        <td width="" style="float:right;">
        <input type="button" name="add_button" id="add_button" value="" class="button_add" onClick="search_display();" />
        <input type="button" id="search" value="" class="button_search1" onClick="search_display();" >
<!--
            <input type="button" name="add_button" id="add_button" value="Add" class="button" onClick="return add_div();"  />   --> 
        </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>

<!-------------->
<?php include_once("main_search_open.php") ?>
  <input type="hidden" name="search_check_val" id="search_check_val" value="0" >
  <!--<div id="adddiv" style="display:<?php if($error_msg != "") { ?>block<?php } else { ?>none<?php } ?>;">-->
  
  <table width="95%">
        <tr><td>
    
  <div id="adddiv" style="">
  
    <form name="user_form" id="user_form" action="" method="post" >
        <table width="95%">
            <tr><td width="300px"><h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;">New GST Sub-division Name</h4></td>
            <td><input type="text" id="gst_subdivision" style="width: 250px"  name="gst_subdivision" value="<?php echo $_REQUEST['gst_subdivision']; ?>"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span>&nbsp;&nbsp;
            <input type="hidden" name="gst_type" id="gst_type" value="output_gst">
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();"></td></tr>
            
            <tr><td colspan="2" align="center">
            </td></tr>
        </table>
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        <input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />    
        </form>
        
        </div>
</td><tr>
    <tr><td>
        <br>
        <form name="search_form" id="search_form" action="" method="post" onSubmit="return search_valid();" >
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                <td width="300px"><h4 class="u-text-2 u-text-palette-1-base1 " style="padding:0px; margin:0px;"> GST Sub-division Search By Name</h4></td>
                    <td width="180" align="left" valign="top"><input type="text" style="width: 250px" name="search_text" id="search_text" value="<?php echo mysql_real_escape_string(trim($_REQUEST['search_text'])); ?>" /></td>
                    
                    <td align="left" valign="top" >&nbsp;&nbsp;<input type="submit" name="search_button" id="search_button" value="Search" class="button"  />&nbsp;&nbsp;<input type="button" name="refresh" id="refresh" value="Refresh" class="button" onClick="window.location='input_gst_subdivision.php';"  />&nbsp;&nbsp;
                    </td>
                    
                    
                </tr>
            </table>
            <input type="hidden" name="page" id="page" value=""  />
            </form>
</td></tr></table>
  <?php include_once("main_search_close.php") ?>
 <!-------------->

  <?php include_once("main_body_open.php") ?>
  <?php if($msg != "") { ?>
    <div class="sukses">
        <?php echo $msg; ?>
        </div>
    <?php } else if($error_msg != "") { ?>
    <div class="gagal">
        
        <?php echo $error_msg; ?>
        </div>
    <?php } ?>
    
    <table style="padding:10px;" width="100%"  >
     <tr><td>
        <table class="data">
            <tr class="data">
                <th class="data" width="30px">S.No.</th>
                <th class="data">GST Sub Division Name</th>
                <th class="data" width="75px">Action</th>
            </tr>
            <?php
            if($select_total > 0)
            {
                $i=1;
                while($select_data = mysql_fetch_array($select_result))
                {
                    
                     ?>
                    <tr class="data">
                        <td class="data" width="30px"><?php echo $i; ?></td>
                        <td class="data"><?php echo $select_data['name']; ?></td>
                        <td class="data" width="75px">
                        <center>
                        <a href="javascript:account_delete(<?php echo $select_data['id'] ?>);"><img src="mos-css/img/delete.png" title="Delete" ></a>
                        </center>
                        </td>
                    </tr>
                <?php
                    $i++;
                }
                
            }
            else
            {
                ?>
                <tr class="data" >
                    <td  width="30px" colspan="6" class="record_not_found" >Record Not Found</td>
                </tr>
                <?php
            }
            ?>
            
        </table>
        </td></tr></table>
        <div class="pagination" >
        
        <?php
            
                        if($page > 1)
                        {
                            $page = $page-1;
                            echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" ><span ><< prev</span></a>&nbsp';
                            $page = $page+1;
                        }
                            
                        ?>
                        
                        <?php
                        if($range['end'] != 1)
                        {
                            for ($i = $range['start']; $i <= $range['end']; $i++)
                            {
                                if($i == $page)
                                    echo '<strong><span class="current">'.$i.'</span></strong>&nbsp;';
                                else
                                    echo '<a href="javascript:void(0)" onclick="return show_records('.$i.')"><span >'.$i.'</span></a>&nbsp;';
                            }
                        }
                        ?>
                        
                        <?php
                        if ($page < $totalPages)
                        {
                            $page = $page+1;
                            echo '<a href="javascript:void(0)" onclick="return show_records('.$page.')" >next >></a>&nbsp;';
                            $page = $page-1;
                        }
                        
                     ?>
       
        </div>
    
		<?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>

</body>
</html>
<script>
function add_div()
{
    $("#adddiv").toggle("slow");
}

function validation()
{
    if($("#gst_subdivision").val() == "")
    {
        alert("Please enter gst_subdivision name.");
        $("#gst_subdivision").focus();
        return false;
    }
    else
    {
        $("#action_perform").val("add_user");
        $("#user_form").submit();
        return true;
    }
    
}
function account_delete(del_id)
{
    if(confirm("Are you sure want to delete?!!!!!......"))
    {
        $("#action_perform").val("delete_user");
        $("#del_id").val(del_id);
        $("#user_form").submit();
        return true;
    }
}
function search_valid()
{
    if(document.getElementById("search_text").value=="")
    {
     alert("Please enter search text to search");
     document.getElementById("search_text").focus();
     return false;
    } 
    
}
function show_records(getno)
{
    //alert(getno);
    document.getElementById("page").value=getno;
    document.search_form.submit(); 
}

</script>
