<?php 
session_start();
if (isset($_SESSION["loginuser"])) {
    header("location: index.php"); 
    exit();
}
?>
<?php 

error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php 
// Parse the log in form if the user has filled it out and pressed "Log In"
if (isset($_POST["username"]) && isset($_POST["password"])) {

	$loginuser = preg_replace('#[^A-Za-z0-9]#i', '', $_POST["username"]); // filter everything but numbers and letters
    $password = preg_replace('#[^A-Za-z0-9]#i', '', $_POST["password"]); // filter everything but numbers and letters
    // Connect to the MySQL database  
    include "storescripts/connect_to_mysql.php"; 
    $sql = mysql_query("SELECT id FROM loginusers WHERE username='$loginuser' AND password='$password' LIMIT 1"); // query the person
    // ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
    $existCount = mysql_num_rows($sql); // count the row nums
    if ($existCount == 1) { // evaluate the count
	     while($row = mysql_fetch_array($sql)){ 
             $id = $row["id"];
		 }
		 $_SESSION["id"] = $id;
		 $_SESSION["loginuser"] = $loginuser;
		 $_SESSION["password"] = $password;
		 header("location: index.php");
         exit();
    } else {
		echo 'That information is incorrect, try again <a href="index.php">Click Here</a>';
		exit();
	}
}
?>
<?php 
// Run a select query to get my letest 6 items
// Connect to the MySQL database  
include "storescripts/connect_to_mysql.php"; 
$category="shirts";

//if (isset($_POST['item'])){
//$category = $_POST['item'];
//}


$dynamicList = "";
$sql = mysql_query("SELECT * FROM products ORDER BY date_added  DESC LIMIT 6");
$productCount = mysql_num_rows($sql); // count the output amount
if ($productCount > 0) {
	while($row = mysql_fetch_array($sql)){ 
             $id = $row["id"];
			 $product_name = $row["product_name"];
			 $price = $row["price"];
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
			 $dynamicList .= '<li id='.$id.'><a href="product.php?id=' . $id . '"><img  src="inventory_images/' . $id . '.jpg" class="items" height="100" alt="" /></a><br clear="all" /><div><span class="name">' . $product_name . '<br/>Price</span>: Rs<span class="price">' . $price . '</span><br/><form id="form1" name="form1" method="post" action="index.php">
        <input type="hidden" name="pid" id="pid" value='.$id.' /><input type="submit" name="button" id="button" value="Add to Shopping Cart" /> </form></div><br />
           
		    
        
      
        </li>';
    }
} else {
	$dynamicList = "We have no products listed in our store yet";
}
mysql_close();
?><?php 

