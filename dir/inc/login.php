<?php
require 'clean.php';
function user_login($link, $email, $password){
	$loge = 0;
	$email = clean($link,$email);
    $password = clean($link,$password);

    if(empty($email) == true){
        $loge++;
    }
    if(empty($password) == true){
        $loge++;
    }

    if($loge == 0){
        $password = md5($password);
        $checkq = mysqli_query($link, "SELECT apid FROM appuser WHERE email='$email' AND password='$password' ");
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
?>