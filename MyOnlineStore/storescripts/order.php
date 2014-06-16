<?php
@author Hemanth 
// Written by Adam Khoury January 01, 2011
// http://www.youtube.com/view_play_list?p=442E340A42191003
// Connect to the MySQL database  
require "connect_to_mysql.php";  

$sqlCommand = "CREATE TABLE order( id int(11) NOT NULL auto_increment,username varchar(24) NOT NULL, total int(24) NOT NULL, orderdate date NOT NULL,status varchar(24) NOT NULL,PRIMARY KEY (id), UNIQUE KEY id(id) )";
if (mysql_query($sqlCommand)){ 
    echo "Your user table has been created successfully!"; 
} else { 
    echo "CRITICAL ERROR: admin table has not been created.";
}
?>