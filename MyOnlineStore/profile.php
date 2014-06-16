<?php 

session_start();
if (!isset($_SESSION["loginuser"])) {
    header("location: main.php"); 
    exit();
}
$error=" ";
// Be sure to check that this manager SESSION value is in fact in the database
$loginuserid = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
$loginuser = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["loginuser"]); // filter everything but numbers and letters
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
// Run mySQL query to be sure that this person is an admin and that their password session var equals the database information
// Connect to the MySQL database  
include "storescripts/connect_to_mysql.php"; 
$sql = mysql_query("SELECT * FROM loginusers WHERE id='$loginuserid' AND username='$loginuser' AND password='$password' LIMIT 1"); // query the person
// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
$existCount = mysql_num_rows($sql); // count the row nums
if ($existCount == 0) { // evaluate the count
	 echo "Your login session has expired";
     exit();
}
?>
<?php 
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php 
include "storescripts/connect_to_mysql.php"; 
// Parse the form data and add inventory item to the system
if (isset($_POST['old'])) {
	include "storescripts/connect_to_mysql.php"; 
	$old = mysql_real_escape_string($_POST['old']);
    $New = mysql_real_escape_string($_POST['New']);
	$conf = mysql_real_escape_string($_POST['conf']);
	$sql2 = mysql_query("SELECT * FROM registeruser WHERE Password='$old' and  Username='$loginuser'  LIMIT 1");
    $productCount1 = mysql_num_rows($sql2); // count the output amount
    if ($productCount1 > 0) {
		
		if($New ==$conf)
		{
	$sql3 = mysql_query("UPDATE registeruser SET Password='$New' WHERE Username='$loginuser'");
	$sql4 = mysql_query("UPDATE loginusers SET password='$New' WHERE username='$loginuser'");
	$error="password has been successfully changed";
	$_SESSION["password"] = $New;
		}
		else
		$error="please enter same passoword";
	}else
	$error="please enter the correct password";
}
	
	?>
