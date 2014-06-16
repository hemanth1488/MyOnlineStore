<?php 
@author Hemanth 

error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php 
// Check to see the URL variable is set and that it exists in the database
if (isset($_GET['id'])) {
	// Connect to the MySQL database  
    include "../storescripts/connect_to_mysql.php"; 
	$ordertable="";
	$id=$_GET['id'];
	$userdetails=mysql_query("select * from onlineorder where id='$id'");
	 $newarray=mysql_fetch_array($userdetails);
	 $username=$newarray["username"];
	 $status=$newarray["status"];
	 
	 $userdetail=mysql_query("select * from registeruser where Username='sukumaran'");
	 $newarrays=mysql_fetch_array($userdetail);
	 $Name=$newarrays["Name"];
	 $Email=$newarrays["Email"];
	 $Sex=$newarrays["Sex"];
	 $Address=$newarrays["Address"];
	 
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
		
} else {
	echo "Data to render this page is missing.";
	exit();
}
mysql_close();
?>
<?php 
// Parse the form data and add inventory item to the system

include "../storescripts/connect_to_mysql.php";
if (isset($_POST['orderstate'])) {
	
	$ordst=$_POST['orderstate'];
	$sql = mysql_query("UPDATE onlineorder SET status='$ordst' where id='$id'");
	
	mysql_close();
	header("location: orderdetails.php?id=$id"); 
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OrderID<?php echo $id; ?></title>
<link rel="stylesheet" href="../style/style.css" type="text/css" media="screen" />
</head>
<body>
<div align="center" id="mainWrapper">
  <?php include_once("../template_header.php");?>
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
    <form action="orderdetails.php?id=<?php echo $id;?>" enctype="multipart/form-data" name="myForm" id="myform" method="post">
    <table>
    <tr>
        <td align="right">Change order status to </td>
        <td><select name="orderstate" id="orderstate">
        <option value="<?php echo $status;?>"><?php echo $status;?></option>
          <option value="initiated">initiated</option>
          <option value="processing">processing</option>
          <option value="delivered">delivered</option>
          </select></td>
      </tr>
    <tr>
        <td>&nbsp;</td>
        <td><label>
          <input type="submit" name="button" id="button" value="change status" />
        </label></td>
      </tr>
    </table>
    </form>
  </div>
  </div>
  
</div>
</body>
</html>