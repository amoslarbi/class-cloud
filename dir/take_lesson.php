<?php
session_start();
require 'inc/over.php';

if(isset($_SESSION['apid']) == true){
  $apid = $_SESSION['apid'];
  $rlid = get_user_role($link, $apid);

  $admindata = get_user_data($link,$apid,$rlid, 'fname', 'lname','mname','avatar');
  $adminnm = $admindata['fname'].' '.$admindata['mname'].' '.$admindata['lname'];

  $uidx = get_userid($link, $apid);

  if(empty($admindata['avatar'] == true)){
      $avatar = 'default.png';
  }
  else{
      $avatar = $admindata['avatar'];
  }

  $adfname = $admindata['fname'];


}
else{
  header('location: login');
}





$slcidx='';
$error=0;
$errormesg=$echoscript='';
$levq = mysqli_query($link, "SELECT * FROM level WHERE 1 ORDER BY level_name ASC ");
$levels = '';
while ($lr = mysqli_fetch_array($levq, MYSQLI_ASSOC)) {
  $levels .= '<option value="'.$lr['lid'].'">'.$lr['level_name'].'</option>';
}


$stjq = mysqli_query($link, "SELECT * FROM subject WHERE 1 ORDER BY subject ASC ");
$subjectz = '';
while ($sbjr = mysqli_fetch_array($stjq, MYSQLI_ASSOC)) {
  $subjectz .= '<option value="'.$sbjr['sid'].'">'.$sbjr['subject'].'</option>';
}

