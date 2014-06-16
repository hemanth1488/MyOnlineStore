<?php 
session_start();
if (isset($_SESSION["loginuser"])) {
    header("location: index.php"); 
    exit();
}
$errors="";
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
		$errors="please provide the correct username and password";
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
       <form id="form1" name="form1" method="post" action="main.php">
        User Name:<br />
          <input name="username" type="text" id="username" size="40" />
        <br /><br />
        Password:<br />
       <input name="password" type="password" id="password" size="40" />
       <br />
       <br />
       <br />
       
         <input type="submit" name="button" id="button" value="Log In" />
       
      </form>
      <h4><font color="#FF0000"><?php echo $errors ?></font></h4>
      <P><a href="registration.php" >Register new user</a></P></div>
      <P><a href="forgotpwd.php" >Forgot password</a></P></div></tr></table>
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