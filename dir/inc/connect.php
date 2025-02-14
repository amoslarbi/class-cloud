<?php
//begin::database connection
$link = mysqli_connect('localhost','root', '', 'ccdb');
if (!$link) {
    die(header('location: 505'));
}
//end::database connection
?>