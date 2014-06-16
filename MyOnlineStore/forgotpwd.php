<?php 
session_start();
if (isset($_SESSION["loginuser"])) {
    header("location: index.php"); 
    exit();
}
$errors="";
$success="";
?>
<?php 

error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php 
// Parse the log in form if the user has filled it out and pressed "Log In"
if (isset($_POST["username"]) && isset($_POST["email"])) {

	$loginuser = preg_replace('#[^A-Za-z0-9]#i', '', $_POST["username"]); // filter everything but numbers and letters
    $email = $_POST["email"]; // filter everything but numbers and letters
    // Connect to the MySQL database  
    include "storescripts/connect_to_mysql.php"; 
    $sql = mysql_query("SELECT Password FROM registeruser WHERE Username='$loginuser' AND Email='$email' LIMIT 1"); // query the person
    // ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
    $existCount = mysql_num_rows($sql); // count the row nums
    if ($existCount == 1) { // evaluate the count
	     while($row = mysql_fetch_array($sql)){ 
             $pass = $row["Password"];
		 }
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

$mail->FromName = "EcartIndia"; 
$mail->AddAddress($email);
$mail->Subject = "Password for ecartindia";
$mail->Body = 'Hi,Please find your password : '.$pass.'
 
 Regards
 
 Admin';

if(!$mail->Send())
{
   $errors="Mail not send";
}
     $success="Password has been send to your mail ID:";    
    } else {
		$errors="please provide the correct username and emailid";
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Store Home Page</title>
<link rel="stylesheet" href="style/css.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.js" ></script>
	<script type="text/javascript" src="js/jquery-bp.js" ></script>
	<script type="text/javascript" src="js/navigation.js" ></script>
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
					<td width="450"><div class="lb">Login</div> <div class="lw">Login</div></td>
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
       <form id="form1" name="form1" method="post" action="forgotpwd.php">
        User Name:<br />
          <input name="username" type="text" id="username" size="40" />
        <br /><br />
        EmailID:<br />
       <input name="email" type="text" id="email" size="40" />
       <br />
       <br />
       <br />
       
         <input type="submit" name="button" id="button" value="Send password" />
       
      </form><br />
      <font color="#FF0000" size="2"><?php echo $errors ?></font>
      <font color="#336600" size="2"><?php echo $success ?></font>
      <P><a href="registration.php" >Register new user</a></P></div>
      </div></tr></table>
    					<div><img src="images/spacer.gif" width="1" height="14" border="0" alt=""></div>
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