$subcx = '';
if($rlid == 3){
    $menu = '<li class="sidebar-menu-item active">
                <a class="sidebar-menu-button" href="hub">
                  <i class="sidebar-menu-icon material-icons">import_contacts</i> Course Manager
                </a>
              </li>';

    $dataresult = '<div class="container-fluid">
                    <div class="center" style="margin-top: 50px;color: #999;">
                      <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                      <p>You can not access this page</p>
                      <a href="hub"><button type="button" class="btn btn-primary">
                        <i class="material-icons">home</i>
                        <span class="icon-text">Return Home</span>
                      </button></a>
                    </div>
                  </div>';
}
else if($rlid == 1){
    $menu = '<li class="sidebar-menu-item active">
              <a class="sidebar-menu-button" href="hub">
                <i class="sidebar-menu-icon material-icons">people</i> Your Students
              </a>
            </li>';

    $dataresult = '<div class="container-fluid">
                    <div class="center" style="margin-top: 50px;color: #999;">
                      <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                      <p>You can not access this page</p>
                      <a href="hub"><button type="button" class="btn btn-primary">
                        <i class="material-icons">home</i>
                        <span class="icon-text">Return Home</span>
                      </button></a>
                    </div>
                  </div>';
}
else{

  if(isset($_POST['quizout'])){
    $slcidi = clean($link, $_POST['slcidx']);
      $index = array();
      $index = array_keys($_POST['qans']);
      $qans = count($index);
      for ($i=0; $i <= $qans; $i++) {
        if(empty($index[$i]) == false){
          $qqidx = $index[$i];
          $answer = clean($link, $_POST['qans'][$qqidx]);

          $qq = mysqli_query($link, "INSERT INTO student_queries(`stid`,`qqid`,`answer`,`slcid`,`tstamp`) VALUES('$uidx','$qqidx','$answer','$slcidi',now()) ");
        }
      }
    
  }

  $gradez = '';

  $menu = '<li class="sidebar-menu-item">
              <a class="sidebar-menu-button" href="hub">
                <i class="sidebar-menu-icon material-icons">school</i> My Courses
              </a>
            </li>
            <li class="sidebar-menu-item">
              <a class="sidebar-menu-button" href="view_subjects">
                <i class="sidebar-menu-icon material-icons">class</i> View Subjects
              </a>
            </li>
            <li class="sidebar-menu-item active">
              <a class="sidebar-menu-button" href="take_lesson">
                <i class="sidebar-menu-icon material-icons">import_contacts</i> Take Subject
              </a>
            </li>
            <li class="sidebar-menu-item">
              <a class="sidebar-menu-button" href="take_quiz">
                <i class="sidebar-menu-icon material-icons">dvr</i> Take a Quiz
              </a>
            </li>';


  if(isset($_GET['lesson']) && empty($_GET['lesson']) == false && isset($_GET['number']) && empty($_GET['number']) == false){

    $slcidx = clean($link, $_GET['lesson']);
    $nextl = clean($link, $_GET['number']);

        $ensubs = '';
        $getstsub = mysqli_query($link, "SELECT * FROM student_subject_level WHERE stid='$uidx' AND slid=(SELECT slid FROM subject_level_curriculum WHERE slcid='$slcidx') ");

        $gsubnum =mysqli_num_rows($getstsub);
        if($gsubnum == 0){
          $ensubs = '<div class="center" style="margin-top: 50px;color: #999;">
                        <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                        <p>You have not enrolled for this subject</p>
                        <a href="hub"><button type="button" class="btn btn-primary">
                          <i class="material-icons">home</i>
                          <span class="icon-text">Go Home</span>
                        </button></a>
                      </div>';
        }
        else{

            $gr = mysqli_fetch_assoc($getstsub);
            $slidx = $gr['slid'];
            $suballq = mysqli_query($link, "SELECT sid,lid FROM subject_level WHERE slid='$slidx' ");
            $subr = mysqli_fetch_assoc($suballq);
            $sidz = $subr['sid'];
            $lidz = $subr['lid'];

            $levelnm = get_level($link, $lidz);
            $subjx = get_subject($link, $sidz);
            $subjx = explode('|', $subjx);
            $subjectnm = $subjx[0];
            $subicon = $subjx[1];
            $stprog = mysqli_query($link, "SELECT progress FROM student_progress WHERE stid='$uidx' AND slcid IN (SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx') ");
            $stpnum = mysqli_num_rows($stprog);
            if($stpnum == 0){
              $progressx = 0;
              $lessonxnum = 1;

              $scqx = mysqli_query($link, "SELECT * FROM subject_level_curriculum WHERE slid='$slidx' ");
              $sqnumx = mysqli_num_rows($scqx);

                if($nextl == 'next'){
                  $scqx = mysqli_query($link, "SELECT * FROM questions WHERE qid IN (SELECT mid FROM lesson_structure WHERE mtype='q' AND slcid='$slcidx' ) ");
                    $scnum = mysqli_num_rows($scqx);
                    if($scnum > 0){
                      while ($qwr = mysqli_fetch_array($scqx, MYSQLI_ASSOC)) {
                        $qidz = $qwr['qid'];
                        $qusnum = $qwr['quizqnum'];

                        $ssq = mysqli_query($link, "SELECT * FROM student_queries WHERE qqid IN (SELECT qqid FROM queries WHERE qid='$qidz') AND slcid='$slcidx' AND stid='$uidx' ");
                        $ssqnum = mysqli_num_rows($ssq);
                        if($ssqnum == 0){
                          $echoscript = '<script type="text/javascript">
                                                swal({
                                                    title: "Error",
                                                    text: "You have to answer all the quizes in this lesson to start the next lesson",
                                                    type: "error",
                                                    html: true,
                                                    showCancelButton: false,
                                                    confirmButtonText: "Okay!",
                                                    closeOnConfirm: true
                                                });
                                        </script>';
                        }
                        else{

                          $progressx = (($lessonxnum)/$sqnumx)*100;
                          $pchq = mysqli_query($link, "SELECT spid FROM student_progress WHERE stid='$uidx' AND slcid IN (SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx')  ");
                          $pchnum = mysqli_num_rows($pchq);

                          $slciday = array();
                          $gtscq = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' ");
                          while ($gsc = mysqli_fetch_array($gtscq, MYSQLI_ASSOC)) {
                            array_push($slciday, $gsc['slcid']);
                          }
                          $slcount = count($slciday);
                          for ($i=0; $i < $slcount; $i++) { 

                            if($slciday[$i] == $slcidx){
                              if($i != ($slcount-1)){
                                $slcidx = $slciday[$i+1];
                                $i = $slcount;
                              }
                            }
                            else if($slciday[$slcount-1] == $slcidx){
                              $progressx = 100;
                            }

                          }

                          if($pchnum == 0){
                            $stprog = mysqli_query($link, "INSERT INTO student_progress (`stid`,`slcid`,`progress`,`tstamp`) VALUES('$uidx','$slcidx','$progressx',now())");
                          }
                          else{
                            $stprog = mysqli_query($link, "UPDATE student_progress SET progress='$progressx' WHERE stid='$uidx' AND slcid='$slcidx' ");
                          }
                        }
                      }
                    }
                    else{
                      $progressx = (($lessonxnum)/$sqnumx)*100;
                      $pchq = mysqli_query($link, "SELECT spid FROM student_progress WHERE stid='$uidx' AND slcid IN (SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx')  ");
                      $pchnum = mysqli_num_rows($pchq);

                      $slciday = array();
                      $gtscq = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' ");
                      while ($gsc = mysqli_fetch_array($gtscq, MYSQLI_ASSOC)) {
                        array_push($slciday, $gsc['slcid']);
                      }
                      $slcount = count($slciday);
                      for ($i=0; $i < $slcount; $i++) { 
                        if($slciday[$i] == $slcidx){
                          if($i != ($slcount-1)){
                            $slcidx = $slciday[$i+1];
                          }
                        }
                        else if($slciday[$slcount-1] == $slcidx){
                          $progressx = 100;
                        }

                      }


                      if($pchnum == 0){
                        $stprog = mysqli_query($link, "INSERT INTO student_progress (`stid`,`slcid`,`progress`,`tstamp`) VALUES('$uidx','$slcidx','$progressx',now())");
                      }
                      else{
                        $stprog = mysqli_query($link, "UPDATE student_progress SET progress='$progressx' WHERE stid='$uidx' AND slcid='$slcidx' ");
                      }
                    }
                }
            }
            else{
              $spr = mysqli_fetch_assoc($stprog);
              $progressx = $spr['progress'];
              
              $scqx = mysqli_query($link, "SELECT * FROM subject_level_curriculum WHERE slid='$slidx' ");
              $sqnumx = mysqli_num_rows($scqx);
              $lessonxnum = ($progressx/100)*$sqnumx;

                if($nextl == 'next'){
                  $scqx = mysqli_query($link, "SELECT * FROM questions WHERE qid IN (SELECT mid FROM lesson_structure WHERE mtype='q' AND slcid='$slcidx' ) ");
                    $scnum = mysqli_num_rows($scqx);
                    if($scnum > 0){
                      while ($qwr = mysqli_fetch_array($scqx, MYSQLI_ASSOC)) {
                        $qidz = $qwr['qid'];
                        $qusnum = $qwr['quizqnum'];

                        $ssq = mysqli_query($link, "SELECT * FROM student_queries WHERE qqid IN (SELECT qqid FROM queries WHERE qid='$qidz') AND slcid='$slcidx' AND stid='$uidx' ");
                        $ssqnum = mysqli_num_rows($ssq);
                        if($ssqnum == 0 ){
                          $echoscript = '<script type="text/javascript">
                                                swal({
                                                    title: "Error",
                                                    text: "You have to answer all the quizes in this lesson to start the next lesson",
                                                    type: "error",
                                                    html: true,
                                                    showCancelButton: false,
                                                    confirmButtonText: "Okay!",
                                                    closeOnConfirm: true
                                                });
                                        </script>';
                        }
                        else{
                          
                          $progressx = (($lessonxnum)/$sqnumx)*100;
                          $pchq = mysqli_query($link, "SELECT spid FROM student_progress WHERE stid='$uidx' AND slcid IN (SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx')  ");
                          $pchnum = mysqli_num_rows($pchq);

                          $slciday = array();
                          $gtscq = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' ");
                          while ($gsc = mysqli_fetch_array($gtscq, MYSQLI_ASSOC)) {
                            array_push($slciday, $gsc['slcid']);
                          }
                          $slcount = count($slciday);
                          for ($i=0; $i < $slcount; $i++) { 
                            if($slciday[$i] == $slcidx){
                              if($i != ($slcount-1)){
                                $slcidx = $slciday[$i+1];
                                $i = $slcount;
                              }
                            }
                            else if($slciday[$slcount-1] == $slcidx){
                              $progressx = 100;
                            }

                          }



                          if($pchnum == 0){
                            $stprog = mysqli_query($link, "INSERT INTO student_progress (`stid`,`slcid`,`progress`,`tstamp`) VALUES('$uidx','$slcidx','$progressx',now())");
                          }
                          else{
                            $stprog = mysqli_query($link, "UPDATE student_progress SET progress='$progressx' WHERE stid='$uidx' AND slcid='$slcidx' ");
                          }
                        }
                      }
                    }
                    else{
                          $progressx = (($lessonxnum)/$sqnumx)*100;
                          $pchq = mysqli_query($link, "SELECT spid FROM student_progress WHERE stid='$uidx' AND slcid IN (SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx')  ");
                          $pchnum = mysqli_num_rows($pchq);

                          $slciday = array();
                          $gtscq = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' ");
                          while ($gsc = mysqli_fetch_array($gtscq, MYSQLI_ASSOC)) {
                            array_push($slciday, $gsc['slcid']);
                          }
                          $slcount = count($slciday);
                          for ($i=0; $i < $slcount; $i++) { 
                            if($slciday[$i] == $slcidx){
                              if($i != ($slcount-1)){
                                $slcidx = $slciday[$i+1];
                              }
                            }
                            else if($slciday[$slcount-1] == $slcidx){
                              $progressx = 100;
                            }

                          }

                          if($pchnum == 0){
                            $stprog = mysqli_query($link, "INSERT INTO student_progress (`stid`,`slcid`,`progress`,`tstamp`) VALUES('$uidx','$slcidx','$progressx',now())");
                          }
                          else{
                            $stprog = mysqli_query($link, "UPDATE student_progress SET progress='$progressx' WHERE stid='$uidx' AND slcid='$slcidx' ");
                          }
                    }
                }


              
            }

            $scq = mysqli_query($link, "SELECT * FROM subject_level_curriculum WHERE slcid='$slcidx'");
            $sqnum = mysqli_num_rows($scq);
            if($sqnum == 0){
              $lessontitle = '';
              $lessonx = '<div class="center" style="margin-top: 50px;color: #999;">
                        <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                        <p>This subject has no lessons yet...</p>
                        <a href="hub"><button type="button" class="btn btn-primary">
                          <i class="material-icons">class</i>
                          <span class="icon-text">Go Home</span>
                        </button></a>
                      </div>';
            }
            else{
              $lsr = mysqli_fetch_assoc($scq);
              $lessontitle = $lsr['lesson_name'];

              if($nextl != 'next'){
                $lsq = mysqli_query($link, "SELECT * FROM lesson_structure WHERE slcid='$slcidx' AND mnum='$nextl' ");
              }
              else{
                $lsq = mysqli_query($link, "SELECT * FROM lesson_structure WHERE slcid='$slcidx' ORDER BY mnum ASC LIMIT 1 ");
              }
              $lssnum = mysqli_num_rows($lsq);
              if($lssnum == 0){
                $lessonx = '<div class="center" style="margin-top: 50px;color: #999;">
                              <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                              <p>This lessons has no materials yet...</p>
                              <a href="hub"><button type="button" class="btn btn-primary">
                                <i class="material-icons">class</i>
                                <span class="icon-text">Go Home</span>
                              </button></a>
                            </div>';
              }
              else{
                $lsnq = mysqli_query($link, "SELECT mnum FROM lesson_structure WHERE slcid='$slcidx' ");
                $lnssnum = mysqli_num_rows($lsnq);
                $lnumarray = array();
                $counterz = 0;
                while ($lns = mysqli_fetch_array($lsnq, MYSQLI_ASSOC)) {
                  $lnumarray[$counterz] = $lns['mnum'];
                  $counterz++;
                }

                

                $lssize = count($lnumarray);
                if($lssize == 1){
                  $prevbutt = '';
                  $slciday = array();
                  $gtscq = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' ");
                  while ($gsc = mysqli_fetch_array($gtscq, MYSQLI_ASSOC)) {
                    array_push($slciday, $gsc['slcid']);
                  }
                  for ($i=0; $i < count($slciday); $i++) { 
                    if($slciday[$i] == $slcidx){
                      if($i != 0){
                        $pslcidx = $slciday[$i-1];
                        $prevbutt = '<a href="take_lesson?lesson='.$pslcidx.'" style="float:left;">
                                        <button type="button" class="btn btn-primary">
                                          <i class="material-icons">arrow_back</i>
                                          <span class="icon-text">Previous Lesson</span>
                                        </button>
                                      </a>';
                      }
                    }
                  }

                  $scqx = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' ORDER BY lesson_number DESC LIMIT 1 ");
                  $slr = mysqli_fetch_assoc($scqx);
                  if($slr['slcid'] == $slcidx){
                    $nextlesson = 'next';
                    $buttonx = $prevbutt.'<a href="take_lesson?lesson='.$slcidx.'&number='.$nextlesson.'" style="float:right;">
                                  <button type="button" class="btn btn-white">
                                    <span class="icon-text">Finish</span>
                                    <i class="material-icons">arrow_forward</i>
                                  </button>
                                </a>';
                  }
                  else{
                    $nextlesson = 'next';
                    $buttonx = $prevbutt.'<a href="take_lesson?lesson='.$slcidx.'&&number='.$nextlesson.'" style="float:right;">
                                  <button type="button" class="btn btn-primary">
                                    <span class="icon-text">Next</span>
                                    <i class="material-icons">arrow_forward</i>
                                  </button>
                                </a>';
                  }
                  
                }
                else{
                  if($nextl != 'next'){
                    for ($i=0; $i < $lssize; $i++) { 
                      if($lnumarray[$i] == $nextl){
                        $prv = $i-1;
                        $nxt = $i+1;

                        if($i == 0){

                          $slciday = array();
                          $gtscq = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' ");
                          while ($gsc = mysqli_fetch_array($gtscq, MYSQLI_ASSOC)) {
                            array_push($slciday, $gsc['slcid']);
                          }
                          for ($i=0; $i < count($slciday); $i++) { 
                            if($slciday[$i] == $slcidx){
                              if($i != 0){
                                $pslcidx = $slciday[$i-1];
                                $prevbutt = '<a href="take_lesson?lesson='.$pslcidx.'" style="float:left;">
                                                <button type="button" class="btn btn-primary">
                                                  <i class="material-icons">arrow_back</i>
                                                  <span class="icon-text">Previous Lesson</span>
                                                </button>
                                              </a>';
                              }
                              else{
                                $prevlesson = 0;
                                $prevbutt = '';
                              }
                            }
                          }
                          
                        }
                        else{
                          $prevlesson = $lnumarray[$prv];
                          $prevbutt = '<a href="take_lesson?lesson='.$slcidx.'&number='.$prevlesson.'" style="float:left;">
                                        <button type="button" class="btn btn-primary">
                                          <i class="material-icons">arrow_back</i>
                                          <span class="icon-text">Previous</span>
                                        </button>
                                      </a>';
                        }

                        $scqx = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' ORDER BY lesson_number DESC LIMIT 1 ");
                        $slr = mysqli_fetch_assoc($scqx);
                        if($slr['slcid'] == $slcidx){
                          $nextlesson = 'next';
                          $nextbutt = '<a href="take_lesson?lesson='.$slcidx.'&number='.$nextlesson.'" style="float:right;">
                                        <button type="button" class="btn btn-white">
                                          <span class="icon-text">Finish</span>
                                          <i class="material-icons">arrow_forward</i>
                                        </button>
                                      </a>';
                        }
                        else{
                          if($lssize > $nxt){
                            $nextlesson = $lnumarray[$nxt];
                          }
                          else{
                            $nextlesson = 'next';

                          }

                          
                          $nextbutt = '<a href="take_lesson?lesson='.$slcidx.'&number='.$nextlesson.'" style="float:right;">
                                          <button type="button" class="btn btn-primary">
                                            <span class="icon-text">Next</span>
                                            <i class="material-icons">arrow_forward</i>
                                          </button>
                                        </a>';
                        }
                        

                        
                      }
                    }

                    $buttonx = $prevbutt.$nextbutt;
                  }
                  else{
                    $prevlesson = '';
                    $slciday = array();
                    $gtscq = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' ");
                    while ($gsc = mysqli_fetch_array($gtscq, MYSQLI_ASSOC)) {
                      array_push($slciday, $gsc['slcid']);
                    }
                    for ($i=0; $i < count($slciday); $i++) { 
                      if($slciday[$i] == $slcidx){
                        if($i != 0){
                          $pslcidx = $slciday[$i-1];
                          $prevlesson = '<a href="take_lesson?lesson='.$pslcidx.'" style="float:left;">
                                            <button type="button" class="btn btn-primary">
                                              <i class="material-icons">arrow_back</i>
                                              <span class="icon-text">Previous Lesson</span>
                                            </button>
                                          </a>';
                        }
                      }
                    }

                    $nextlesson = $lnumarray[1];
                    $buttonx = $prevlesson.'<a href="take_lesson?lesson='.$slcidx.'&number='.$nextlesson.'" style="float:right;">
                                  <button type="button" class="btn btn-primary">
                                    <span class="icon-text">Next</span>
                                    <i class="material-icons">arrow_forward</i>
                                  </button>
                                </a>';
                  }
                    
                }

                $lzr = mysqli_fetch_assoc($lsq);
                $lmid = $lzr['mid'];
                $lmtype = $lzr['mtype'];

                if($lmtype == 'd'){
                  $mq = mysqli_query($link, "SELECT * FROM document WHERE dcid='$lmid' ");
                  $mr = mysqli_fetch_assoc($mq);
                  $lessonx = '<div class="card col-lg-12" style="margin-top:20px;">
                              <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                              <hr>
                              <p style="margin-bottom: 5px;">'.$mr['text'].'</p>
                            </div>
                            <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;float: left;width: 100%;">
                              <p>
                                '.$buttonx.'
                              </p>
                            </div>';
                }
                else if($lmtype == 'a'){
                  $mq = mysqli_query($link, "SELECT * FROM videonaudio WHERE vaid='$lmid' ");
                  $mr = mysqli_fetch_assoc($mq);
                  $lessonx = '<div class="card col-lg-12" style="margin-top:20px;">
                              <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                              <hr>
                              <p>'.$mr['description'].'</p>
                              <nav class="center">
                                <audio controls style="width: 100%;">
                                  <source src="assets/materials/'.$mr['path'].'" type="audio/mpeg">
                                </audio>
                              </nav>
                            </div>
                            <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;float: left;width: 100%;">
                              <p>
                                '.$buttonx.'
                              </p>
                            </div>';
                }
                else if($lmtype == 'v'){
                  $mq = mysqli_query($link, "SELECT * FROM videonaudio WHERE vaid='$lmid' ");
                  $mr = mysqli_fetch_assoc($mq);
                  $lessonx = '<div class="card col-lg-12" style="margin-top:20px;">
                              <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                              <hr>
                              <p style="margin-bottom: 5px;">'.$mr['description'].'</p>
                              <nav class="center">
                                <video style="width:70%" controls>
                                  <source src="assets/materials/'.$mr['path'].'" type="video/mp4">
                                </video>
                              </nav>
                            </div>
                            <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;float: left;width: 100%;">
                              <p>
                                '.$buttonx.'
                              </p>
                            </div>';
                }
                else if($lmtype == 'i'){
                  $mq = mysqli_query($link, "SELECT * FROM image WHERE imid='$lmid' ");
                  $mr = mysqli_fetch_assoc($mq);
                  $lessonx = '<div class="card col-lg-12" style="margin-top:20px;">
                              <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                              <hr>
                              <p style="margin-bottom: 5px;">'.$mr['description'].'</p>
                              <nav class="center">
                                <img  src="assets/materials/'.$mr['path'].'" style="width:50%;background-size:100%;">
                              </nav>
                            </div>
                            <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;float: left;width: 100%;">
                              <p>
                                '.$buttonx.'
                              </p>
                            </div>';
                }
                else if($lmtype == 'g'){
                  $mq = mysqli_query($link, "SELECT * FROM games WHERE gid='$lmid' ");
                  $mr = mysqli_fetch_assoc($mq);
                  $lessonx = '<div class="card col-lg-12" style="margin-top:20px;">
                              <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                              <hr>
                              <p style="margin-bottom: 5px;">'.$mr['description'].'</p>
                              <nav class="center">
                                <object  data="assets/materials/'.$mr['path'].'" width="500" height="200"></object>
                              </nav>
                            </div>
                            <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;float: left;width: 100%;">
                              <p>
                                '.$buttonx.'
                              </p>
                            </div>';
                }
                else if($lmtype == 'q'){
                  $qz = mysqli_query($link, "SELECT qqid,answer FROM student_queries WHERE qqid IN (SELECT qqid FROM queries WHERE qid='$lmid' ) AND stid='$uidx' ");

                  $qznum = mysqli_num_rows($qz);
                  if($qznum > 0){
                    $mq = mysqli_query($link, "SELECT * FROM questions WHERE qid='$lmid' ");
                    $mr = mysqli_fetch_assoc($mq);
                    $qtnum = $mr['quizqnum'];
                    $countt = 0;


                    while($az = mysqli_fetch_array($qz, MYSQLI_ASSOC)){
                      $stanswer = $az['answer'];
                      $qqidz = $az['qqid'];

                      $qd = mysqli_query($link, "SELECT answer FROM queries WHERE qqid='$qqidz' ");
                      $ar = mysqli_fetch_assoc($qd);
                      if($stanswer == $ar['answer']){
                        $countt++;
                      }
                    }

                    $lessonx = '<div class="card col-lg-12" style="margin-top:20px;float:left;">
                                  <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                                  <hr>
                                  <nav class="center">
                                    <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">bubble_chart</i>
                                    <p>You have completed this quiz already</p>
                                    <p style="color:green;">Score: '.$countt.'/'.$qtnum.'</p>
                                    <p>
                                      <a href="take_quiz?quiz='.$lmid.'"><button type="button" class="btn btn-white">
                                        <i class="material-icons">settings_backup_restore</i>
                                        <span class="icon-text">Retake Quiz</span>
                                      </button></a>

                                    </p>
                                    <p>
                                      <a href="quiz_results?quiz='.$lmid.'"><button type="button" class="btn btn-success">
                                        <i class="material-icons">assessment</i>
                                        <span class="icon-text">View Results</span>
                                      </button></a>
                                      
                                    </p>
                                  </nav>
                                </div>
                                <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;float: left;width: 100%;">
                                  <p>
                                    '.$buttonx.'
                                  </p>
                                </div>';
                  }
                  else{
                    $mq = mysqli_query($link, "SELECT * FROM questions WHERE qid='$lmid' ");
                    $mr = mysqli_fetch_assoc($mq);
                    $qtnum = $mr['quizqnum'];

                    $qqq = mysqli_query($link, "SELECT * FROM queries WHERE qid='$lmid' ORDER BY RAND() LIMIT $qtnum ");
                    $qqnum = mysqli_num_rows($qqq);
                    $queries = '';
                    if($qqnum == 0){
                      $queries = '<nav class="center">
                                    <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">help_outline</i>
                                    <p>No questions found</p>
                                  </nav>';
                    }
                    else{
                      
                      while ($qqr = mysqli_fetch_array($qqq, MYSQLI_ASSOC)) {
                        $qattach = '';
                        $qqid = $qqr['qqid'];
                        $questionx = $qqr['question'];
                        if(empty($qqr['attach'])==false){
                          if($qqr['attach_type'] == 'i'){
                            $qattach = '<nav class="center">
                                          <img  src="assets/materials/'.$qqr['attach'].'" style="width:50%;background-size:100%;">
                                        </nav>';
                          }
                          else{
                            $qattach = '<nav class="center">
                                          <audio controls style="width: 100%;">
                                            <source src="assets/materials/'.$qqr['attach'].'" type="audio/mpeg">
                                          </audio>
                                        </nav>';
                          }
                        }

                        $options = '';
                        $opq = mysqli_query($link, "SELECT * FROM qanswers WHERE qqid='$qqid' ");
                        while ($opr = mysqli_fetch_array($opq, MYSQLI_ASSOC)) {
                            $options .= '<div class="form-group">
                                            <label class="c-input c-radio">
                                              <input name="qans['.$qqid.']" type="radio" value="'.$opr['options'].'">
                                              <span class="c-indicator"></span> '.$opr['options'].'
                                            </label>
                                          </div>';
                        }

                        $queries .= '<p><b>'.$questionx.'</b>'.$qattach .'<br></p>'.$options.'<hr>';
                      }
                      $queries = '<form method="POST" action="">
                                  '.$queries.'
                                  <input type="hidden" name="slcidx" value="'.$slcidx.'">
                                  <p style="margin-bottom: 10px;float: right;">
                                      <button type="submit" name="quizout" class="btn btn-success" style="float:right;">
                                        <span class="icon-text">Submit</span>
                                        <i class="material-icons">send</i>
                                      </button>
                                  </p>
                                </form>';
                    }

                    $lessonx = '<div class="card col-lg-12" style="margin-top:20px;float:left;">
                                <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                                <hr>
                                '.$queries.'
                              </div>
                              <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;float: left;width: 100%;">
                                <p>
                                  '.$buttonx.'
                                </p>
                              </div>';
                  }

                  
                }
              }
              
            }

            $gradz = grade_student($link, $uidx, $slidx);
            if($gradz != '0'){
              $gradez = '<div class="media-right">
                            <div class="gradebx">'.$gradz.'</div>
                          </div>';
            }

            $ensubs = '<div class="card-header bg-white">
                          <div class="media">
                            <div class="media-left">
                              <img src="assets/images/'.$subicon.'" alt="" class="img-rounded" width="60">
                            </div>
                            <div class="media-body media-middle">
                              <h4 class="card-title">'.$subjectnm.'</h4>
                              <span class="label label-primary">'.$levelnm.'</span>
                            </div>
                            '.$gradez.'
                          </div>
                          <p style="margin-top: 20px;font-size: 18px;">Lesson Title: <b style="text-transform: capitalize;">'.$lessontitle.'</b></p>
                          <p style="margin: 5px 0;color:#999;text-align:right;">Completed: '.$progressx.'%</p>
                          <progress class="progress progress-primary progress-striped m-b-0" value="'.$progressx.'" max="100">'.$progressx.'%</progress>
                        </div>'.$lessonx;

            

        }
          $dataresult = '<div class="container-fluid">
                              <ol class="breadcrumb">
                                <li><a href="#">Take Lesson</a></li>
                              </ol>

                              '.$ensubs.'
                            </div>';

        
  }
  else if(isset($_GET['lesson']) && empty($_GET['lesson']) == false){
        $slcidx = clean($link, $_GET['lesson']);
        $ensubs = '';
        $getstsub = mysqli_query($link, "SELECT * FROM student_subject_level WHERE stid='$uidx' AND slid=(SELECT slid FROM subject_level_curriculum WHERE slcid='$slcidx') ");
        $gsubnum =mysqli_num_rows($getstsub);
        if($gsubnum == 0){
          $ensubs = '<div class="center" style="margin-top: 50px;color: #999;">
                        <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                        <p>You have not enrolled for this subject</p>
                        <a href="hub"><button type="button" class="btn btn-primary">
                          <i class="material-icons">home</i>
                          <span class="icon-text">Go Home</span>
                        </button></a>
                      </div>';
        }
        else{
            $gr = mysqli_fetch_assoc($getstsub);
            $slidx = $gr['slid'];
            $suballq = mysqli_query($link, "SELECT sid,lid FROM subject_level WHERE slid='$slidx' ");
            $subr = mysqli_fetch_assoc($suballq);
            $sidz = $subr['sid'];
            $lidz = $subr['lid'];

            $levelnm = get_level($link, $lidz);
            $subjx = get_subject($link, $sidz);
            $subjx = explode('|', $subjx);
            $subjectnm = $subjx[0];
            $subicon = $subjx[1];
            $stprog = mysqli_query($link, "SELECT progress FROM student_progress WHERE stid='$uidx' AND slcid IN (SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx') ");
            $stpnum = mysqli_num_rows($stprog);
            if($stpnum == 0){
              $progressx = 0;
              $lessonxnum = 1;
            }
            else{
              $spr = mysqli_fetch_assoc($stprog);
              $progressx = $spr['progress'];
              
              $scqx = mysqli_query($link, "SELECT * FROM subject_level_curriculum WHERE slid='$slidx' ");
              $sqnumx = mysqli_num_rows($scqx);
              $lessonxnum = ($progressx/100)*$sqnumx;
              // $lessonxnum++;
            }

            

            $scq = mysqli_query($link, "SELECT * FROM subject_level_curriculum WHERE slcid='$slcidx'");
            $sqnum = mysqli_num_rows($scq);
            if($sqnum == 0){
              $lessontitle = '';
              $lessonx = '<div class="center" style="margin-top: 50px;color: #999;">
                        <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                        <p>This subject has no lessons yet...</p>
                        <a href="hub"><button type="button" class="btn btn-primary">
                          <i class="material-icons">home</i>
                          <span class="icon-text">Go Home</span>
                        </button></a>
                      </div>';
            }
            else{
              $lsr = mysqli_fetch_assoc($scq);
              $lessontitle = $lsr['lesson_name'];

              $lsq = mysqli_query($link, "SELECT * FROM lesson_structure WHERE slcid='$slcidx' ORDER BY mnum ASC LIMIT 1 ");
              $lssnum = mysqli_num_rows($lsq);
              if($lssnum == 0){
                $lessonx = '<div class="center" style="margin-top: 50px;color: #999;">
                        <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                        <p>This lessons has no materials yet...</p>
                        <a href="hub"><button type="button" class="btn btn-primary">
                          <i class="material-icons">home</i>
                          <span class="icon-text">Go Home</span>
                        </button></a>
                      </div>';
              }
              else{
                $lsnq = mysqli_query($link, "SELECT mnum FROM lesson_structure WHERE slcid='$slcidx' ");
                $lnssnum = mysqli_num_rows($lsnq);
                $lnumarray = array();
                $counterz = 0;
                while ($lns = mysqli_fetch_array($lsnq, MYSQLI_ASSOC)) {
                  $lnumarray[$counterz] = $lns['mnum'];
                  $counterz++;
                }

                $lssize = count($lnumarray);

                $prevbutt = '';
                $slciday = array();
                $gtscq = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' ");
                while ($gsc = mysqli_fetch_array($gtscq, MYSQLI_ASSOC)) {
                  array_push($slciday, $gsc['slcid']);
                }
                for ($i=0; $i < count($slciday); $i++) { 
                  if($slciday[$i] == $slcidx){
                    if($i != 0){
                      $pslcidx = $slciday[$i-1];
                      $prevbutt = '<a href="take_lesson?lesson='.$pslcidx.'" style="float:left;">
                                                <button type="button" class="btn btn-primary">
                                                  <i class="material-icons">arrow_back</i>
                                                  <span class="icon-text">Previous Lesson</span>
                                                </button>
                                              </a>';
                    }
                  }
                }


                if($lssize == 1){
                  $scqx = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' ORDER BY lesson_number DESC LIMIT 1 ");
                  $slr = mysqli_fetch_assoc($scqx);
                  if($slr['slcid'] == $slcidx){
                    $nextlesson = 'next';
                    $buttonx = $prevbutt.'<a href="take_lesson?lesson='.$slcidx.'&number='.$nextlesson.'" style="float:right;">
                                  <button type="button" class="btn btn-white">
                                    <span class="icon-text">Finish</span>
                                    <i class="material-icons">arrow_forward</i>
                                  </button>
                                </a>';
                  }
                  else{
                    $nextlesson = 'next';
                    $buttonx = $prevbutt.'<a href="take_lesson?lesson='.$slcidx.'&&number='.$nextlesson.'" style="float:right;">
                                  <button type="button" class="btn btn-primary">
                                    <span class="icon-text">Next</span>
                                    <i class="material-icons">arrow_forward</i>
                                  </button>
                                </a>';
                  }
                }
                else{
                  // $scqzx = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' ");

                    $nextlesson = $lnumarray[1];
                    $buttonx = $prevbutt.'<a href="take_lesson?lesson='.$slcidx.'&number='.$nextlesson.'" style="float:right;">
                                <button type="button" class="btn btn-primary">
                                  <span class="icon-text">Next</span>
                                  <i class="material-icons">arrow_forward</i>
                                </button>
                              </a>';
                }

                $lzr = mysqli_fetch_assoc($lsq);
                $lmid = $lzr['mid'];
                $lmtype = $lzr['mtype'];

                if($lmtype == 'd'){
                  $mq = mysqli_query($link, "SELECT * FROM document WHERE dcid='$lmid' ");
                  $mr = mysqli_fetch_assoc($mq);
                  $lessonx = '<div class="card col-lg-12" style="margin-top:20px;">
                              <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                              <hr>
                              <p style="margin-bottom: 5px;">'.$mr['text'].'</p>
                            </div>
                            <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;">
                              <p>
                                '.$buttonx.'
                              </p>
                            </div>';
                }
                else if($lmtype == 'a'){
                  $mq = mysqli_query($link, "SELECT * FROM videonaudio WHERE vaid='$lmid' ");
                  $mr = mysqli_fetch_assoc($mq);
                  $lessonx = '<div class="card col-lg-12" style="margin-top:20px;">
                              <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                              <hr>
                              <p>'.$mr['description'].'</p>
                              <nav class="center">
                                <audio controls style="width: 100%;">
                                  <source src="assets/materials/'.$mr['path'].'" type="audio/mpeg">
                                </audio>
                              </nav>
                            </div>
                            <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;">
                              <p>
                                '.$buttonx.'
                              </p>
                            </div>';
                }
                else if($lmtype == 'v'){
                  $mq = mysqli_query($link, "SELECT * FROM videonaudio WHERE vaid='$lmid' ");
                  $mr = mysqli_fetch_assoc($mq);
                  $lessonx = '<div class="card col-lg-12" style="margin-top:20px;">
                              <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                              <hr>
                              <p style="margin-bottom: 5px;">'.$mr['description'].'</p>
                              <nav class="center">
                                <video style="width:70%" controls>
                                  <source src="assets/materials/'.$mr['path'].'" type="video/mp4">
                                </video>
                              </nav>
                            </div>
                            <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;">
                              <p>
                                '.$buttonx.'
                              </p>
                            </div>';
                }
                else if($lmtype == 'i'){
                  $mq = mysqli_query($link, "SELECT * FROM image WHERE imid='$lmid' ");
                  $mr = mysqli_fetch_assoc($mq);
                  $lessonx = '<div class="card col-lg-12" style="margin-top:20px;">
                              <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                              <hr>
                              <p style="margin-bottom: 5px;">'.$mr['description'].'</p>
                              <nav class="center">
                                <img  src="assets/materials/'.$mr['path'].'" style="width:50%;background-size:100%;">
                              </nav>
                            </div>
                            <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;">
                              <p>
                                '.$buttonx.'
                              </p>
                            </div>';
                }
                else if($lmtype == 'g'){
                  $mq = mysqli_query($link, "SELECT * FROM games WHERE gid='$lmid' ");
                  $mr = mysqli_fetch_assoc($mq);
                  $lessonx = '<div class="card col-lg-12" style="margin-top:20px;">
                              <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                              <hr>
                              <p style="margin-bottom: 5px;">'.$mr['description'].'</p>
                              <nav class="center">
                                <object  data="assets/materials/'.$mr['path'].'" width="500" height="200"></object>
                              </nav>
                            </div>
                            <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;">
                              <p>
                                '.$buttonx.'
                              </p>
                            </div>';
                }
                else if($lmtype == 'q'){
                  $qz = mysqli_query($link, "SELECT qqid,answer FROM student_queries WHERE qqid IN (SELECT qqid FROM queries WHERE qid='$lmid' ) AND stid='$uidx' ");

                  $qznum = mysqli_num_rows($qz);
                  if($qznum > 0){
                    $mq = mysqli_query($link, "SELECT * FROM questions WHERE qid='$lmid' ");
                    $mr = mysqli_fetch_assoc($mq);
                    $qtnum = $mr['quizqnum'];
                    $countt = 0;


                    while($az = mysqli_fetch_array($qz, MYSQLI_ASSOC)){
                      $stanswer = $az['answer'];
                      $qqidz = $az['qqid'];

                      $qd = mysqli_query($link, "SELECT answer FROM queries WHERE qqid='$qqidz' ");
                      $ar = mysqli_fetch_assoc($qd);
                      if($stanswer == $ar['answer']){
                        $countt++;
                      }
                    }

                    $lessonx = '<div class="card col-lg-12" style="margin-top:20px;float:left;">
                                  <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                                  <hr>
                                  <nav class="center">
                                    <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">bubble_chart</i>
                                    <p>You have completed this quiz already</p>
                                    <p style="color:green;">Score: '.$countt.'/'.$qtnum.'</p>
                                    <p>
                                      <a href="take_quiz?quiz='.$lmid.'"><button type="button" class="btn btn-white">
                                        <i class="material-icons">settings_backup_restore</i>
                                        <span class="icon-text">Retake Quiz</span>
                                      </button></a>
                                    </p>
                                    <p>
                                      <a href="quiz_results?quiz='.$lmid.'"><button type="button" class="btn btn-success">
                                        <i class="material-icons">assessment</i>
                                        <span class="icon-text">View Results</span>
                                      </button></a>
                                      
                                    </p>
                                  </nav>
                                </div>
                                <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;float: left;width: 100%;">
                                  <p>
                                    '.$buttonx.'
                                  </p>
                                </div>';
                  }
                  else{
                    $mq = mysqli_query($link, "SELECT * FROM questions WHERE qid='$lmid' ");
                    $mr = mysqli_fetch_assoc($mq);
                    $qtnum = $mr['quizqnum'];

                    $qqq = mysqli_query($link, "SELECT * FROM queries WHERE qid='$lmid' ORDER BY RAND() LIMIT $qtnum ");
                    $qqnum = mysqli_num_rows($qqq);
                    $queries = '';
                    if($qqnum == 0){
                      $queries = '<nav class="center">
                                    <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">help_outline</i>
                                    <p>No questions found</p>
                                  </nav>';
                    }
                    else{
                      
                      while ($qqr = mysqli_fetch_array($qqq, MYSQLI_ASSOC)) {
                        $qattach = '';
                        $qqid = $qqr['qqid'];
                        $questionx = $qqr['question'];
                        if(empty($qqr['attach'])==false){
                          if($qqr['attach_type'] == 'i'){
                            $qattach = '<nav class="center">
                                          <img  src="assets/materials/'.$qqr['attach'].'" style="width:50%;background-size:100%;">
                                        </nav>';
                          }
                          else{
                            $qattach = '<nav class="center">
                                          <audio controls style="width: 100%;">
                                            <source src="assets/materials/'.$qqr['attach'].'" type="audio/mpeg">
                                          </audio>
                                        </nav>';
                          }
                        }

                        $options = '';
                        $opq = mysqli_query($link, "SELECT * FROM qanswers WHERE qqid='$qqid' ");
                        while ($opr = mysqli_fetch_array($opq, MYSQLI_ASSOC)) {
                            $options .= '<div class="form-group">
                                            <label class="c-input c-radio">
                                              <input name="qans['.$qqid.']" type="radio" value="'.$opr['options'].'">
                                              <span class="c-indicator"></span> '.$opr['options'].'
                                            </label>
                                          </div>';
                        }

                        $queries .= '<p><b>'.$questionx.'</b>'.$qattach .'<br></p>'.$options.'<hr>';
                      }
                      $queries = '<form method="POST" action="">
                                  '.$queries.'
                                  <input type="hidden" name="slcidx" value="'.$slcidx.'">
                                  <p style="margin-bottom: 10px;float: right;">
                                      <button type="submit" name="quizout" class="btn btn-success" style="float:right;">
                                        <span class="icon-text">Submit</span>
                                        <i class="material-icons">send</i>
                                      </button>
                                  </p>
                                </form>';
                    }

                    $lessonx = '<div class="card col-lg-12" style="margin-top:20px;float:left;">
                                <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                                <hr>
                                '.$queries.'
                              </div>
                              <div class="card col-lg-12" style="margin-top:20px;margin-bottom:50px;padding:10px;float: left;width: 100%;">
                                <p>
                                  '.$buttonx.'
                                </p>
                              </div>';
                  }

                  
                }
              }
              
            }

            $gradz = grade_student($link, $uidx, $slidx);
            if($gradz != '0'){
              $gradez = '<div class="media-right">
                            <div class="gradebx">'.$gradz.'</div>
                          </div>';
            }

            $ensubs = '<div class="card-header bg-white">
                          <div class="media">
                            <div class="media-left">
                              <img src="assets/images/'.$subicon.'" alt="" class="img-rounded" width="60">
                            </div>
                            <div class="media-body media-middle">
                              <h4 class="card-title">'.$subjectnm.'</h4>
                              <span class="label label-primary">'.$levelnm.'</span>
                            </div>
                            '.$gradez.'
                          </div>
                          <p style="margin-top: 20px;font-size: 18px;">Lesson Title: <b style="text-transform: capitalize;">'.$lessontitle.'</b></p>
                          <p style="margin: 5px 0;color:#999;text-align:right;">Completed: '.$progressx.'%</p>
                          <progress class="progress progress-primary progress-striped m-b-0" value="'.$progressx.'" max="100">'.$progressx.'%</progress>
                        </div>'.$lessonx;

            

          }
          $dataresult = '<div class="container-fluid">
                              <ol class="breadcrumb">
                                <li><a href="#">Take Lesson</a></li>
                              </ol>

                              '.$ensubs.'
                            </div>';

  }
  else{
          $dataresult = '<div class="container-fluid">
                              <ol class="breadcrumb">
                                <li><a href="#">Take Lesson</a></li>
                              </ol>
                              <div class="center" style="margin-top: 50px;color: #999;">
                                <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                                <p>No subject selected</p>
                                <a href="hub"><button type="button" class="btn btn-primary">
                                  <i class="material-icons">home</i>
                                  <span class="icon-text">Go Home</span>
                                </button></a>
                              </div>
                            </div>';
  }
}

