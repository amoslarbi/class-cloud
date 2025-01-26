<?php
$link = mysqli_connect('localhost','root', '', 'ccdb');
if (!$link) {
    die(header('location: 505'));
}
?>