include "storescripts/connect_to_mysql.php";
// Parse the form data and add inventory item to the system
if (isset($_POST['Name'])) {
	
    $Name = mysql_real_escape_string($_POST['Name']);
	$Email = mysql_real_escape_string($_POST['Email']);
	$Sex = mysql_real_escape_string($_POST['Sex']);
	$Address = mysql_real_escape_string($_POST['Address']);
	$Username = $_POST['Username'];
	$Password = mysql_real_escape_string($_POST['Password']);
	$pn = mysql_real_escape_string($_POST['pn']);
	
	// See if that product name is an identical match to another product in the system
	$sql = mysql_query("SELECT id FROM loginusers WHERE username='$Username' LIMIT 1");
	$userMatch = mysql_num_rows($sql); // count the output amount
    if ($userMatch > 0) {
		echo 'Sorry the usernamealready exists, <a href="registration.php">click here</a>';
		exit();
	}
	$sql = mysql_query("INSERT INTO loginusers (username, password, last_log_date) 
        VALUES('$Username','$Password',now())") or die (mysql_error());
	// Add this product into the database now
	$sql = mysql_query("INSERT INTO registeruser (Name, Email, Sex, Address,phonenumber , Username,Password, date_added) 
        VALUES('$Name','$Email','$Sex','$Address','$pn','$Username','$Password',now())") or die (mysql_error());
     $pid = mysql_insert_id();
	// Place image in the folder 
	$newname = "$pid.jpg";
	require("PHPMailer/class.phpmailer.php");
include("PHPMailer/class.smtp.php");
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 1; 
$mail->SMTPSecure = 'ssl'; 
$mail->Mailer = "smtp";

$mail->Host = "smtp.gmail.com";
 $mail->Port = 465;

$mail->SMTPAuth = true;
$mail->Username = 'hemanths1488';
$mail->Password = 'sukumaran';
$mail->From     = "hemanths1488@gmail.com";
$mail->FromName = "EcartIndia"; 
$mail->AddAddress($Email);
$mail->Subject = "Registration Complete Password fine here within";
$mail->Body = 'Hi,Thank you for registering with ecart website your password is '.$Password.'
 
 Regards
 
 Admin';

if(!$mail->Send())
{
   echo "Error sending: " . $mail->ErrorInfo;;
}

	move_uploaded_file( $_FILES['fileField']['tmp_name'], "user_images/$newname");
	header("location: registration.php"); 
    exit();
}
?>
<?php 
// Run a select query to get my letest 6 items
// Connect to the MySQL database  
include "storescripts/connect_to_mysql.php"; 

$categorylist = "";
$sql = mysql_query("SELECT * FROM category");
$productCount = mysql_num_rows($sql); // count the output amount
if ($productCount > 0) {
	while($row = mysql_fetch_array($sql)){ 
             $cid = $row["id"];
			 $category_name = $row["category"];
			 $categorylist .= '<div class="lmenu"><img src="images/ico2.gif" width="7" height="5" border="0" alt="" hspace="6"><a href="index1.php?id=' . $cid . '" class="top11">' . $category_name . '</a></div>
								<div><img src="images/line_h2.gif" width="186" height="1" border="0" alt=""></div>';
    }
} else {
	$categorylist = "We have no products listed in our store yet";
}
mysql_close();
?>
<?php 

/** 
 * The letter l (lowercase L) and the number 1 
 * have been removed, as they can be mistaken 
 * for each other. 
 */ 
$genpwd=" ";
function createRandomPassword() { 

    $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 

    while ($i <= 7) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 

    return $pass; 

} 

// Usage 
 
$genpwd=createRandomPassword();
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Store Home Page</title>
<link rel="stylesheet" href="style/css.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.js" ></script>
	<script type="text/javascript" src="js/jquery-bp.js" ></script>
	<script type="text/javascript" src="js/navigation.js" ></script>
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
    <script src="scripts/pwdwidget.js" type="text/javascript"></script> 
       </head>
<body>
<div align="center" id="mainWrapper">
  <?php include_once("templatehead.php");?>
  <div id="pageContent">
  
   
  <table width="100%" border="0" cellspacing="0" cellpadding="10" height="500">
  <tr>
    <td width="50%" valign="top">
    <table border="0" cellpadding="0" cellspacing="0"  style="background: url(images/left_bg.gif)">
				<tr>
					<td><img src="images/left_left.gif" width="21" height="29" border="0" alt=""></td>
					<td><img src="images/spacer.gif" width="7" height="1" border="0" alt=""></td>
					<td width="170"><div class="lb">CATEGORIES</div> <div class="lw">CATEGORIES</div></td>
					<td><img src="images/left_right.gif" width="6" height="29" border="0" alt=""></td>
				</tr>
			</table>
   <table border="0" cellpadding="0" cellspacing="0">
			   	<tr>
					<td style="background: url(images/c_left.gif)"><img src="images/spacer.gif" width="1" height="1" border="0" alt=""></td>
					<td width="194" align="center">
						
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td>
    <?php echo $categorylist; ?>
   </td>
							</tr>
						</table>
					</td>
			   		<td style="background: url(images/c_right.gif)"><img src="images/spacer.gif" width="1" height="1" border="0" alt=""></td>
			   	</tr>
				<tr>
					<td><img src="images/c4.gif" width="5" height="5" border="0" alt=""></td>
					<td style="background: url(images/c_bot.gif)"><img src="images/spacer.gif" width="1" height="1" border="0" alt=""></td>
					<td><img src="images/c3.gif" width="5" height="5" border="0" alt=""></td>
				</tr>
			</table>
        </td>
    <td width="20%" valign="top"><table border="0" cellpadding="0" cellspacing="0"  style="background: url(images/left_bg.gif)">
				<tr>
					<td><img src="images/left_left.gif" width="21" height="29" border="0" alt=""></td>
					<td><img src="images/spacer.gif" width="7" height="1" border="0" alt=""></td>
					<td width="580"><div class="lb">FEATURED PRODUCTS</div> <div class="lw">FEATURED PRODUCTS</div></td>
					<td><img src="images/left_right.gif" width="6" height="29" border="0" alt=""></td>
				</tr>
			</table>
            	<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="background: url(images/c_left.gif)"><img src="images/spacer.gif" width="1" height="1" border="0" alt=""></td>
					<td width="428" align="center">
					<div><img src="images/spacer.gif" width="1" height="12" border="0" alt=""></div>
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							
    <div id="wrap">
    <ul>
      <p><?php echo $dynamicList; ?><br />
        </p>
        </ul>
        </div>
      <p><br />
      </p>	</tr>
					</table>
					<div><img src="images/spacer.gif" width="1" height="14" border="0" alt=""></div>
					</td>
					<td style="background: url(images/c_right.gif)"><img src="images/spacer.gif" width="1" height="1" border="0" alt=""></td>
				</tr>
				<tr>
					<td><img src="images/c4.gif" width="5" height="5" border="0" alt=""></td>
					<td style="background: url(images/c_bot.gif)"><img src="images/spacer.gif" width="1" height="1" border="0" alt=""></td>
					<td><img src="images/c3.gif" width="5" height="5" border="0" alt=""></td>
				</tr>
			</table></td>
    <td width="30%" valign="top">
    <table border="0" cellpadding="0" cellspacing="0"  style="background: url(images/left_bg.gif)">
				<tr>
					<td><img src="images/left_left.gif" width="21" height="29" border="0" alt=""></td>
					<td><img src="images/spacer.gif" width="7" height="1" border="0" alt=""></td>
					<td width="450"><div class="lb">Register</div> <div class="lw">Register</div></td>
					<td><img src="images/left_right.gif" width="6" height="29" border="0" alt=""></td>
				</tr>
			</table>
            	<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="background: url(images/c_left.gif)"><img src="images/spacer.gif" width="1" height="1" border="0" alt=""></td>
					<td width="428" align="center">
					<div><img src="images/spacer.gif" width="1" height="12" border="0" alt=""></div>
												
      <table border="0" cellpadding="0" cellspacing="0">
						<tr>
                         <div id="left_bar">
         <form action="registration.php" enctype="multipart/form-data" name="myForm" id="myform" method="post">
    <table border="0" cellspacing="0" cellpadding="6">
      <tr>
        <td width="20%" align="right">Name</td>
        <td width="80%"><label>
          <input name="Name" type="text" id="Name" size="40" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Email</td>
        <td><label>
          
          <input name="Email" type="text" id="Email" size="12" />
        </label></td>
      </tr>
      <tr>
        <td align="right">Sex</td>
        <td><label>
          <select name="Sex" id="Sex">
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          </select>
        </label></td>
      </tr>
      
      <tr>
        <td align="right">Address</td>
        <td><label>
          <textarea name="Address" id="Address" cols="40" rows="5"></textarea>
        </label></td>
      </tr>
      <tr>
        <td align="right">Photo</td>
        <td><label>
          <input type="file" name="fileField" id="fileField" />
        </label></td>
      </tr>   
      <tr>
        <td width="20%" align="right">mobilenumber</td>
        <td width="80%"><label>
          <input name="pn" type="text" id="pn" size="40" />
        </label></td>
      </tr>
      <tr>
        <td width="20%" align="right">Username</td>
        <td width="80%"><label>
          <input name="Username" type="text" id="Username" size="40" />
        </label></td>
      </tr>
      
      <tr>
        <td width="20%" align="right"></td>
        <td width="80%">
    
    <input type='hidden' name='Password' id='Password' value="<?php echo $genpwd; ?>" maxlength="50" />
    </td>
      </tr>   
      <tr>
        <td>&nbsp;</td>
        <td><label>
          <input type="submit" name="button" id="button" value="Register" />
        </label></td>
      </tr>
    </table>
    </form><script type='text/javascript'>
// <![CDATA[
       
    var frmvalidator  = new Validator("myform");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();
    frmvalidator.addValidation("Name","req","Please provide your name");

    frmvalidator.addValidation("Email","req","Please provide your email address");
	frmvalidator.addValidation("pn","req","Please provide your email address");

    frmvalidator.addValidation("Email","email","Please provide a valid email address");

    frmvalidator.addValidation("Username","req","Please provide a username");
    
    frmvalidator.addValidation("Password","req","Please provide a password");

// ]]>
</script>	<div><img src="images/spacer.gif" width="1" height="14" border="0" alt=""></div>
					</td>
					<td style="background: url(images/c_right.gif)"><img src="images/spacer.gif" width="1" height="1" border="0" alt=""></td>
				</tr>
				<tr>
					<td><img src="images/c4.gif" width="5" height="5" border="0" alt=""></td>
					<td style="background: url(images/c_bot.gif)"><img src="images/spacer.gif" width="1" height="1" border="0" alt=""></td>
					<td><img src="images/c3.gif" width="5" height="5" border="0" alt=""></td>
				</tr>
			</table>
      </td>
  </tr>
</table>

  </div>
  <?php include_once("template_footer.php");?>
</div>
</body>
</html>