$jhssubs=$shssubs= '';
$jsq = mysqli_query($link, "SELECT * FROM level WHERE 1 ORDER BY level_name ASC");
while ($jr = mysqli_fetch_array($jsq, MYSQLI_ASSOC)) {
  $lidz = $jr['lid'];
  $lvname = $jr['level_name'];
  $sq = mysqli_query($link, "SELECT sid,slid FROM subject_level WHERE lid='$lidz'");

  while ($sr = mysqli_fetch_array($sq, MYSQLI_ASSOC)) {
    $sjid = $sr['sid'];
    $slidx = $sr['slid'];
    $sujq = mysqli_query($link, "SELECT subject FROM subject WHERE sid='$sjid' ");
    $sjr = mysqli_fetch_assoc($sujq); 
    $sub = $sjr['subject'];
    
      if($lvname == 'JHS 1' || $lvname == 'JHS 2' || $lvname == 'JHS 3'){
          $jhssubs .= '<div class="checkbox">
                        <label>
                          <input type="checkbox" name="subject[]" value="'.$slidx.'"> '.$sub.' for '.$lvname.'
                        </label>
                      </div>';
      }
      else{
          $shssubs .= '<div class="checkbox">
                        <label>
                          <input type="checkbox" name="subject[]" value="'.$slidx.'"> '.$sub.' for '.$lvname.'
                        </label>
                      </div>';
      }
  }

}


