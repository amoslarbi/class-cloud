<?php
//begin::database connection
$host = 'mysql';  // The service name from docker-compose.yml
$db   = 'my_database';
$user = 'user';
$pass = 'password';

$link = new mysqli($host, $user, $pass, $db);

if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}
//end::database connection
?>