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
 
include "storescripts/connect_to_mysql.php"; 
?>
<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 1 (if user attempts to add something to the cart from the product page)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
	$wasFound = false;
	$i = 0;
	// If the cart session variable is not set or cart array is empty
	if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) { 
	    // RUN IF THE CART IS EMPTY OR NOT SET
		$_SESSION["cart_array"] = array(0 => array("item_id" => $pid, "quantity" => 1));
	} else {
		// RUN IF THE CART HAS AT LEAST ONE ITEM IN IT
		foreach ($_SESSION["cart_array"] as $each_item) { 
		      $i++;
		      while (list($key, $value) = each($each_item)) {
				  if ($key == "item_id" && $value == $pid) {
					  // That item is in cart already so let's adjust its quantity using array_splice()
					  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $pid, "quantity" => $each_item['quantity'] + 1)));
					  $wasFound = true;
				  } // close if condition
		      } // close while loop
	       } // close foreach loop
		   if ($wasFound == false) {
			   array_push($_SESSION["cart_array"], array("item_id" => $pid, "quantity" => 1));
		   }
	}
	header("location: checkout.php"); 
    exit();
}
?>
<?php 

function money_format($format, $number) 
{ 
    $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'. 
              '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/'; 
    if (setlocale(LC_MONETARY, 0) == 'C') { 
        setlocale(LC_MONETARY, ''); 
    } 
    $locale = localeconv(); 
    preg_match_all($regex, $format, $matches, PREG_SET_ORDER); 
    foreach ($matches as $fmatch) { 
        $value = floatval($number); 
        $flags = array( 
            'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ? 
                           $match[1] : ' ', 
            'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0, 
            'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ? 
                           $match[0] : '+', 
            'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0, 
            'isleft'    => preg_match('/\-/', $fmatch[1]) > 0 
        ); 
        $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0; 
        $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0; 
        $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits']; 
        $conversion = $fmatch[5]; 

        $positive = true; 
        if ($value < 0) { 
            $positive = false; 
            $value  *= -1; 
        } 
        $letter = $positive ? 'p' : 'n'; 

        $prefix = $suffix = $cprefix = $csuffix = $signal = ''; 

        $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign']; 
        switch (true) { 
            case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+': 
                $prefix = $signal; 
                break; 
            case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+': 
                $suffix = $signal; 
                break; 
            case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+': 
                $cprefix = $signal; 
                break; 
            case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+': 
                $csuffix = $signal; 

                break; 
            case $flags['usesignal'] == '(': 
            case $locale["{$letter}_sign_posn"] == 0: 
                $prefix = '('; 
                $suffix = ')'; 
                break; 
        } 
        if (!$flags['nosimbol']) { 
            $currency = $cprefix . 
                        ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) . 
                        $csuffix; 
        } else { 
            $currency = ''; 
        } 
        $space  = $locale["{$letter}_sep_by_space"] ? ' ' : ''; 

        $value = number_format($value, $right, $locale['mon_decimal_point'], 
                 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']); 
        $value = @explode($locale['mon_decimal_point'], $value); 

        $n = strlen($prefix) + strlen($currency) + strlen($value[0]); 
        if ($left > 0 && $left > $n) { 
            $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0]; 
        } 
        $value = implode($locale['mon_decimal_point'], $value); 
        if ($locale["{$letter}_cs_precedes"]) { 
            $value = $prefix . $currency . $space . $value . $suffix; 
        } else { 
            $value = $prefix . $value . $space . $currency . $suffix; 
        } 
        if ($width > 0) { 
            $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ? 
                     STR_PAD_RIGHT : STR_PAD_LEFT); 
        } 

        $format = str_replace($fmatch[0], $value, $format); 
    } 
    return $format; 
} 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 2 (if user chooses to empty their shopping cart)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_GET['cmd']) && $_GET['cmd'] == "emptycart") {
    unset($_SESSION["cart_array"]);
}
?>
<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 3 (if user chooses to adjust item quantity)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['item_to_adjust']) && $_POST['item_to_adjust'] != "") {
    // execute some code
	$item_to_adjust = $_POST['item_to_adjust'];
	$quantity = $_POST['quantity'];
	$quantity = preg_replace('#[^0-9]#i', '', $quantity); // filter everything but numbers
	if ($quantity >= 100) { $quantity = 99; }
	if ($quantity < 1) { $quantity = 1; }
	if ($quantity == "") { $quantity = 1; }
	$i = 0;
	foreach ($_SESSION["cart_array"] as $each_item) { 
		      $i++;
		      while (list($key, $value) = each($each_item)) {
				  if ($key == "item_id" && $value == $item_to_adjust) {
					  // That item is in cart already so let's adjust its quantity using array_splice()
					  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $item_to_adjust, "quantity" => $quantity)));
				  } // close if condition
		      } // close while loop
	} // close foreach loop
}
?>
<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 4 (if user wants to remove an item from cart)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST['index_to_remove']) && $_POST['index_to_remove'] != "") {
    // Access the array and run code to remove that array index

 	$key_to_remove = $_POST['index_to_remove'];
	if (count($_SESSION["cart_array"]) <= 1) {
		unset($_SESSION["cart_array"]);
	} else {
		unset($_SESSION["cart_array"]["$key_to_remove"]);
		sort($_SESSION["cart_array"]);
	}
}
?>
<?php 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//       Section 5  (render the cart for the user to view on the page)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$cartOutput = "";
$cartTotal = "";
$pp_checkout_btn = '';
$product_id_array = '';
if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
    $cartOutput = "<h2 align='center'>Your shopping cart is empty</h2>";
} else {
	
	$sql = mysql_query("INSERT INTO onlineorder (username, total, order_date, status) 
        VALUES('$loginuser','0',now(),'initiated')") or die (mysql_error());
     $piid = mysql_insert_id();
	 $sql = mysql_query("CREATE TABLE order$piid (
		 		 id int(11) NOT NULL auto_increment,
				 product varchar(24) NOT NULL,
		 		 quantity int(11) NOT NULL,
		 		 PRIMARY KEY (id),
		 		 UNIQUE KEY product(product)
		 		 )") or die (mysql_error());
				$n=$_SESSION['order_name'];
	$e=$_SESSION['order_email'];
	$s=$_SESSION['order_sex'];
	$a=$_SESSION['order_add'];
	$p=$_SESSION['order_pn'];
				 
				 $sql = mysql_query("INSERT INTO Deliverydetails(id,Name,Email,Sex,Address,Phone) 
        VALUES('$piid','$n','$e','$s','$a','$p')")or die (mysql_error());
				 
	 
	// Start PayPal Checkout Button
	$pp_checkout_btn .= '<form action="checkout.php" method="post">
    <input type="hidden" name="cmd" value="_cart">
    <input type="hidden" name="upload" value="1">
    <input type="hidden" name="business" value="you@youremail.com">';
	// Start the For Each loop
	$i = 0; 
    foreach ($_SESSION["cart_array"] as $each_item) { 
		$item_id = $each_item['item_id'];
		$sql = mysql_query("SELECT * FROM products WHERE id='$item_id' LIMIT 1");
		while ($row = mysql_fetch_array($sql)) {
			$product_name = $row["product_name"];
			$price = $row["price"];
			$details = $row["details"];
		}
		$pricetotal = $price * $each_item['quantity'];
		$cartTotal = $pricetotal + $cartTotal;
		setlocale(LC_MONETARY, "en_US");
        $pricetotal = money_format("%10.2n", $pricetotal);
		$qty=$each_item['quantity'];
		// Dynamic Checkout Btn Assembly
		$x = $i + 1;
		$sql = mysql_query("INSERT INTO order$piid (product, quantity) 
        VALUES('$product_name','$qty')")or die (mysql_error());
		
		$pp_checkout_btn .= '<input type="hidden" name="item_name_' . $x . '" value="' . $product_name . '">
        <input type="hidden" name="amount_' . $x . '" value="' . $price . '">
        <input type="hidden" name="quantity_' . $x . '" value="' . $each_item['quantity'] . '">  ';
		// Create the product array variable
		$product_id_array .= "$item_id-".$each_item['quantity'].","; 
		// Dynamic table row assembly
		$cartOutput .= "<tr>";
		$cartOutput .= '<td><a href="product.php?id=' . $item_id . '">' . $product_name . '</a><br /><img src="inventory_images/' . $item_id . '.jpg" alt="' . $product_name. '" width="40" height="52" border="1" /></td>';
		$cartOutput .= '<td>$' . $price . '</td>';
		$cartOutput .= '<td>' . $each_item['quantity'] . '</td>';
		//$cartOutput .= '<td>' . $each_item['quantity'] . '</td>';
		$cartOutput .= '<td>' . $pricetotal . '</td>';
		$cartOutput .= '</tr>';
		$i++; 
    } $sql = mysql_query("UPDATE onlineorder set total='$cartTotal' where id='$piid'");
	setlocale(LC_MONETARY, "en_US");
    $cartTotal = money_format("%10.2n", $cartTotal);
	$cartTotal = "<div style='font-size:18px; margin-top:12px;' align='right'>Cart Total : ".$cartTotal." RS</div>";
	
	require("PHPMailer/class.phpmailer.php");
include("PHPMailer/class.smtp.php");
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 1; 
$mail->SMTPSecure = 'ssl'; 
$mail->Mailer = "smtp";

$mail->Host = "smtp.gmail.com";
 $mail->Port = 465;
$mail->IsHTML(true);
$mail->SMTPAuth = true;
$mail->Username = 'hemanths1488';
$mail->Password = 'sukumaran';
$mail->From     = "hemanths1488@gmail.com";
$mail->FromName = "EcartIndia"; 
$mail->AddAddress($_SESSION['order_email']);
$mail->Subject = "Order Successfully done .OrderID:". $piid."";
$mail->Body = '<table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>Name:</strong></td>
        <td width="35%" bgcolor="#C5DFFA"><strong>'.$_SESSION['order_name'].'</strong></td>
        </tr>
        <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>EMAIL:</strong></td>
        <td width="35%" bgcolor="#C5DFFA"><strong>'. $_SESSION['order_email'].'</strong></td>
        </tr>
        <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>Sex:</strong></td>
        <td width="35%" bgcolor="#C5DFFA"><strong>'.$_SESSION['order_sex'].'</strong></td>
        </tr>
        <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>Address:</strong></td>
        <td width="75%" bgcolor="#C5DFFA"><strong>'.$_SESSION['order_add'].'</strong></td>
        </tr>
        <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>Phonenumber:</strong></td>
        <td width="75%" bgcolor="#C5DFFA"><strong>'. $_SESSION['order_pn'].'</strong></td>
        </tr>
        </table>
        
        <br/>
        <br/> <table width="100%" border="1" cellspacing="0" cellpadding="4">
      <tr>
        <td width="35%" bgcolor="#C5DFFA"><strong>Product</strong></td>
        <td width="25%" bgcolor="#C5DFFA"><strong>Unit Price</strong></td>
        <td width="25%" bgcolor="#C5DFFA"><strong>Quantity</strong></td>
        <td width="25%" bgcolor="#C5DFFA"><strong>Total</strong></td>
       </tr>'.$cartOutput.'</table><br/>'.$cartTotal.'
	   <br/>
	   <br/>
 
 Regards
 
 Admin';
 if(!$mail->Send())
{
   echo "Error sending: " . $mail->ErrorInfo;;
}

    // Finish the Paypal Checkout Btn
	$pp_checkout_btn .= '<input type="hidden" name="custom" value="' . $product_id_array . '">
	<input type="hidden" name="notify_url" value="/storescripts/my_ipn.php">
	<input type="hidden" name="return" value="https://www.yoursite.com/checkout_complete.php">
	<input type="hidden" name="rm" value="2">
	<input type="hidden" name="cbt" value="Return to The Store">
	<input type="hidden" name="cancel_return" value="https://www.yoursite.com/paypal_cancel.php">
	<input type="hidden" name="lc" value="US">
	<input type="hidden" name="currency_code" value="USD">
	<input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="Make payments with PayPal - its fast, free and secure!">
	</form>';
	
	
}
?>
'
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
			 $date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
		}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Your Cart</title>
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
        <td width="25%" bgcolor="#C5DFFA"><strong>Name:</strong></td>
        <td width="35%" bgcolor="#C5DFFA"><strong><?php echo $_SESSION['order_name'];?></strong></td>
        </tr>
        <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>EMAIL:</strong></td>
        <td width="35%" bgcolor="#C5DFFA"><strong><?php echo $_SESSION['order_email'];?></strong></td>
        </tr>
        <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>Sex:</strong></td>
        <td width="35%" bgcolor="#C5DFFA"><strong><?php echo $_SESSION['order_sex'];?></strong></td>
        </tr>
        <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>Address:</strong></td>
        <td width="75%" bgcolor="#C5DFFA"><strong><?php echo $_SESSION['order_add'];?></strong></td>
        </tr>
        <tr>
        <td width="25%" bgcolor="#C5DFFA"><strong>Phonenumber:</strong></td>
        <td width="75%" bgcolor="#C5DFFA"><strong><?php echo $_SESSION['order_pn'];?></strong></td>
        </tr>
        </table>
        
        <br/>
        <br/>
        
    <table width="100%" border="1" cellspacing="0" cellpadding="4">
      <tr>
        <td width="35%" bgcolor="#C5DFFA"><strong>Product</strong></td>
        <td width="25%" bgcolor="#C5DFFA"><strong>Unit Price</strong></td>
        <td width="25%" bgcolor="#C5DFFA"><strong>Quantity</strong></td>
        <td width="25%" bgcolor="#C5DFFA"><strong>Total</strong></td>
       </tr>
     <?php echo $cartOutput; ?>
     <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr> -->
    </table>
    <?php echo $cartTotal; ?>
    <br />
<br />
<?php //echo $pp_checkout_btn; ?>
</br>
</br>
<form>
<input type="button" value="Print this page" onClick="window.print()">
</form>
    <br />
    <br />
    </div>
   <br />
  </div>
  
</div>
</body>
</html>