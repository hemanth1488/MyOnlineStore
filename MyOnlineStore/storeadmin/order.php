<?php 
@author Hemanth 

session_start();
if (!isset($_SESSION["manager"])) {
    header("location: admin_login.php"); 
    exit();
}
// Be sure to check that this manager SESSION value is in fact in the database
$managerID = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
$manager = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["manager"]); // filter everything but numbers and letters
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
// Run mySQL query to be sure that this person is an admin and that their password session var equals the database information
// Connect to the MySQL database  
include "../storescripts/connect_to_mysql.php"; 
$sql = mysql_query("SELECT * FROM admin WHERE id='$managerID' AND username='$manager' AND password='$password' LIMIT 1"); // query the person
// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
$existCount = mysql_num_rows($sql); // count the row nums
if ($existCount == 0) { // evaluate the count
	 echo "Your login session data is not on record in the database.";
     exit();
}
?>
<?php 
// Run a select query to get my letest 6 items
// Connect to the MySQL database  
include "../storescripts/connect_to_mysql.php"; 
$orderlist = "";
$sql = mysql_query("SELECT * FROM onlineorder WHERE status='initiated' ORDER BY order_date DESC ");
$OrderCount = mysql_num_rows($sql); // count the output amount
if ($OrderCount > 0) {
	while($row = mysql_fetch_array($sql)){ 
             $id = $row["id"];
			 $username = $row["username"];
			 $total = $row["total"];
			 $orderlist .= '<table width="100%" border="0" cellspacing="0" cellpadding="6">
        <tr>
		<td width="25%" >' . $id . '</td><td width="25%" >' . $username . '</td><td width="25%" >
            Rs' . $total . '
            </td>
          <td width="25%" valign="top"><a href="orderdetails.php?id=' . $id . '">View order</a></td>
          
        </tr>
      </table>';
    }
} else {
	$orderlist = "We have no products listed in our store yet";
}
mysql_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Store Admin Area</title>
<link rel="stylesheet" href="../style/style.css" type="text/css" media="screen" />
</head>

<body>
<div align="center" id="mainWrapper">
  <?php include_once("../template_header.php");?>
  <div id="pageContent"><br />
    <div align="left" style="margin-left:24px;">
      <h2>Hello store manager</h2>
      <p>Open Orders</p><br/>
      
       <table width="100%" border="1" cellspacing="0" cellpadding="6">
      <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>OrderID</strong></td>
        <td width="25%" bgcolor="#C5DFFA"><strong>User</strong></td>
        <td width="25%" bgcolor="#C5DFFA"><strong>total</strong></td>
        <td width="25%" bgcolor="#C5DFFA"><strong>details</strong></td>
         </tr>
     <?php echo $orderlist; ?>
     <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr> -->
    </table>
      
    </div>
    <br />
  <br />
  <br />
  </div>
  
</div>
</body>
</html>