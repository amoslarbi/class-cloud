<?php
session_start(); //replicate active session
unset($_SESSION['uid']); //unset user id
session_destroy(); //destroy session
header ('location: ../../home'); //redirect user to login page
?>