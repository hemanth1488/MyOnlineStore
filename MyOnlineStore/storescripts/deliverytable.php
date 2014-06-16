<?php
@author Hemanth 
// Written by Adam Khoury January 01, 2011
// http://www.youtube.com/view_play_list?p=442E340A42191003
// Connect to the MySQL database  
require "connect_to_mysql.php";  

$sqlCommand = "CREATE TABLE Deliverydetails(
		 		 id int(11) NOT NULL auto_increment,
				 Name varchar(24) NOT NULL,
		 		 Email varchar(24) NOT NULL,
		 		 Sex varchar(24) NOT NULL,
				 Address varchar(256) NOT NULL,
				 Phone varchar(24),
		 		 PRIMARY KEY (id)
		 				 		 )  ";
if (mysql_query($sqlCommand)){ 
    echo "Your user table has been created successfully!"; 
} else { 
    echo "CRITICAL ERROR: delivery table has not been created.";
}
?>