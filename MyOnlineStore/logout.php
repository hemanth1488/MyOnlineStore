<?php

session_start();
session_unset();
    session_destroy();
    $_SESSION = array();
    session_start();

header("location: main.php"); 
?>