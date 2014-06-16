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
@author Hemanth 
// Written by Adam Khoury January 01, 2011
// http://www.youtube.com/view_play_list?p=442E340A42191003
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php 
// Check to see the URL variable is set and that it exists in the database
if (isset($_GET['id'])) {
	// Connect to the MySQL database  
    include "storescripts/connect_to_mysql.php"; 
	$ordertable="";
	$id=$_GET['id'];
	$userdetail5=mysql_query("select username from onlineorder where id='$id'");
	 $newarrays5=mysql_fetch_array($userdetail5);
	 $username5=$newarrays5["username"];
	 if($username5!=$loginuser){
	 header("location: main.php"); }
	 else
	 {
	 
	 
	 
	$userdetails=mysql_query("select * from onlineorder where id='$id'");
	 $newarray=mysql_fetch_array($userdetails);
	 $username=$newarray["username"];
	 $status=$newarray["status"];
	 
	 $userdetail=mysql_query("select * from Deliverydetails where id='$id'");
	 $newarrays=mysql_fetch_array($userdetail);
	 $Name=$newarrays["Name"];
	 $Email=$newarrays["Email"];
	 $Sex=$newarrays["Sex"];
	 $Address=$newarrays["Address"];
	 $pn=$newarrays["Phone"];
	
	// Use this var to check to see if this ID exists, if yes then get the product 
	// details, if no then exit this script and give message why
	$sql = mysql_query("SELECT * FROM order$id ");
	$carttotal=0;
	$productCount = mysql_num_rows($sql); // count the output amount
    if ($productCount > 0) {
		// get all the product details
		while($row = mysql_fetch_array($sql)){ 
			 $product = $row["product"];
			 $quantity = $row["quantity"];
			 $proprice=mysql_query("select price from products where product_name='$product'");
			 $newrow=mysql_fetch_array($proprice);
			 $price=$newrow["price"];
			 $totalprice=$quantity*$price;
			 $ordertable .= '<table width="100%" border="0" cellspacing="0" cellpadding="6">
        <tr>
		<td width="25%" >' . $product . '</td><td width="25%" >' . $quantity . '</td><td width="25%" >
            Rs' . $price . '
            </td>
          <td width="25%" >' . $totalprice . '</td>
          
        </tr>
      </table>';
			$carttotal=$carttotal+$totalprice; 
         }
		 
	} else {
		echo "no item exists.";
	    exit();
	}
		
} }else {
	echo "Data to render this page is missing.";
	exit();
}
mysql_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OrderID<?php echo $id; ?></title>
<link rel="stylesheet" href="style/css.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.js" ></script>
	<script type="text/javascript" src="js/jquery-bp.js" ></script>
	<script type="text/javascript" src="js/navigation.js" ></script>
</head>
<body>
<div align="center" id="mainWrapper">
  <?php include_once("templateheader.php");?>
  <div id="pageContent">
  <div style="margin:24px; text-align:left;">
   <br />
    
    <table width="100%" border="0" cellspacing="0" cellpadding="4">
    <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>OrderID:</strong></td>
        <td width="35%" bgcolor="#C5DFFA"><strong><?php echo $id;?></strong></td>
        </tr>
        <tr>
      <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>Name:</strong></td>
        <td width="35%" bgcolor="#C5DFFA"><strong><?php echo $Name;?></strong></td>
        </tr>
        <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>EMAIL:</strong></td>
        <td width="35%" bgcolor="#C5DFFA"><strong><?php echo $Email;?></strong></td>
        </tr>
        <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>Sex:</strong></td>
        <td width="35%" bgcolor="#C5DFFA"><strong><?php echo $Sex;?></strong></td>
        </tr>
        <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>Address:</strong></td>
        <td width="75%" bgcolor="#C5DFFA"><strong><?php echo $Address;?></strong></td>
        </tr>
        <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>OrderStatus:</strong></td>
        <td width="75%" bgcolor="#C5DFFA"><strong><?php echo $status;?></strong></td>
        </tr>
        </table>
        
        <br/>
        <br/>
        
  <table width="100%" border="1" cellspacing="0" cellpadding="6">
      <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>product</strong></td>
        <td width="25%" bgcolor="#C5DFFA"><strong>quantity</strong></td>
        <td width="25%" bgcolor="#C5DFFA"><strong>price</strong></td>
        <td width="25%" bgcolor="#C5DFFA"><strong>total</strong></td>
         </tr>
         </table>
     <?php echo $ordertable; ?>
     <br/>
     <br/>
    <div style='font-size:18px; margin-top:12px;' align='right'>Cart Total : RS<?php echo $carttotal; ?> </div> 
    
    <br/>
    <br/>
    </div>
  </div>
  <?php include_once("template_footer.php");?>
</div>
</body>
</html>