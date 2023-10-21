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
    $fname=mysql_real_escape_string(trim($_REQUEST['fname']));
    $lname=mysql_real_escape_string(trim($_REQUEST['lname']));
    $full_name = $fname.' '.$lname;
    $username=mysql_real_escape_string(trim($_REQUEST['username']));
    $password=base64_encode(mysql_real_escape_string(trim($_REQUEST['password'])));
    $phone=mysql_real_escape_string(trim($_REQUEST['phone']));
    $mobile=mysql_real_escape_string(trim($_REQUEST['mobile']));
    $email=mysql_real_escape_string(trim($_REQUEST['email']));
    $current_address=mysql_real_escape_string(trim($_REQUEST['current_address']));
    $permanent_address=mysql_real_escape_string(trim($_REQUEST['permanent_address']));
    
    if(mysql_real_escape_string(trim($_REQUEST['same_current'])) == "yes")
    {
        $same_address = "yes";
    }
    else
    {
        $same_address = "no";
    }
    $admin=mysql_real_escape_string(trim($_REQUEST['admin']));
    
    $auth_arr = $_REQUEST['authority'];
    $authority = implode(",",$auth_arr);
    $quuerrr="select userid from user where email='".$email."' OR username = '".$username."'";
    
    $sql=mysql_query($quuerrr) or die('error in query '.mysql_error().$quuerrr);
    $no=mysql_num_rows($sql);
    if($no > 0)
    {
        
        $data=mysql_fetch_array($sql);
        if($data['email'] == $email && $data['username'] != $username)
        {
            $error_msg = "E-Mail ID already exist try another";
        }
        else if($data['email'] != $email && $data['username'] == $username)
        {
            $error_msg = "username already exist try another";
        }
        else
        {
            $error_msg = "E-Mail ID and Username already exist try another";
        }
        
    }
    else
    {
        $query="insert into user set fname = '".$fname."', lname = '".$lname."', full_name = '".$full_name."', username = '".$username."', password = '".$password."', mobile = '".$mobile."', email = '".$email."', current_address = '".$current_address."', same_address = '".$same_address."', permanent_address = '".$permanent_address."',admin = '".$admin."',authority = '".$authority."', create_date = '".getTime()."'";
        $result= mysql_query($query) or die('error in query '.mysql_error().$query);
        
       
        $msg = "User added successfully.";
        header("Location:user.php?msg=User added successfully");
    }
    
}

/*     Deletion  Account   */
     
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
        New User Account Create</h4>
  </td>
        <td width="" style="float:right;">
        <a href="user.php" title="Back" ><input type="button" name="back_button" id="back_button" value="" class="button_back"  /></a>
         </td>
    </tr>
</table>
<?php include_once("main_heading_close.php") ?>

<?php include_once("main_body_open.php") ?>
    
