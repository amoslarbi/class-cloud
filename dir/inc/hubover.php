<?php
//begin::get user data
function get_user_data($link,$apid,$rlid){
	$data = array();
	$func_num_args = func_num_args();
	$func_get_args = func_get_args();
	
	$grild = mysqli_query($link, "SELECT uid FROM appuser WHERE apid='$apid'");
	$gr = mysqli_fetch_assoc($grild);
	$uid = $gr['uid'];
	if($rlid == 1){
		if($func_num_args > 1){
			unset($func_get_args[0]);
			$fields = ''.implode(',',$func_get_args).'';
			$data = mysqli_fetch_assoc(mysqli_query($link, "SELECT $fields FROM instructor WHERE inst_id = '$uid' "));
			return $data;
		}
	}
  else if($rlid == 3){
    if($func_num_args > 1){
      unset($func_get_args[0]);
      $fields = ''.implode(',',$func_get_args).'';
      $data = mysqli_fetch_assoc(mysqli_query($link, "SELECT $fields FROM admin WHERE aid = '$uid' "));
      return $data;
    }
  }
	else{
		if($func_num_args > 1){
			unset($func_get_args[0]);
			$fields = ''.implode(',',$func_get_args).'';
			$data = mysqli_fetch_assoc(mysqli_query($link, "SELECT $fields FROM student WHERE stid = '$uid' "));
			return $data;
		}
	}

}
//end::get user data

//begin::audit trail
function action_log($link,$aid,$action,$description){
    $instq = mysqli_query($link, "INSERT INTO admin_log(`aid`,`action`,`details`,`tstamp`) VALUES('$aid','$action','$description',now())");
}
//end::audit trail

function array_is_unique($array) {
   return array_unique($array) == $array;
}

//begin::get user id
function get_userid($link, $apid){
	$q = mysqli_query($link, "SELECT uid FROM appuser WHERE apid='$apid' ");
	$qr = mysqli_fetch_assoc($q);
	return $qr['uid'];
}
//end::get user id

//begin::get subject
function get_subject($link, $sid){
	$q = mysqli_query($link, "SELECT subject, icon FROM subject WHERE sid = '$sid' ");
	$qr = mysqli_fetch_assoc($q);
	return $qr['subject'].'|'.$qr['icon'];
}
//end::get subject

//begin::get course level
function get_level($link, $lid){
	$q = mysqli_query($link, "SELECT level_name FROM level WHERE lid='$lid' ");
	$qr = mysqli_fetch_assoc($q);
	return $qr['level_name'];
}
//end::get course level

//begin::get user role
function get_user_role($link, $apid){
	$q = mysqli_query($link, "SELECT rlid FROM appuser WHERE apid='$apid' ");
	$qr = mysqli_fetch_assoc($q);
	return $qr['rlid'];
}
//end::get user role

//begin::check audio before upload (eg: size)
function checkaudio($link,$imagename,$audiosize){
    $allow = array('mp3','wav');
    $bits = explode('.',$imagename);
    $file_extn = strtolower(end($bits));
    if(in_array($file_extn, $allow) == false){
        return '0';
    }
    else if($audiosize > 10000000 || $audiosize == 0){
        return '2';
    }
    else{
        return '1';
    }
}
//end::check audio before upload (eg: size)

//begin::check video before upload (eg: size)
function checkvideo($link,$imagename,$videosize){
    $allow = array('mp4','wmv');
    $bits = explode('.',$imagename);
    $file_extn = strtolower(end($bits));
    if(in_array($file_extn, $allow) == false){
        return '0';
    }
    else if($videosize > 50000000 || $videosize == 0){
        return '2';
    }
    else{
        return '1';
    }
}
//end::check video before upload (eg: size)

//begin::check game before upload (eg: size)
function checkgame($link,$imagename,$gamesize){
    $allow = array('fla','swf');
    $bits = explode('.',$imagename);
    $file_extn = strtolower(end($bits));
    if(in_array($file_extn, $allow) == false){
        return '0';
    }
    else if($gamesize > 10000000 || $gamesize == 0){
        return '2';
    }
    else{
        return '1';
    }
}
//end::check game before upload (eg: size)

//begin::check image before upload (eg: size)
function checkimage($link,$imagename,$imgsize){
    $allow = array('jpg','jpeg','png','svg');
    $bits = explode('.',$imagename);
    $file_extn = strtolower(end($bits));
    if(in_array($file_extn, $allow) == false){
        return '0';
    }
    else if($imgsize > 2500000 || $imgsize == 0){
        return '2';
    }
    else{
        return '1';
    }
}
//end::check image before upload (eg: size)