<?php 
include "storescripts/connect_to_mysql.php"; 
// Parse the form data and add inventory item to the system
if (isset($_POST['Name'])) {
	include "storescripts/connect_to_mysql.php"; 
	$loginusername = mysql_real_escape_string($_POST['loginuser']);
    $Name = mysql_real_escape_string($_POST['Name']);
	$Email = mysql_real_escape_string($_POST['Email']);
	$Sex = mysql_real_escape_string($_POST['Sex']);
		$Address = mysql_real_escape_string($_POST['Address']);
		$phn = mysql_real_escape_string($_POST['pn']);
	// See if that product name is an identical match to another product in the system
	$sql = mysql_query("UPDATE registeruser SET Name='$Name', Email='$Email', Sex='$Sex', Address='$Address', phonenumber='$phn' WHERE Username='$loginusername'");
	if ($_FILES['fileField']['tmp_name'] != "") {
	    // Place image in the folder 
	    $newname = "$pid.jpg";
	    move_uploaded_file($_FILES['fileField']['tmp_name'], "user_images/$newname");
	}
	header("location: profile.php"); 
    exit();
}
?>
<?php 
include "storescripts/connect_to_mysql.php"; 
// Gather this product's full information for inserting automatically into the edit form below on page

	$targetID = $loginuser;
    $sql = mysql_query("SELECT * FROM registeruser WHERE Username='$targetID' LIMIT 1");
    $productCount = mysql_num_rows($sql); // count the output amount
    if ($productCount > 0) {
	    while($row = mysql_fetch_array($sql)){ 
             
			 $Name = $row["Name"];
			 $Email = $row["Email"];
			 $Sex = $row["Sex"];
			 $Address = $row["Address"];
			 $pn=$row["phonenumber"];
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
		}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Profile</title>
<link rel="stylesheet" href="style/profilecss.css" type="text/css" media="screen" />

<script type="text/javascript" src="js/jquery.js" ></script>
	<script type="text/javascript" src="js/jquery-bp.js" ></script>
	<script type="text/javascript" src="js/navigation.js" ></script>
    <script src="js/jquery-1.7.1.js"></script>
	<script src="js/jquery.ui.core.js"></script>
	<script src="js/jquery.ui.widget.js"></script>
	<script src="js/jquery.ui.accordion.js"></script>
    <script>
	$(function() {
		$( "#accordion" ).accordion({
			event: "mouseover"
		});
	});
	</script>
</head>

<body>
<div align="center" id="mainWrapper">
  <?php include_once("templateheader.php");?>
  <div id="pageContent"><br />
    <div align="right" style="margin-right:32px;"><a href="profile.php#personalForm">+ Edit profile</a></div>
<div align="left" style="margin-left:24px;">
      <h2>Personal info</h2>
      </div>
    <hr />
    <a name="personalForm" id="personalForm"></a>
    <h3>
    &darr; Edit profile <?php echo $loginuser; ?> &darr;
    </h3>
 <div class="main_container">
        <h1 style="text-align: center; margin-bottom: 0.5em;">
          Your Account
        </h1>
        <div class="alert messages"></div>
            <div class="rhsContent">
    <div>
    <div id="accordion">
    <h3><a href="#">Basic information</a></h3>
    <div>
    <p>
      <form action="profile.php" enctype="multipart/form-data" name="myForm" id="myform" method="post">
        
        <input name="user" value="5258655" type="hidden">
        <div class="label" align="left">Name</div>
        <div class="label" align="left"><input name="Name"  value="<?php echo $Name; ?>" type="text" align="left"></div>
        <div class="label" align="left">Email</div>
        <div class="label" align="left"><input name="Email" align="left" value="<?php echo $Email; ?>" type="text"></div>
		<div class="label" align="left">Sex</div><div class="label" align="left"><select name="Sex"  id="Sex" >
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          </select></div>
		  <div class="label" align="left" >Address</div>
        <div class="label" align="left"><textarea name="Address" id="Address" cols="64" rows="5" align="left"><?php echo $Address; ?></textarea></div>
        <div class="label" align="left">Phonenumber</div>
        <div class="label" align="left"><input name="pn" align="left" value="<?php echo $pn; ?>" type="text"></div>
		
		<div class="label" align="left">Upload photo</div>
		<div class="label" align="left">		<input type="file" name="fileField" id="fileField" align="left"/></div>
				<input name="loginuser" type="hidden" value="<?php echo $loginuser; ?>" />
        <div style="float: right; font-size: 0.7em; width: 65%; margin-top: 10px;">
          <input name="save" value="Update" class="button_orange" type="submit">
        </div>
      <div style="display: none;"><input type="hidden" name="_sourcePage" value="qDiGoYz0EIrS2_Z7twth3fpZneCYx8Rn7AWIQ8nZftQ="><input type="hidden" name="__fp" value="yLT5uvRGkCo="></div></form><br/>
    </div></p></div><br/>
    <br/>
    <h3><a href="#">Change password</a></h3>
    <div>
    <p>
    <div style="margin-top: 50px;">
      <form action="profile.php" method="post">
        
        <h4 class="strikeline"> <?php echo $error; ?></h4>
        <div class="label"  align="left">Old Password</div>
       <div class="label" align="left"><input name="old"   type="password" align="left"></div>
        <div class="label"  align="left">New Password</div>
        <div class="label" align="left"><input name="New"   type="password" align="left"></div>
        <div class="label"  align="left">Re-enter new password</div>
        <div class="label" align="left"><input name="conf"   type="password" align="left"></div>
        <div style="float: right; font-size: 0.7em; width: 75%; margin-top: 10px;">
          <input name="changePassword" value="Change Password" class="button_orange" type="submit">
        </div>
      <div style="display: none;"><input type="hidden" name="_sourcePage" value="zf4tkSsvKKLS2_Z7twth3fpZneCYx8Rn7AWIQ8nZftQ="><input type="hidden" name="__fp" value="KgFNvmeH_TI="></div></form>
    </div>
    </p>
    </div>
    </div>
	<div class="floatfix"></div>
      </div>
      <div class="floatfix"></div>
    </div>
    <br />
  <br />
  </div>
  <?php include_once("template_footer.php");?>
</div>
</body>
</html>