?>


<!DOCTYPE html>
<html class="bootstrap-layout">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Dashboard</title>

  <!-- Prevent the demo from appearing in search engines (REMOVE THIS) -->
  <meta name="robots" content="noindex">
  <link rel="shortcut icon" href="assets/images/ccwhite.png">

  <!-- Material Design Icons  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- Preloader Css -->
  <link href="assets/css/md-preloader.css" rel="stylesheet" />

  <!-- Roboto Web Font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">

  <!-- jQuery -->
  <script src="assets/vendor/jquery.min.js"></script>

  <!-- Datepicker -->
  <link rel="stylesheet" href="examples/css/bootstrap-datepicker.min.css">

  <!-- App CSS -->
  <link type="text/css" href="assets/css/style.css" rel="stylesheet">

   <!-- Vendor CSS -->
  <link rel="stylesheet" href="examples/css/nestable.min.css">

  <!-- Required by summernote -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/fontawesome/4.5.0/css/font-awesome.min.css">

  <!-- Summernote WYSIWYG -->
  <link rel="stylesheet" href="examples/css/summernote.min.css">

  <!-- Touchspin -->
  <link rel="stylesheet" href="examples/css/bootstrap-touchspin.min.css">

  <link rel="stylesheet" href="assets/css/parsley.css">  
  <link rel="stylesheet" type="text/css" href="assets/css/sweetalert.css">
  <script src="assets/js/sweetalert.min.js"></script> 
  <script src="assets/js/parsley.js"></script>
