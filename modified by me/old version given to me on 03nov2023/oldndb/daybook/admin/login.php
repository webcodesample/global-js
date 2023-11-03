<?php 
session_start();
include_once ("../connection.php");
if($_SESSION['username'] != "")
{
	echo '<script>window.location="index.php";</script>';
}

$msg="";
if ($_POST['submit']=='Login')
{
$user_name = mysql_real_escape_string(trim($_POST['user_name']));
$base64_pass = base64_encode(mysql_real_escape_string(trim($_POST['pwd'])));
$sql = "SELECT * FROM user WHERE username = '$user_name' AND password = '$base64_pass' "; 
//echo "$sql";
  //  exit();			
$result = mysql_query($sql) or die (mysql_error()); 
$row=mysql_fetch_array($result);
$num = mysql_num_rows($result);

	if ( $num != 0 ) { 
		// A matching row was found - the user is authenticated. 
		session_start(); 

		$_SESSION['username']= $row['username'];  
		$_SESSION['userId']= $row['userid'];
		$_SESSION['authority']=$row['authority'];
		$_SESSION['admin_flag']=$row['admin'];
		echo '<script>window.location="index.php";</script>';
		
	} else
    {
$msg="Invalid Username OR Password.";
    }
	
}

 
?>

<!DOCTYPE html>
<?php include_once ("top_header1.php"); ?>   
<style>
{
    padding: 0px;
    margin: 0px;
}
body {
   
    /*background: linear-gradient(315deg, #FFF 0%, ##e8cec7 100%);*/
    background: linear-gradient(315deg, #6c1801 30%, #FFF 100%);
   
}
header {
    color: white;
    align-items: center;
    height: 10vh;
    box-shadow: 5px 5px 10px rgb(0,0,0,0.3);
    background: linear-gradient(315deg, #DE461B 0%, #FFF 100%);
    color: #fff; border-color: #C8360E;
          
}
h1 {
    letter-spacing: 1.5vw;
    font-family: 'system-ui';
    text-transform: uppercase;
    text-align: center;
}
main {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 75vh;
    width: 100%;
    background-size: cover;
}
.form_class {
    width: 500px;
    padding: 40px;
    border-radius: 8px;
    background-color: white;
    font-family: 'system-ui';
    box-shadow: 5px 5px 10px rgb(0,0,0,0.3);
}
.form_div {
    text-transform: uppercase;
}
.form_div > label {
    letter-spacing: 3px;
    font-size: 1rem;
}
.info_div {
    text-align: center;
    margin-top: 20px;
}
.info_div {
    letter-spacing: 1px;
}
.field_class {
    width: 100%;
    border-radius: 6px;
    border-style: solid;
    border-width: 1px;
    padding: 5px 0px;
    text-indent: 6px;
    margin-top: 10px;
    margin-bottom: 20px;
    font-family: 'system-ui';
    font-size: 0.9rem;
    letter-spacing: 2px;
}
.submit_class {
    border-style: none;
    border-radius: 5px;
    background-color: #FFE6D4;
    padding: 8px 20px;
    font-family: 'system-ui';
    text-transform: uppercase;
    letter-spacing: .8px;
    display: block;
    margin: auto;
    margin-top: 10px;
    box-shadow: 2px 2px 5px rgb(0,0,0,0.2);
    cursor: pointer;
}
footer {
  
    height: 5vh;
    background-color: #7c1b00;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom:-20px;
    box-shadow: -5px -5px 10px rgb(0,0,0,0.3);
}
footer > p {
    text-align: center;
    font-family: 'system-ui';
    letter-spacing: 3px;
}
footer > p > a {
    text-decoration: none;
    color: white;
    font-weight: bold;
}

</style>
<body>
<main>
	
        <form id="login_form" name="login_form" class="form_class" action="" method="post">
          <h3 style="margin-top:-10px;text-align: center;color:#DE461B;">Login Administrator</h3>
          <div style="color:#FF0000; font-weight:bold;text-align: center; "><?php echo $msg.'<br><br>'; ?></div>
            <div class="form_div">
                <label>User Name:</label>
                <input class="field_class" name="user_name" id="user_name" name="login_txt" type="text" placeholder="Enter User Name" autofocus>
                <label>Password:</label>
                <input name="pwd" id="pwd" class="field_class"  type="password" placeholder="Enter Password">
            
                <input type="submit" class="submit_class" name="submit" id="submit"  class="button" value="Login" onclick="return validarLogin()">
	            </div>
            <div class="info_div">
                <p>Don't have an account? <a href="register/reg-form.php">Sign-up</a></p>
            </div>
        </form>
    </main>
</body>  

</html>


<script>
function validarLogin()
{
    
	if(document.getElementById("user_name").value=="" )
	{
		alert("Please Enter User Name");
		document.getElementById("user_name").focus();
	 	return false;
	}
	else if(document.getElementById("pwd").value=="")
	{
	 alert("Please enter Password");
	 document.getElementById("pwd").focus();
	 return false;
	}
	else {
	 return true;
	} 
	
}

</script>