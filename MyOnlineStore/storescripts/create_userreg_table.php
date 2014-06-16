<?php
@author Hemanth 
 
require "connect_to_mysql.php";  

$sqlCommand = "CREATE TABLE registeruser (
		 		 id int(11) NOT NULL auto_increment,
				 Name varchar(255) NOT NULL,
		 		 Email varchar(16) NOT NULL,
				 Sex  varchar(16) NOT NULL,
				 Address text NOT NULL,
				 Username varchar(16) NOT NULL,
				 Password varchar(16) NOT NULL,
		 		 date_added date NOT NULL,
		 		 PRIMARY KEY (id),
		 		 UNIQUE KEY Username(Username)
		 		 ) ";
if (mysql_query($sqlCommand)){ 
    echo "Your user registration table has been created successfully!"; 
} else { 
    echo "CRITICAL ERROR: products table has not been created.";
}
?>