<style type="text/css">
.gradebx{
    width: 60px;
    border-radius: 40px;
    height: 60px;
    background-color: #fbfbfb;
    text-align: center;
    color: #2196f3;
    font-size: 20px;
    padding: 5px;
  }
  .gradebx span{
    margin: 0;
    float: left;
    width: 100%;
  }

.usernmx{
  text-transform: capitalize;
}
.userformbx{
  width: 90%;
  min-height: 40px;
  margin: 10px auto;
}

.userform,select{
    width: 100%;
    margin: 0;
    height: 40px;
    padding: 5px;
    border: 2px solid #ccc;
    border-radius: 4px;
}
#progressover{
  background-color: rgba(39, 39, 39, 0.86);
  width: 100%;
    height: 100%;
    z-index: 100000;
    float: left;
    overflow: scroll;
    display: none;
    top: 0;
    position: fixed;
}
#addsubjectover{
  background-color: rgba(181, 181, 181, 0.86);
  width: 100%;
    height: 100%;
    z-index: 100000;
    float: left;
    overflow: scroll;
    display: none;
    top: 0;
    position: fixed;
}
.lessonzx{
  font-size: 17px;
  padding: 10px;
}
.lessonzx span{
  font-weight: 700;
}
#addlessonmatover{
  background-color: rgba(0, 0, 0, 0.85);
  width: 100%;
  height: 100%;
  z-index: 100000;
  float: left;
  overflow: scroll;
  display: none;
  top: 0;
  position: fixed;
}
#addquestionover{
  background-color: rgba(0, 0, 0, 0.85);
  width: 100%;
  height: 100%;
  z-index: 100000;
  float: left;
  overflow: scroll;
  display: none;
  top: 0;
  position: fixed;
}
#addlessonover{
  background-color: rgba(0, 0, 0, 0.85);
  width: 100%;
    height: 100%;
    z-index: 100000;
    float: left;
    overflow: scroll;
    display: none;
    top: 0;
    position: fixed;
}
#addsubjectlevelover{
  background-color: rgba(181, 181, 181, 0.86);
  width: 100%;
    height: 100%;
    z-index: 100000;
    float: left;
    overflow: scroll;
    display: none;
    top: 0;
    position: fixed;
}
 #grayoverlay{
  background-color: rgba(181, 181, 181, 0.86);
  width: 100%;
    height: 100%;
    z-index: 100000;
    float: left;
    overflow: scroll;
    display: none;
    top: 0;
    position: fixed;
 }
 .topheader{
  width: 100%;
  height: 25px;
  border-bottom: 1px solid #eaeaea;
 }