<table width="100%" style="padding:10px;" >
  <tr><td>
  <table width="100%" class="tbl_border" >
    <tr><td>
        <?php if($msg != "") { ?>
    <div class="sukses">
        <?php echo $msg; ?>
        </div>
    <?php } else if($error_msg != "") { ?>
    <div class="gagal">
        
        <?php echo $error_msg; ?>
        </div>
    <?php } ?>
    
    <div id="adddiv">
    
    <form name="user_form" id="user_form" action="" method="post" >
        <table width="95%">
            <tr>
            <td valign="top">
                        <table width="100%">
                        <tr><td width="125px">First Name</td>
            <td><input type="text" id="fname"  name="fname" style="width:250px;" value="<?php echo $_REQUEST['fname']; ?>"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >Username</td>
            <td><input type="text" id="username"  name="username"  style="width:250px;" value="<?php echo $_REQUEST['username']; ?>"/></td></tr>
            <tr><td align="left" valign="top" >Mobile</td>
            <td><input type="text"  name="mobile" id="mobile" style="width:250px;" value="<?php echo $_REQUEST['mobile']; ?>" />&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td valign="top">Current Address</td>
            <td><textarea name="current_address" id="current_address" style="width:250px; height:100px;"><?php echo $_REQUEST['current_address']; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr>
                    <td align="left" valign="top" style="vertical-align:top">Administrator</td>
                    
                  <td align="left" valign="top">
                    <!--<select name="admin" id="admin" onChange="return checkedAll(this.value);" > -->

                    <select name="admin" id="admin" style="width:150px;" >
                    <option value="">-Select-</option>
                    <option value="yes" <?php if($_REQUEST['admin'] == "yes") echo 'selected="selected"'; ?>  >yes</option>
                    <option value="no" <?php if($_REQUEST['admin'] == "no") echo 'selected="selected"'; ?> >no</option>
                    
                    </select>&nbsp;<strong style="color:#FF0000;" >*</strong>
                   
                    </td>
                    
                    </tr>
            <tr>
            <td align="left" valign="top">Authority</td>
    <td align="left" valign="top" id="rights" name="auth" >
    <?php 
            if($_SESSION['admin_flag']=="yes") {
            ?>
                <input type="checkbox" name="chck_all" id="chck_all" value="" onClick="return checkedAll(this.value);"  />
           Check All</br>        
           
            <?php
    
           }
            ?>
            
			<?php 
            if($_SESSION['admin_flag']=="yes") {
            ?>
                <input type="checkbox" name="display_all" id="display_all" value="" onClick="return DisplayAll(this.value);"  />
           Expand All</br>        
           
            <?php
    
           }
            ?>
  </td></tr> 
                    </table>
                    </td>
                    <td valign="top">
                        <table width="100%">
                        <tr><td >Last Name</td>
            <td><input type="text" id="lname" style="width:250px;"  name="lname" value="<?php echo $_REQUEST['lname']; ?>"/></td></tr>
            <tr><td >Password</td>
            <td><input type="password" style="width:250px;" id="password"  name="password" value="<?php echo $_REQUEST['password']; ?>"/></td></tr>
            
            <tr><td >E-Mail Id</td>
            <td><input type="text" id="email" style="width:250px;" name="email" value="<?php echo $_REQUEST['email']; ?>"/>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            <tr><td >Same as Current</td>
            <td><input type="checkbox" name="same_current" id="same_current" value="yes" onClick="return same_address();" <?php if($_REQUEST['same_current'] == "yes") { echo 'checked="checked"'; } ?> /></td></tr>
            
            <tr><td align="left" valign="top" >Permanent Address</td>
            <td><textarea name="permanent_address" id="permanent_address" style="width:250px; height:100px;"><?php echo $_REQUEST['permanent_address']; ?></textarea>&nbsp;<span style="color:#FF0000; font-weight:bold;"  >*</span></td></tr>
            
                    </table>
                    </td>
            </tr>
            
            <tr><td colspan="2">
             
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="customer_div">
    
    <tr><td width="10" height="5"></td><td colspan="5"></td></tr>
    <tr>
    <?php 
    $auth_arr = explode(",",$_REQUEST['authority']);
    $i = 1;
 
        $menu_query = "select * from menu where parent_id = 0";
    $menu_result = mysql_query($menu_query) or die("error in query".mysql_error());
    while($menurow = mysql_fetch_array($menu_result))
    { 
        if($i%3 == 0)
        {   ?>
        
            <td width="110" align="left" valign="top">
        <div style="height:25px; cursor:pointer; ">
                    <div id="submenu_image_<?php echo $i; ?>" style="float:left; " onClick="return show_submenu_div('<?php echo $i; ?>');" >&nbsp;&nbsp;<img  src="images/plus.png" width="15px;" /></div>
                    <div style="height:20px; " onClick="return show_submenu_div('<?php echo $i; ?>');">&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $menurow['name']; ?></strong>
                            </div> </div>
        
        
        <div id="submenu_div_<?php echo $i; ?>" style="display:none; width:100px; " >
        <table cellpadding="0" cellspacing="0" border="0" width="110" class="customer_div" >
        <?php
        $submenu_query = "query_".$arr[$i];
        $submenu_result = "result_".$arr[$i];
        $submenurow = "data_".$arr[$i];
        $submenu_query = "select * from menu where parent_id = '".$menurow['id']."'";
        $submenu_result = mysql_query($submenu_query) or die("error in query".mysql_error());
        while($submenurow = mysql_fetch_array($submenu_result))
        {
            
            ?>
            <tr>
            <td width="10"><input type="checkbox" name="authority[]" <?php  if(in_array($submenurow['id'],$auth_arr)) { echo 'checked="checked"'; }  ?>   value="<?php echo $submenurow['id']; ?>" /></td>
        <td width="100" align="left" valign="top"><?php echo $submenurow['name']; ?>
        </td>
        </tr>
        <?php } ?>
        </table>
        
        </div>
        <br />
        </td>
        </tr>
        <tr>
        
    <?php    }
        else
        {  ?>
        <td width="110" align="left" valign="top">
        <div style="height:25px; cursor:pointer; ">
                    <div id="submenu_image_<?php echo $i; ?>" style="float:left; " onClick="return show_submenu_div('<?php echo $i; ?>');" >&nbsp;&nbsp;<img  src="images/plus.png" width="15px;" /></div>
                    <div style="height:20px; " onClick="return show_submenu_div('<?php echo $i; ?>');">&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $menurow['name']; ?></strong>
                            </div> </div>
    
        
        <div id="submenu_div_<?php echo $i; ?>" style="display:none; width:100px; " >
        <table cellpadding="0" cellspacing="0" border="0" width="110" class="customer_div" >
        <?php
        $submenu_query = "query_".$arr[$i];
        $submenu_result = "result_".$arr[$i];
        $submenurow = "data_".$arr[$i];
        $submenu_query = "select * from menu where parent_id = '".$menurow['id']."'";
        $submenu_result = mysql_query($submenu_query) or die("error in query".mysql_error());
        while($submenurow = mysql_fetch_array($submenu_result))
        {
            ?>
            <tr>
            <td width="10"><input type="checkbox" name="authority[]" <?php if(in_array($submenurow['id'],$auth_arr)) { echo 'checked="checked"'; }  ?>   value="<?php echo $submenurow['id']; ?>" /></td>
        <td width="100" align="left" valign="top"><?php echo $submenurow['name']; ?>
        </td>
        </tr>
        <?php } ?>
        </table>
        
        </div>
        <br />
        </td>
        
<?php        }
    $i++;
    } 
    ?>
    </tr>
    </table>
 
 
      </td>
    
                   </tr>
            <tr><td></td><td>
            <input type="button" class="button" name="submit_button" id="submit_button" value="Submit" onClick="return validation();">
            </td></tr>
        </table>
        <input type="hidden" name="action_perform" id="action_perform" value="" >
        <input type="hidden" name="del_id" id="del_id" value="" >
        <input type="hidden" name="count" id="count" value="<?php echo $i; ?>"  />    
        </form>
        
        </div>