//begin::upload course material
function uploadmaterial($link,$imagename,$imagetmp){
    $allow = array('jpg','jpeg','png','svg','fla','swf','mp4','wmv','mp3','wav');
    $bits = explode('.',$imagename);
    $file_extn = strtolower(end($bits));
    $filename = 'cc'.substr(md5(time().rand(10000,99999)), 0, 15).'.'.$file_extn;
    $fullpath = 'assets/materials/'.$filename;
        $move = move_uploaded_file($imagetmp ,$fullpath) ;
        if(!$move){
            return '0';
        }
        return $filename;
}
//end::upload course material

//begin::upload image for course material
function uploadimage($link,$imagename,$imagetmp){
    $allow = array('jpg','jpeg','png','svg');
    $bits = explode('.',$imagename);
    $file_extn = strtolower(end($bits));
    $filename = 'cc'.substr(md5(time().rand(10000,99999)), 0, 15).'.'.$file_extn;
    $fullpath = 'assets/images/'.$filename;
        $move = move_uploaded_file($imagetmp ,$fullpath) ;
        if(!$move){
            return '0';
        }
        return $filename;
}
//end::upload image for course material

//begin::grade student
function grade_student($link,$stid,$slid){
    $gq = mysqli_query($link, "SELECT qid FROM `queries` WHERE qqid IN (SELECT qqid FROM `student_queries` WHERE stid='$stid' AND slcid IN (SELECT slcid FROM subject_level_curriculum WHERE slid='$slid') GROUP BY slcid) GROUP BY qid");
    $gnum = mysqli_num_rows($gq);
    if($gnum == 0){
      return 0;
    }

    $tqz = 0;
    $tans = 0;
    while ($gr = mysqli_fetch_array($gq, MYSQLI_ASSOC)) {
      $qid = $gr['qid'];
      
      $qz = mysqli_query($link, "SELECT qqid,answer,slcid FROM student_queries WHERE qqid IN (SELECT qqid FROM queries WHERE qid='$qid') AND stid='$stid' ");

      $qznum = mysqli_num_rows($qz);
        $mq = mysqli_query($link, "SELECT * FROM questions WHERE qid='$qid' ");
        $mr = mysqli_fetch_assoc($mq);
        $qtnum = $mr['quizqnum'];
        $countt = 0;


        while($az = mysqli_fetch_array($qz, MYSQLI_ASSOC)){
          $stanswer = $az['answer'];
          $qqidz = $az['qqid'];
          $slcidx = $az['slcid'];

          $qd = mysqli_query($link, "SELECT answer FROM queries WHERE qqid='$qqidz' ");
          $ar = mysqli_fetch_assoc($qd);
          if($stanswer == $ar['answer']){
            $countt++;
          }
        }

        $scz = ($countt/$qtnum)*100;
        $cq = mysqli_query($link, "SELECT stlid FROM student_lessons WHERE stid='$stid' AND slcid='$slcidx' ");
        $cqnum = mysqli_num_rows($cq);
        if($cqnum == 0){
          $inq = mysqli_query($link, "INSERT INTO student_lessons(stid,slcid,score,created) VALUES('$stid','$slcidx','$scz', now())");
        }
        else{
          $cqr = mysqli_fetch_assoc($cq);
          $stlid = $cqr['stlid'];
          $up = mysqli_query($link, "UPDATE student_lessons SET score='$scz',created=now() WHERE stid='$stid' AND slcid='$slcidx' "); 
        }

      $tqz += $qtnum;
      $tans += $countt;

    }
    $scorez = ($tans/$tqz)*100;

    $grq = mysqli_query($link, "SELECT grade FROM gradebook WHERE (maxscore > '$scorez' OR maxscore = '$scorez') AND (minscore < '$scorez' OR minscore = '$scorez')");
    $gdr = mysqli_fetch_assoc($grq);
    return '<span>'.$gdr['grade'].'</span>
            <span style="font-size: 12px;">'.round($scorez,1).'%</span>';
  }

  function grade($link, $scorez){
    $grq = mysqli_query($link, "SELECT grade FROM gradebook WHERE (maxscore > '$scorez' OR maxscore = '$scorez') AND (minscore < '$scorez' OR minscore = '$scorez')");
    $gdr = mysqli_fetch_assoc($grq);
    return $gdr['grade'];
  }
//end::grade student
?>