#lessonstbx{
  width: 100%;
  min-height: 50px;
}
#questionbx{
  width: 100%;
  min-height: 50px;
}
.toolsbox{
  width: 100%;
  min-height: 200px;
}
.buttonmarg{
  margin: 5px;
}
.elementbx{
  width: 100%;
  border:1px solid #ccc;
  float: left;
  min-height: 100px;
  border-bottom: 1px solid #ccc;
  margin-bottom: 10px;
}
.elementitle span{
  float: left;
  width: 60%;
  padding: 4px;
  font-weight: 700;
  height: 100%;
  font-size: 16px;
}
.elementitle{
  float: left;
  width: 100%;
  height: 30px;
  background-color: #ccc;

}
.note-popover .popover-content, .panel-heading.note-toolbar{
    background-color: #efefef;
    border-bottom: 1px solid #e0e0e0;
}
.closeele{
  float: right;
  color: #fff;
  text-align: center;
  padding: 2px;
  cursor: pointer;
  width: 30px;
  background-color: #ff6464;
  height: 30px;
}
.elementbody{
  float: left;
  padding: 5px;
  width: 100%;
  min-height: 40px;
}
#optioncnt{
  width: 100%;
  min-height: 50px;
  padding: 10px;
  float: left;
  margin: 5px 0;
  border: 1px dashed #eee;
}
.answerbx{
  width: 100%;
  min-height: 20px;
  padding: 10px;
  margin-top: 20px;
  float: left;
  border: 1px dashed #eee;
}
</style>
</head>

