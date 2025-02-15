<?php
//begin::database connection
$host = 'mysql';  // The service name from docker-compose.yml
$db   = 'class_cloud';
$user = 'user';
$pass = 'password';

$link = new mysqli($host, $user, $pass, $db);

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}
//end::database connection
?>