</td></tr></table></td></tr></table>            
        <?php include_once("main_body_close.php") ?>
        <?php include_once ("footer.php"); ?>
</body>
</html>
<script>

function validation()
{
    if($("#fname").val() == "")
    {
        alert("Please enter first name.");
        $("#fname").focus();
        return false;
    }
    else if($("#username").val() == "")
    {
        alert("Please enter Username.");
        $("#username").focus();
        return false;
    }
    else if($("#password").val() == "")
    {
        alert("Please enter password.");
        $("#password").focus();
        return false;
    }
    else if($("#mobile").val() == "")
    {
        alert("Please enter mobile number.");
        $("#mobile").focus();
        return false;
    }
    else if($("#email").val() == "")
    {
        alert("Please enter email address.");
        $("#email").focus();
        return false;
    }
    else if($("#current_address").val() == "")
    {
        alert("Please enter current address.");
        $("#current_address").focus();
        return false;
    }
    else if(document.getElementById("admin").value == "yes")
    {
        for (var i = 0; i < document.getElementById('user_form').elements.length; i++)
        {
            if(document.getElementById('user_form').elements[i].checked == true)
            {
                check = 1;
            }
        }
        if(check == 1)
        {
            $("#action_perform").val("add_user");
            $("#user_form").submit();
            return true;
        }
        else
        {
            alert("Please check Atleast one checkbox. ");
            return false;
        }
    }
    else if(document.getElementById("admin").value == "no")
    {
        for (var i = 0; i < document.getElementById('user_form').elements.length; i++)
        {
            if(document.getElementById('user_form').elements[i].checked == true)
            {
                check = 1;
            }
        }
        if(check == 1)
        {
            $("#action_perform").val("add_user");
            $("#user_form").submit();
            return true;
        }
        else
        {
            alert("Please check Atleast one checkbox. ");
            return false;
        }
    }
    else
    {
        $("#action_perform").val("add_user");
        $("#user_form").submit();
        return true;
    }
    
}              
function show_records(getno)
{
    //alert(getno);
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

/* function checkedAll(value)
{
    if(value == "yes")
    {
        for (var i = 0; i < document.getElementById('user_form').elements.length; i++)
        {
            document.getElementById('user_form').elements[i].checked = true;
        }
    }
    else
    {
        for (var i = 0; i < document.getElementById('user_form').elements.length; i++)
        {
            document.getElementById('user_form').elements[i].checked = false;
        }
    }
}
*/
 function checkedAll(value)
{
   if(document.getElementById("chck_all").checked==true)
	{
        
        for (var i = 0; i < document.getElementById('user_form').elements.length; i++)
        { 
        if(i==7||i==11){}
        else{    
            document.getElementById('user_form').elements[i].checked = true;
        }
        }
      
	}
	else
	{
		 for (var i = 0; i < document.getElementById('user_form').elements.length; i++)
        {
            if(i==7||i==11){}

            else
            {
            document.getElementById('user_form').elements[i].checked = false;
            }
        }
      
	}
   
    
}
//DisplayAll,display_all

function DisplayAll(value)
{
   if(document.getElementById("display_all").checked==true)
	{
		//var div_id = document.getElementById("submenu_div_"+id);
		//var imageEle = document.getElementById("submenu_image_"+id);
		var count = document.getElementById("count").value;
			//$("#submenu_div_"+id).show("slow");
			//imageEle.innerHTML = '&nbsp;&nbsp;<img  src="images/minus.png" width="15px;" />';
			for(var j=1;j<count;j++)
			{
					var div_id = document.getElementById("submenu_div_"+j);
					var imageEle = document.getElementById("submenu_image_"+j);
					//$("#submenu_div_"+j).hide("slow");
					$("#submenu_div_"+j).show("slow");
					imageEle.innerHTML = '&nbsp;&nbsp;<img  src="images/minus.png" width="15px;" />';
					
				
			}
		
	}
	else
	{
		
		var count = document.getElementById("count").value;

			//$("#submenu_div_"+id).show("slow");
			//imageEle.innerHTML = '&nbsp;&nbsp;<img  src="images/minus.png" width="15px;" />';
			for(var j=1;j<count;j++)
			{
					var div_id = document.getElementById("submenu_div_"+j);
					var imageEle = document.getElementById("submenu_image_"+j);
					//$("#submenu_div_"+j).hide("slow");
					$("#submenu_div_"+j).hide("slow");
					imageEle.innerHTML = '&nbsp;&nbsp;<img  src="images/plus.png" width="15px;" />';
					
				
				
			}
		
      
	}
   
    
}

function show_submenu_div(id)
{
    var count = document.getElementById("count").value;
    
    if(id != "")
    {
        var div_id = document.getElementById("submenu_div_"+id);
        var imageEle = document.getElementById("submenu_image_"+id);
        
        if(div_id.style.display == "block")
        {
            $("#submenu_div_"+id).hide("slow");
            imageEle.innerHTML = '&nbsp;&nbsp;<img  src="images/plus.png" width="15px;" />';
            
        }
        else
        {
            $("#submenu_div_"+id).show("slow");
            imageEle.innerHTML = '&nbsp;&nbsp;<img  src="images/minus.png" width="15px;" />';
            for(var j=1;j<count;j++)
            {
                if(j != id)
                {
                    var div_id = document.getElementById("submenu_div_"+j);
                    var imageEle = document.getElementById("submenu_image_"+j);
                    $("#submenu_div_"+j).hide("slow");
                    imageEle.innerHTML = '&nbsp;&nbsp;<img  src="images/plus.png" width="15px;" />';
                    
                }
                
            }
                
                
                
        }
    }
    
    
    
}
</script>