<body class="layout-container ls-top-navbar si-l3-md-up">
<?php echo $echoscript;?>
  <!-- Navbar -->
  <nav class="navbar navbar-dark bg-primary navbar-full navbar-fixed-top">

    <!-- Toggle sidebar -->
    <button class="navbar-toggler pull-xs-left" type="button" data-toggle="sidebar" data-target="#sidebarLeft"><span class="material-icons">menu</span></button>

    <!-- Brand -->
    <a href="hub" class="navbar-brand" style="background-image:url(assets/images/ccwhite.png);width: 40px;margin: 8px;height: 40px;background-size: 100%;"></a>

    <!-- Search -->
    <form class="form-inline pull-xs-left hidden-sm-down">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search">
        <span class="input-group-btn"><button class="btn" type="button"><i class="material-icons">search</i></button></span>
      </div>
    </form>
    <!-- // END Search -->

    <ul class="nav navbar-nav hidden-sm-down">

      <!-- Menu -->
      <!-- <li class="nav-item">
        <a class="nav-link" href="forum.html">Forum</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="get-help.html">Get Help</a>
      </li> -->
    </ul>

    <!-- Menu -->
    <ul class="nav navbar-nav pull-xs-right">

      <!-- <li class="nav-item">
        <a href="cart.html" class="nav-link">
          <i class="material-icons">shopping_cart</i>
        </a>
      </li> -->

      <!-- User dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link active dropdown-toggle p-a-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false">
          <span class="usernmx"><?php echo $adminnm;?></span>
          <img src="assets/avatar/<?php echo $avatar;?>" alt="Avatar" class="img-circle" width="40">
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-list" aria-labelledby="Preview">
          <a class="dropdown-item" href="account-edit.html"><i class="material-icons md-18">lock</i> <span class="icon-text">Edit Account</span></a>
          
          <a class="dropdown-item" href="inc/logout">Logout</a>
        </div>
      </li>
      <!-- // END User dropdown -->

    </ul>
    <!-- // END Menu -->

  </nav>
  <!-- // END Navbar -->
  <!-- Sidebar -->
  <div class="sidebar sidebar-left sidebar-light sidebar-visible-md-up si-si-3 ls-top-navbar-xs-up sidebar-transparent-md" id="sidebarLeft" data-scrollable>
    <!-- 
    <div class="sidebar-heading">Student</div> -->
    <ul class="sidebar-menu">
      <?php echo $menu;?>
    </ul>
  </div>
  <!-- // END Sidebar -->

  <!-- Content -->
  <div class="layout-content" data-scrollable>
    <?php echo $dataresult;?>
  </div>

  <div id="addsubjectover">
    <div class="row">
      <div class="col-sm-8 col-sm-push-1 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
        <div class="center m-a-2">
          <div class="icon-block img-circle" onclick="hideform()" style="background-color: #fff;cursor:pointer;">
            <i class="material-icons md-36 text-muted" style="color: #ff6666;font-weight: 800;">close</i>
          </div>
        </div>
        <div class="card">
          <div class="card-header bg-white center">
            <h4 class="card-title">Subject Form</h4>
            <p class="card-subtitle">Add a new subject</p>
          </div>
          <div class="p-a-2">
            <form action="" method="POST" data-parsley-validate enctype="multipart/form-data">
              <div class="form-group">
                <input type="text" class="form-control" name="subject" placeholder="Subject" required>
              </div>
              <div class="form-group">
                <input type="file" class="form-control" name="icon" required>
              </div>
              <p class="center">
                <button type="submit" name="addsubject" class="btn btn-success btn-rounded btn-block">Add Subject</button>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="addsubjectlevelover">
    <div class="row">
      <div class="col-sm-8 col-sm-push-1 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
        <div class="center m-a-2">
          <div class="icon-block img-circle" onclick="hideform()" style="background-color: #fff;cursor:pointer;">
            <i class="material-icons md-36 text-muted" style="color: #ff6666;font-weight: 800;">close</i>
          </div>
        </div>
        <div class="card">
          <div class="card-header bg-white center">
            <h4 class="card-title">Subject Level Form</h4>
            <p class="card-subtitle">Create subject for a level</p>
          </div>
          <div class="p-a-2">
            <form action="" method="POST" data-parsley-validate>
              <div class="form-group">
               <select class="form-control" name="subject" required>
                  <option value="">*-- Subject --*</option>
                  <?php echo $subjectz;?>
                </select>
              </div>
              <div class="form-group">
               <select class="form-control" name="level" required>
                  <option value="">*-- Level --*</option>
                  <?php echo $levels;?>
                </select>
              </div>
              <p class="center">
                <button type="submit" name="addsubjectlevel" class="btn btn-success btn-rounded btn-block">Create</button>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="progressover">
    <div class="loader" style="width:100px;height:100px;margin:200px auto;">
        <div class="md-preloader pl-size-md">
            <svg viewBox="0 0 75 75" style="margin: 0 20px;width: 100%;">
                <circle cx="37.5" cy="37.5" r="33.5" class="pl-white" stroke-width="6"></circle>
            </svg>
        </div>
        <p style="color:#fff;width:100%;text-align:center;">Please wait...</p>
    </div>
  </div>

  

  <!-- Bootstrap -->
  <script src="assets/vendor/tether.min.js"></script>
  <script src="assets/vendor/bootstrap.min.js"></script>

  <!-- AdminPlus -->
  <script src="assets/vendor/adminplus.js"></script>

  <!-- App JS -->
  <script src="assets/js/main.min.js"></script>

  <!-- Custom js -->
  <script src="assets/js/action.js"></script>

    <!-- Vendor JS -->
  <script src="assets/vendor/bootstrap-datepicker.min.js"></script>

  <!-- Init -->
  <script src="examples/js/date-time.js"></script>

  <!-- Vendor JS -->
  <script src="assets/vendor/jquery.nestable.js"></script>
  <script src="assets/vendor/summernote.min.js"></script>

  <!-- Initialize -->
  <script src="examples/js/nestable.js"></script>
  <script src="examples/js/summernote.js"></script>
  <script src="examples/js/touchspin.js"></script>

</body>

</html>