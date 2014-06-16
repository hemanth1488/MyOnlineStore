<?php 

session_start();
if (!isset($_SESSION["loginuser"])) {
    header("location: main.php"); 
    exit();
}

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
// Run a select query to get my letest 6 items
// Connect to the MySQL database  
include "storescripts/connect_to_mysql.php"; 
$orderlist = "";
$sql = mysql_query("SELECT * FROM onlineorder WHERE username='$loginuser' ORDER BY order_date DESC ");
$OrderCount = mysql_num_rows($sql); // count the output amount
if ($OrderCount > 0) {
	while($row = mysql_fetch_array($sql)){ 
             $id = $row["id"];
			 $username = $row["username"];
			 $total = $row["total"];
			 $status=$row["status"];
			 $orderdate=$row["order_date"];
			 $orderlist .='<div class="shopp1" id="each-'. $id . '"><div class="label1" align="left">' . $id . '</div><div class="shopp-price1" align="left">  <em>'. $username .'</em></div><div class="shopp-quantity1"  align="left">Rs' . $total . '</div></em><div class="pricetotal1" align="left">' . $status . '</div><div class="label1" align="left">'.$orderdate.'</div><div class="remove1" align="left"><a href="orderdetails.php?id=' . $id . '">View</a></div></div>';
    }
} else {
	$orderlist = "We have no orders for you  listed in our store yet";
}
mysql_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>order status for user</title>
<link rel="stylesheet" href="style/css.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.js" ></script>
	<script type="text/javascript" src="js/jquery-bp.js" ></script>
	<script type="text/javascript" src="js/navigation.js" ></script>
</head>

<body>
<div align="center" id="mainWrapper">
  <?php include_once("templateheader.php");?>
  <div id="pageContent"><br />
    <div align="left" style="margin-left:24px;">
      <h2>Hello <?php echo $loginuser; ?></h2>
      <p>Orders</p><br/>
      
      <table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="background: url(images/c_left.gif)"><img src="images/spacer.gif" width="1" height="1" border="0" alt=""></td>
					<td width="428" align="center">
					<div><img src="images/spacer.gif" width="1" height="12" border="0" alt=""></div>
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							
       <div id="left_bar">
      <div class="cart-info1">
     <div class="shopp1" id="id"><div class="label1" align="left"><b>OrderID</b></div><div class="shopp-price1" align="left"> <em><b>User</b></em></div><div class="shopp-quantity1" align="left"><b>Total</b></div><div class="pricetotal1" align="left"><b>Status</b></div><div class="pricetotal1" align="left"><b>Orderdate</b></div><div class="remove1" align="left"><b>View</b></div></div>
     <?php echo $orderlist; ?>
     <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr> -->
    </div></div>
        </tr>
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
			</table>
    </div>
    <br />
  <br />
  <br />
  </div>
  <?php include_once("template_footer.php");?>
</div>
</body>
</html>