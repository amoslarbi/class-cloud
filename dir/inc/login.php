<?php
require 'clean.php';
//begin::user login
function user_login($link, $email, $password){
	$loge = 0;
	$email = clean($link,$email); //handle sql injection
    $password = clean($link,$password); //handle sql injection

    if(empty($email) == true){ //check if email input field is empty
        $loge++;
    }
    if(empty($password) == true){ //check if password input field is empty
        $loge++;
    }

    if($loge == 0){ //check if email and password input fields are empty
        $password = md5($password); //decrypt password
        $checkq = mysqli_query($link, "SELECT apid FROM appuser WHERE email = '$email' AND password = '$password' ");
        $cknum = mysqli_num_rows($checkq);
        if($cknum == 1){
            $getx = mysqli_fetch_assoc($checkq);
            $uid = $getx['apid'];
                return $uid;
        }
        else{
        	return '0';
        }
    }
    else{
    	return '0';
    }
}
//end::user login
?>