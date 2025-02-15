<?php
session_start();
require 'inc/over.php';

//check if user session is active and get user data
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
else{ //if user session is not active, redirect user to the login page
  header('location: login');
}

$slcidx='';
$error=0;
$errormesg=$echoscript='';

//get subject levels
$levq = mysqli_query($link, "SELECT * FROM level WHERE 1 ORDER BY level_name ASC ");
$levels = '';
while ($lr = mysqli_fetch_array($levq, MYSQLI_ASSOC)) {
  $levels .= '<option value="'.$lr['lid'].'">'.$lr['level_name'].'</option>';
}

//get subjects
$stjq = mysqli_query($link, "SELECT * FROM subject WHERE 1 ORDER BY subject ASC ");
$subjectz = '';
while ($sbjr = mysqli_fetch_array($stjq, MYSQLI_ASSOC)) {
  $subjectz .= '<option value="'.$sbjr['sid'].'">'.$sbjr['subject'].'</option>';
}

// Get the protocol (HTTP or HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// Get the host name (e.g., www.example.com)
$host = $_SERVER['HTTP_HOST'];

// Get the URI (e.g., /path/to/page)
$uri = $_SERVER['REQUEST_URI'];

// Extract the last part of the URL using `basename`
$lastPart = basename($uri);

// Parse the query string part of the URL
$queryString = parse_url($lastPart, PHP_URL_QUERY);

// Extract the part before the '='
if ($queryString) {
    $lastPartUrlResult = strstr($queryString, '=', true);
}

$subcx = '';
//begin::admin
if($rlid == 3){
  if(isset($_POST['addquestionz'])){
    $qtype = clean($link, $_POST['qtype']);
    $qdescrip = clean($link, $_POST['qdescrip']);
    $qid = clean($link, $_POST['qid']);
    $qanswer = clean($link, $_POST['qanswer']);

    if($qtype == "qi"){
      $filex = $_FILES['qimage']['name'];
      $filexsize = $_FILES['qimage']['size'];
      $filetemp = $_FILES['qimage']['tmp_name'];

      if(empty($filex) == false){
          $imgupdt = checkimage($link,$filex,$filexsize);
          if($imgupdt == 0){
              $error++;
              $errormesg .= "Invalid file format(Allowed mp3,wav)<br>";
          }
          else if($imgupdt == 2){
              $errormesg .= "Audio size too big (Max image size 10MB)<br>";
              $error++;
          }
      }
    }
    else if($qtype == "qa"){
      $filex = $_FILES['qaudio']['name'];
      $filexsize = $_FILES['qaudio']['size'];
      $filetemp = $_FILES['qaudio']['tmp_name'];

      if(empty($filex) == false){
          $imgupdt = checkaudio($link,$filex,$filexsize);
          if($imgupdt == 0){
              $error++;
              $errormesg .= "Invalid file format(Allowed mp3,wav)<br>";
          }
          else if($imgupdt == 2){
              $errormesg .= "Audio size too big (Max image size 10MB)<br>";
              $error++;
          }
      }
    }


    if($error == 0){
      if($qtype == 'q'){
        $q = mysqli_query($link, "INSERT INTO queries(`qid`,`question`,`answer`) VALUES('$qid','$qdescrip','$qanswer') ");
        if($q){
          $qqid = mysqli_insert_id($link);
          foreach ($_POST['qoption'] as $option) {
            if(empty($option) == false){
              $inq = mysqli_query($link, "INSERT INTO qanswers(`qqid`,`options`) VALUES('$qqid','$option') ");
            }
          }
          if(!$inq){
            $error++;
          }
        }
        else{
          echo $qanswer;exit();
          $error++;
        }
      }
      else if($qtype == "qi"){
        $uploadmat = uploadmaterial($link,$filex,$filetemp);
        $q = mysqli_query($link, "INSERT INTO queries(`qid`,`question`,`answer`,`attach`,`attach_type`) VALUES('$qid','$qdescrip','$qanswer','$uploadmat','i') ");
        if($q){
          $qqid = mysqli_insert_id($link);
          foreach ($_POST['qoption'] as $option) {
            if(empty($option) == false){
              $inq = mysqli_query($link, "INSERT INTO qanswers(`qqid`,`options`) VALUES('$qqid','$option') ");
            }
          }
          if(!$inq){
            $error++;
          }
        }
        else{
          $error++;
        }

      }
      else if($qtype == "qa"){
        $uploadmat = uploadmaterial($link,$filex,$filetemp);
        $q = mysqli_query($link, "INSERT INTO queries(`qid`,`question`,`answer`,`attach`,`attach_type`) VALUES('$qid','$qdescrip','$qanswer','$uploadmat','a') ");
        if($q){
          $qqid = mysqli_insert_id($link);
          foreach ($_POST['qoption'] as $option) {
            if(empty($option) == false){
              $inq = mysqli_query($link, "INSERT INTO qanswers(`qqid`,`options`) VALUES('$qqid','$option') ");
            }
          }
          if(!$inq){
            $error++;
          }
        }
        else{
          $error++;
        }

      }

      if($error == 0){
        $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Question Added",
                                      text: "Question added successfully",
                                      type: "success",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
      }
      else{
        $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "Error adding question.",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
      }
    }
    else{
      $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "'.$errormesg.'",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
    }

  }

  if(isset($_POST['addlessonmatx'])){
    $mattype = clean($link, $_POST['mattype']);
    $materialtitle = clean($link, $_POST['materialtitle']);
    $mtnumber = clean($link, $_POST['mtnumber']);
    $slcidz = clean($link, $_POST['slcid']);

    if($mattype == 'd'){
      $descrip = cleanx($link, $_POST['descrip']);
    }
    else if($mattype == 'a'){
      $descrip = cleanx($link, $_POST['descrip']);
      $filex = $_FILES['audio']['name'];
      $filexsize = $_FILES['audio']['size'];
      $filetemp = $_FILES['audio']['tmp_name'];

      if(empty($filex) == false){
          $imgupdt = checkaudio($link,$filex,$filexsize);
          if($imgupdt == 0){
              $error++;
              $errormesg .= "Invalid file format(Allowed mp3,wav)<br>";
          }
          else if($imgupdt == 2){
              $errormesg .= "Audio size too big (Max image size 10MB)<br>";
              $error++;
          }
      }
    }
    else if($mattype == 'g'){
      $descrip = cleanx($link, $_POST['descrip']);
      $filex = $_FILES['game']['name'];
      $filexsize = $_FILES['game']['size'];
      $filetemp = $_FILES['game']['tmp_name'];

      if(empty($filex) == false){
          $imgupdt = checkgame($link,$filex,$filexsize);
          if($imgupdt == 0){
              $error++;
              $errormesg .= "Invalid file format(Allowed fla)<br>";
          }
          else if($imgupdt == 2){
              $errormesg .="Game size too big (Max image size 10MB)<br>";
              $error++;
          }
      }
    }
    else if($mattype == 'i'){
      $descrip = cleanx($link, $_POST['descrip']);
      $filex = $_FILES['image']['name'];
      $filexsize = $_FILES['image']['size'];
      $filetemp = $_FILES['image']['tmp_name'];

      if(empty($filex) == false){
          $imgupdt = checkimage($link,$filex,$filexsize);
          if($imgupdt == 0){
              $error++;
              $errormesg .= "Invalid file format(Allowed jpg,jpeg,png,svg)<br>";
          }
          else if($imgupdt == 2){
              $errormesg .="Image size too big (Max image size 2MB)<br>";
              $error++;
          }
      }
    }
    else if($mattype == 'v'){
      $descrip = cleanx($link, $_POST['descrip']);
      $filex = $_FILES['video']['name'];
      $filexsize = $_FILES['video']['size'];
      $filetemp = $_FILES['video']['tmp_name'];

      if(empty($filex) == false){
          $imgupdt = checkvideo($link,$filex,$filexsize);
          if($imgupdt == 0){
              $error++;
              $errormesg .= "Invalid file format(Allowed mp4,wmv)<br>";
          }
          else if($imgupdt == 2){
              $errormesg .="Video size too big (Max image size 50MB)<br>";
              $error++;
          }
      }
    }

    $ckmq = mysqli_query($link, "SELECT lstid FROM lesson_structure WHERE mnum='$mtnumber' AND slcid='$slcidz' ");
    $cmnum = mysqli_num_rows($ckmq);
    if($cmnum > 0){
      $errormesg .="Lesson material number already exits<br>";
      $error++;
    }

    if($error == 0){
      if($mattype == 'd'){
        $inmtq = mysqli_query($link, "INSERT INTO document(`title`,`text`,`added`) VALUES('$materialtitle','$descrip',now())");
      }
      else if($mattype == 'a'){
        $uploadmat = uploadmaterial($link,$filex,$filetemp);
        $inmtq = mysqli_query($link, "INSERT INTO videonaudio(`title`,`description`,`path`,`file_type`,`added`) VALUES('$materialtitle','$descrip','$uploadmat','$mattype',now())");
      }
      else if($mattype == 'g'){
        $uploadmat = uploadmaterial($link,$filex,$filetemp);
        $inmtq = mysqli_query($link, "INSERT INTO games(`title`,`description`,`path`,`added`) VALUES('$materialtitle','$descrip','$uploadmat',now())");
      }
      else if($mattype == 'i'){
        $uploadmat = uploadmaterial($link,$filex,$filetemp);
        $inmtq = mysqli_query($link, "INSERT INTO image(`title`,`description`,`path`,`added`) VALUES('$materialtitle','$descrip','$uploadmat',now())");
      }
      else if($mattype == 'q'){
        $slidv = $_GET['sub'];
        $quiznumber = clean($link, $_POST['quiznumber']);
        $qsl = mysqli_query($link, "SELECT sid FROM subject_level WHERE slid='$slidv' ");
        $srq = mysqli_fetch_assoc($qsl);
        $sids = $srq['sid'];

        $inmtq = mysqli_query($link, "INSERT INTO questions(`title`,`sid`,`quizqnum`,`added`) VALUES('$materialtitle','$sids','$quiznumber',now())");
      }
      else if($mattype == 'v'){
        $uploadmat = uploadmaterial($link,$filex,$filetemp);
        $inmtq = mysqli_query($link, "INSERT INTO videonaudio(`title`,`description`,`path`,`file_type`,`added`) VALUES('$materialtitle','$descrip','$uploadmat','$mattype',now())");
      }

      $midx = mysqli_insert_id($link);
      
      $inmq = mysqli_query($link, "INSERT INTO lesson_structure(slcid,mid,mnum,mtype) VALUES('$slcidz','$midx','$mtnumber','$mattype')");
      if(!$inmq){
        $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "Error adding lesson material.",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
      }
      else{
        $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Lesson Material Added",
                                      text: "Lesson material added successfully",
                                      type: "success",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
      }
      
    }
    else{
      $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "'.$errormesg.'",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
    }

  }

  if(isset($_POST['addlesson'])){
    $lessontitle = clean($link, $_POST['lessontitle']);
    $lnumber = clean($link, $_POST['lnumber']);
    $slid = clean($link, $_POST['subject_level']);

    if(empty($lessontitle) == false){
      $ck = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE lesson_name='$lessontitle' AND slid='$slid' ");
      $cknum = mysqli_num_rows($ck);
      if($cknum > 0){
        $error++;
        $errormesg .= 'Lesson title for this course already exits<br>';
      }
    }

    if(empty($lnumber) == false){
      $ckq = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE lesson_number='$lnumber' AND slid='$slid' ");
      $cknum = mysqli_num_rows($ckq);
      if($cknum > 0){
        $error++;
        $errormesg .= 'Lesson number for this course already exits<br>';
      }
    }


    if($error == 0){
      $inq = mysqli_query($link, "INSERT INTO subject_level_curriculum(`slid`,`lesson_name`,`lesson_number`,`created`) VALUES('$slid','$lessontitle','$lnumber',now())");
      if(!$inq){
        $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "Error adding lesson",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
      }
      else{
        $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Lesson Added",
                                      text: "Lesson added successfully",
                                      type: "success",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
      } 
    }
    else{
        $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "'.$errormesg.'",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
      }

  }

  if(isset($_POST['addsubject'])){
    $error=0;
    $errormesg = '';

    $subject = clean($link, $_POST['subject']);
    $icon = $_FILES['icon']['name'];
    $iconsize = $_FILES['icon']['size'];
    $icontmp = $_FILES['icon']['tmp_name'];

    if(empty($icon) == false){
        $imgupdt = checkimage($link,$icon,$iconsize);
        if($imgupdt == 0){
            $error++;
            $errormesg .= "Invalid file format(Allowed png,jpg,jpeg)<br>";
        }
        else if($imgupdt == 2){
            $errormesg .="Image size too big (Max image size 2MB)<br>";
            $error++;
        }
    }

    if (empty($subject)==true) {
      $error++;
      $errormesg .= "Course field empty<br>";
    }

    if($error == 0){
      $scq = mysqli_query($link, "SELECT sid FROM subject WHERE subject='$subject' ");
      $snum = mysqli_num_rows($scq);
      if($snum == 0){
        $icon = uploadimage($link,$icon,$icontmp);
        $q = mysqli_query($link, "INSERT INTO subject(subject,icon,added) VALUES('$subject','$icon',now())");
        if($q){
          $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Course Added!",
                                      text: "Course added successfully",
                                      type: "success",
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: false
                                  });
                          </script>';
        }
      }
      else{
        $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "Course already exit",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
      }
      
    }
    else{
      $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "'.$errormesg.'",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
    }
  }

  if(isset($_POST['addsubjectlevel'])){
    $subject = clean($link, $_POST['subject']);
    $level = clean($link, $_POST['level']);

    if(empty($subject)==false && empty($level)==false){
      $chq = mysqli_query($link, "SELECT slid FROM subject_level WHERE sid='$subject' AND lid='$level' ");
      $chnum = mysqli_num_rows($chq);
      if($chnum == 0){
        $q = mysqli_query($link, "INSERT INTO subject_level(sid,lid,added) VALUES('$subject','$level',now())");
        if($q){
          $echoscript = '<script type="text/javascript">
                                    swal({
                                        title: "Course Level Created!",
                                        text: "Course level created successfully",
                                        type: "success",
                                        showCancelButton: false,
                                        confirmButtonText: "Okay!",
                                        closeOnConfirm: false
                                    });
                            </script>';
        }
      }
      else{
        $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "Course level already exit",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
      }
      
    }
    else{
      $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "Some of the fields are empty",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
    }

  }

  if(isset($_GET['sub']) && (empty($_GET['sub']) == false) && isset($_GET['lesson']) && (empty($_GET['lesson']) == false)){
    $sub = clean($link, $_GET['sub']);
    $slcidx = clean($link, $_GET['lesson']);
    $subcx = $sub;
    $allcourses = '';
    $subq = mysqli_query($link, "SELECT slid,sid,lid FROM subject_level WHERE slid='$sub' ");
    $subnum = mysqli_num_rows($subq);
    if($subnum == 0){
      $allcourses = '<nav class="center" style="margin-top: 100px;">
                        <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                        <p>No info found</p>
                      </nav';
    }
    else{
      $subr = mysqli_fetch_assoc($subq);
      $lidx = $subr['lid'];
      $sidx = $subr['sid'];
      $slidx = $subr['slid'];

      $subjx = get_subject($link, $sidx);
      $subjx = explode('|', $subjx);
      $subjectnm = $subjx[0];

      $levelnm = get_level($link, $lidx);

      $clq = mysqli_query($link, "SELECT * FROM subject_level_curriculum WHERE slcid='$slcidx'");
      $clnum = mysqli_num_rows($clq);
      if($clnum == 0){
        $allcourses  = '<ol class="breadcrumb m-b-0">
                          <li><a href="hub">Course Manager</a></li>
                          <li style="text-transform:capitalize;"><a href="hub?sub='.$sub.'">'.$subjectnm.' - '.$levelnm.'</a></li>
                        </ol>
                        <div class="card col-lg-12" style="margin-top:20px;">
                          <h1 class="page-heading h4">
                            '.$subjectnm.'
                              <button onclick="addlessonmat()" style="float: right;" type="button" class="btn btn-primary">
                                <i class="material-icons">add_box</i>
                                <span class="icon-text">Add Lesson Materials</span>
                              </button>
                            <br><span class="label label-primary" style="font-size:14px;">'.$levelnm.'</span>
                          </h1>
                        </div>
                        <nav class="center" style="margin-top: 100px;">
                          <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">view_list</i>
                          <p>No lessons found</p>
                        </nav>';
      }
      else{
          $cr = mysqli_fetch_assoc($clq);
          $lesson_number = $cr['lesson_number'];
          $lesson_name = $cr['lesson_name']; 

          $lezz = '';

          $lsq = mysqli_query($link, "SELECT * FROM lesson_structure WHERE slcid='$slcidx' ORDER BY mnum ASC ");
          $lsnum = mysqli_num_rows($lsq);
          if($lsnum == 0){
            $lezz = '<nav class="center" style="margin-top: 100px;">
                        <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">view_list</i>
                        <p>No lessons material found</p>
                      </nav>';
          }
          else{
            while ($lqr = mysqli_fetch_array($lsq, MYSQLI_ASSOC)) {
              $midx = $lqr['mid'];
              $mtypex = $lqr['mtype'];

              if($mtypex == 'd'){
                $mq = mysqli_query($link, "SELECT * FROM document WHERE dcid='$midx' ");
                $mr = mysqli_fetch_assoc($mq);
                $lezz .= '<div class="card col-lg-12" style="margin-top:20px;">
                            <div class="card-button-wrapper" style="float: right;margin: 5px;position: static;">
                              <div class="btn btn-white btn-sm" style="color:#f44336;"><i class="material-icons">delete</i> </div>
                            </div>
                            <div class="card-button-wrapper" style="float: right;margin: 5px;position: static;">
                              <div class="btn btn-white btn-sm" style="color:#2196F3;"><i class="material-icons">create</i> </div>
                            </div>
                            <p style="margin-bottom: 5px;">Lesson Material: <span style="font-weight: bold;">'.$lqr['mnum'].'</span></p>
                            <p style="margin-bottom: 5px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                            <hr>
                            <p style="margin-bottom: 5px;">'.$mr['text'].'</p>
                          </div>';
              }
              else if($mtypex == 'a'){
                $mq = mysqli_query($link, "SELECT * FROM videonaudio WHERE vaid='$midx' ");
                $mr = mysqli_fetch_assoc($mq);
                $lezz .= '<div class="card col-lg-12" style="margin-top:20px;">
                            <div class="card-button-wrapper" style="float: right;margin: 5px;position: static;">
                              <div class="btn btn-white btn-sm" style="color:#f44336;"><i class="material-icons">delete</i> </div>
                            </div>
                            <div class="card-button-wrapper" style="float: right;margin: 5px;position: static;">
                              <div class="btn btn-white btn-sm" style="color:#2196F3;"><i class="material-icons">create</i> </div>
                            </div>
                            <p>Lesson Material: <span style="font-weight: bold;">'.$lqr['mnum'].'</span></p>
                            <p>Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                            <hr>
                            <p>'.$mr['description'].'</p>
                            <nav class="center">
                              <audio controls style="width: 100%;">
                                <source src="assets/materials/'.$mr['path'].'" type="audio/mpeg">
                              </audio>
                            </nav>
                          </div>';
              }
              else if($mtypex == 'v'){
                $mq = mysqli_query($link, "SELECT * FROM videonaudio WHERE vaid='$midx' ");
                $mr = mysqli_fetch_assoc($mq);
                $lezz .= '<div class="card col-lg-12" style="margin-top:20px;">
                            <div class="card-button-wrapper" style="float: right;margin: 5px;position: static;">
                              <div class="btn btn-white btn-sm" style="color:#f44336;"><i class="material-icons">delete</i> </div>
                            </div>
                            <div class="card-button-wrapper" style="float: right;margin: 5px;position: static;">
                              <div class="btn btn-white btn-sm" style="color:#2196F3;"><i class="material-icons">create</i> </div>
                            </div>
                            <p style="margin-bottom: 5px;">Lesson Material: <span style="font-weight: bold;">'.$lqr['mnum'].'</span></p>
                            <p style="margin-bottom: 5px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                            <hr>
                            <p style="margin-bottom: 5px;">'.$mr['description'].'</p>
                            <nav class="center">
                              <video style="width:70%" controls>
                                <source src="assets/materials/'.$mr['path'].'" type="video/mp4">
                              </video>
                            </nav>
                          </div>';
              }
              else if($mtypex == 'i'){
                $mq = mysqli_query($link, "SELECT * FROM image WHERE imid='$midx' ");
                $mr = mysqli_fetch_assoc($mq);
                $lezz .= '<div class="card col-lg-12" style="margin-top:20px;">
                            <div class="card-button-wrapper" style="float: right;margin: 5px;position: static;">
                              <div class="btn btn-white btn-sm" style="color:#f44336;"><i class="material-icons">delete</i> </div>
                            </div>
                            <div class="card-button-wrapper" style="float: right;margin: 5px;position: static;">
                              <div class="btn btn-white btn-sm" style="color:#2196F3;"><i class="material-icons">create</i> </div>
                            </div>
                            <p style="margin-bottom: 5px;">Lesson Material: <span style="font-weight: bold;">'.$lqr['mnum'].'</span></p>
                            <p style="margin-bottom: 5px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                            <hr>
                            <p style="margin-bottom: 5px;">'.$mr['description'].'</p>
                            <nav class="center">
                              <img  src="assets/materials/'.$mr['path'].'" style="width:50%;background-size:100%;">
                            </nav>
                          </div>';
              }
              else if($mtypex == 'g'){
                $mq = mysqli_query($link, "SELECT * FROM games WHERE gid='$midx' ");
                $mr = mysqli_fetch_assoc($mq);
                $lezz .= '<div class="card col-lg-12" style="margin-top:20px;">
                            <div class="card-button-wrapper" style="float: right;margin: 5px;position: static;">
                              <div class="btn btn-white btn-sm" style="color:#f44336;"><i class="material-icons">delete</i> </div>
                            </div>
                            <div class="card-button-wrapper" style="float: right;margin: 5px;position: static;">
                              <div class="btn btn-white btn-sm" style="color:#2196F3;"><i class="material-icons">create</i> </div>
                            </div>
                            <p style="margin-bottom: 5px;">Lesson Material: <span style="font-weight: bold;">'.$lqr['mnum'].'</span></p>
                            <p style="margin-bottom: 5px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                            <hr>
                            <p style="margin-bottom: 5px;">'.$mr['description'].'</p>
                            <nav class="center">
                              <object  data="assets/materials/'.$mr['path'].'" width="500" height="200"></object>
                            </nav>
                          </div>';
              }
              else if($mtypex == 'q'){
                $mq = mysqli_query($link, "SELECT * FROM questions WHERE qid='$midx' ");
                $mr = mysqli_fetch_assoc($mq);
                $qqq = mysqli_query($link, "SELECT * FROM queries WHERE qid='$midx' ");
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
                    $answer = $qqr['answer'];
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
                      if($answer == $opr['options']){
                        $options .= '<p><b style="color:green;">'.$opr['options'].'</b> - Answer</p>';
                      }
                      else{
                        $options .= '<p>'.$opr['options'].'</p>';
                      }
                      
                    }

                    $queries .= '<p><b>'.$questionx.'</b>'.$qattach .'<br></p>'.$options.'<hr>';
                  }
                }

                $lezz .= '<div class="card col-lg-12" style="margin-top:20px;">
                            <div class="card-button-wrapper" style="float: right;margin: 5px;position: static;">
                              <div class="btn btn-white btn-sm" style="color:#f44336;"><i class="material-icons">delete</i> </div>
                            </div>
                            <div class="card-button-wrapper" style="float: right;margin: 5px;position: static;">
                              <div class="btn btn-white btn-sm" style="color:#2196F3;"><i class="material-icons">create</i> </div>
                            </div>
                            <div class="card-button-wrapper" style="float: right;margin: 5px;position: static;" onclick="addquestion(\''.$midx.'\')">
                              <div class="btn btn-white btn-sm" style="color:#8bc34a;"><i class="material-icons">add</i> Add Question</div>
                            </div>
                            <p style="margin-bottom: 5px;">Lesson Material: <span style="font-weight: bold;">'.$lqr['mnum'].'</span></p>
                            <p style="margin-bottom: 5px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                            <hr>
                            '.$queries.'
                          </div>';
              }

              

              
            }

          }


          $allcourses = '<ol class="breadcrumb m-b-0">
                          <li><a href="hub">Course Manager</a></li>
                          <li style="text-transform:capitalize;"><a href="hub?sub='.$sub.'">'.$subjectnm.' - '.$levelnm.'</a></li>
                          <li class="active" style="text-transform:capitalize;">Lesson '.$lesson_number.' - '.$lesson_name.'</li>
                        </ol>
                        <div class="card col-lg-12" style="margin-top:20px;">
                          <h1 class="page-heading h4">
                            '.$subjectnm.'
                              <button onclick="addlessonmat(\''.$slcidx.'\')" style="float: right;" type="button" class="btn btn-primary">
                                <i class="material-icons">add_box</i>
                                <span class="icon-text">Add Lesson Materials</span>
                              </button>
                            <br><span class="label label-primary" style="font-size:14px;">'.$levelnm.'</span>
                          </h1>
                          <p style="padding-bottom: 10px;">Lesson '.$lesson_number.' - <span style="font-weight: bold;">'.$lesson_name.'</span></p>
                        </div>
                        <div class="row">
                          '.$lezz.'
                        </div>';       
      }

      
    }
  }
  else if(isset($_GET['sub']) && (empty($_GET['sub']) == false)){
    $sub = clean($link, $_GET['sub']);
    $subcx = $sub;
    $allcourses = '';
    $subq = mysqli_query($link, "SELECT slid,sid,lid FROM subject_level WHERE slid='$sub' ");
    $subnum = mysqli_num_rows($subq);
    if($subnum == 0){
      $allcourses = '<nav class="center" style="margin-top: 100px;">
                        <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                        <p>No info found</p>
                      </nav';
    }
    else{
      $subr = mysqli_fetch_assoc($subq);
      $lidx = $subr['lid'];
      $sidx = $subr['sid'];
      $slidx = $subr['slid'];

      $subjx = get_subject($link, $sidx);
      $subjx = explode('|', $subjx);
      $subjectnm = $subjx[0];

      $levelnm = get_level($link, $lidx);
      $lessonsx = '';

      $clq = mysqli_query($link, "SELECT * FROM subject_level_curriculum WHERE slid='$slidx' ORDER BY lesson_number ASC ");
      $clnum = mysqli_num_rows($clq);
      if($clnum == 0){
        $lessonsx = '<nav class="center" style="margin-top: 100px;">
                        <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">view_list</i>
                        <p>No lessons found</p>
                      </nav>';
      }
      else{
        while ($cr = mysqli_fetch_array($clq, MYSQLI_ASSOC)) {
          $slcid = $cr['slcid'];
          $lesson_number = $cr['lesson_number'];
          $lesson_name = $cr['lesson_name'];

          $lessonsx .= '<div class="card col-lg-12" style="margin-bottom: 0;">
                          <div class="lessonzx">Lesson '.$lesson_number.' - <span>'.$lesson_name.'</span> 
                            <div class="card-button-wrapper" style="float: right;margin: 0 5px;position: static;" onclick="deletelesson(\''.$slcid.'\')">
                              <div class="btn btn-white btn-sm" style="color:#f44336;"><i class="material-icons">delete</i> </div>
                            </div>
                            <div class="card-button-wrapper" style="float: right;position: static;">
                              <a href="hub?sub='.$slidx.'&lesson='.$slcid.'" class="btn btn-white btn-sm"><i class="material-icons">add</i> </a>
                            </div></div>
                        </div>';
        }
        
      }

      $allcourses = '<ol class="breadcrumb m-b-0">
                      <li><a href="hub">Course Manager</a></li>
                      <li class="active" style="text-transform:capitalize;">'.$subjectnm.' - '.$levelnm.'</li>
                    </ol>
                      <div class="card col-lg-12" style="margin-top:20px;">
                        <h1 class="page-heading h4">
                          '.$subjectnm.'
                            <button onclick="addlesson()" style="float: right;" type="button" class="btn btn-primary">
                              <i class="material-icons">add_box</i>
                              <span class="icon-text">Add Lesson</span>
                            </button>
                          <br><span class="label label-primary" style="font-size:14px;">'.$levelnm.'</span>
                        </h1>
                        
                      </div>
                      '.$lessonsx.'';
    }
    
  }
  else{
    $allcourses = '';
    $q = mysqli_query($link, "SELECT * FROM subject_level WHERE 1");
    $qnum = mysqli_num_rows($q);
    if($qnum == 0){
      $allcourses = '<nav class="center" style="margin-top: 100px;">
                        <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">library_books</i>
                        <p>No Course(s)</p>
                        <button onclick="addsubject()" type="button" class="btn btn-primary">
                          <i class="material-icons">add_box</i>
                          <span class="icon-text">Add Course</span>
                        </button>
                      </nav>';
    } 
    else{
      while ($qr = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
          $lidx = $qr['lid'];
          $sidx = $qr['sid'];
          $slidz = $qr['slid'];

          $subjx = get_subject($link, $sidx);
          $subjx = explode('|', $subjx);
          $subjectnm = $subjx[0];
          $subicon = $subjx[1];

          $levelnm = get_level($link, $lidx);
          $allcourses .= '<div class="card">
                            <div class="card-button-wrapper">
                              <a href="hub?sub='.$slidz.'" class="btn btn-white btn-sm"><i class="material-icons">apps</i> </a>
                            </div>
                            <div class="card-header bg-white">
                              <div class="media">
                                <div class="media-left">
                                  <img src="assets/images/'.$subicon.'" alt="" class="img-rounded" width="60">
                                </div>
                                <div class="media-body media-middle">
                                  <h4 class="card-title"><a href="hub?sub='.$slidz.'">'.$subjectnm.'</a></h4>
                                  <span class="label label-primary">'.$levelnm.'</span>
                                </div>
                              </div>
                            </div>
                          </div>';
      }
      $allcourses = '<h1 class="page-heading h2">Manage Courses
                        <button onclick="addsubjectlevel()" style="float: right;margin:0 5px" type="button" class="btn btn-success">
                          <i class="material-icons">add_box</i>
                          <span class="icon-text">Create Course Level</span>
                        </button>
                        <button onclick="addsubject()" style="float: right;" type="button" class="btn btn-primary">
                          <i class="material-icons">add_box</i>
                          <span class="icon-text">Add Course</span>
                        </button>
                      </h1>
                      <div class="card-columns">
                        '.$allcourses.'
                      </div>';
      
    }
  }

  $wards = '';

  if(isset($_POST['erollstudent'])){
    $studenten = clean($link, $_GET['s']);

    $subcount = 0;
    foreach($_POST['subject'] as $subject) {
        $subject = clean($link, $subject);

        if(empty($subject) == false){
            $subcount++;
        }   
    }

    if($subcount == 0){
      $error++;
    }

    if(empty($studenten) == true){
      $error++;
    }

    if($error == 0){
      $aerror=$gadded= 0;
      foreach($_POST['subject'] as $subject) {
          $subject = clean($link, $subject);
          $enchkq = mysqli_query($link, "SELECT sslid FROM student_subject_level WHERE stid='$studenten' AND slid='$subject' ");
          $encknum = mysqli_num_rows($enchkq);
          if($encknum == 0){
            $einq = mysqli_query($link, "INSERT INTO student_subject_level(stid,slid,added) VALUES('$studenten','$subject',now()) ");
            if($einq){
              $gadded++;
            }
            else{
              $aerror++;
            }
          }
          else{
              $aerror++;
            }
             
      }
      if($gadded > 0){
          $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "All done",
                                      text: "Student has been erolled for '.$gadded.' course(s)",
                                      type: "success",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
      }
      else{
        $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "Failed to enroll Student for '.$aerror.' course(s)<br>Cause: the student has enrolled for the course already.",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
      }
    }
    else{
      $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "'.$errormesg.'",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
    }

  }

  if(isset($_POST['addstudent'])){
    $user = '2';
    $fname = clean($link, $_POST['fname']);
    $lname = clean($link, $_POST['lname']);
    $mname = clean($link, $_POST['mname']);
    $dob = clean($link, $_POST['dob']);
    $gender = clean($link, $_POST['gender']);
    $email = clean($link, $_POST['email']);
    $slevel = clean($link, $_POST['level']);

    if(empty($user) == true){
      $error++;
    }
    if(empty($slevel) == true){
      $error++;
    }
    if(empty($fname) == true){
      $error++;
    }
    if(empty($lname) == true){
      $error++;
    }
    if(empty($dob) == true){
      $error++;
    }
    if(empty($gender) == true){
      $error++;
    }
    if(empty($email) == true){
        $error++;
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error++;
    }
    else{
        $chkmq = mysqli_query($link, "SELECT uid FROM appuser WHERE email='$email' ");
        $emnum = mysqli_num_rows($chkmq);
        if($emnum > 0) {
            $error++;
            $errormesg .= 'Email already exits<br>';
        }   
    }


    if($error == 0){
      $dob = date( "Y-m-d", strtotime(str_replace('/', '-', $dob)));
      if(empty($fname) == true){
        $mname = NULL;
      }

        $insq = mysqli_query($link, "INSERT INTO student(fname,lname,mname,gender,inst_id,lid,dob,added) VALUES('$fname','$lname','$mname','$gender','$uidx','$slevel','$dob',now())");


      if($insq){
        $uid = mysqli_insert_id($link);
        $rawpassword = 'CC'.$fname.rand(1000,9999);
        $password = md5($rawpassword);
        $ainsq = mysqli_query($link, "INSERT INTO appuser(uid,email,password,rlid) VALUES('$uid','$email','$password','$user')");
        if($ainsq){

          $to = $email;
          $subject = 'Class Cloud |New Student';

          $message = '<!DOCTYPE html>
                      <html>
                        <head>
                          <meta name="viewport" content="width=device-width">
                          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                          <link href="http://fonts.googleapis.com/css?family=Raleway:400,900,600|Pacifico" rel="stylesheet" type="text/css">
                          <style type="text/css">
                          /* -------------------------------------
                              INLINED WITH https://putsmail.com/inliner
                          ------------------------------------- */
                          /* -------------------------------------
                              RESPONSIVE AND MOBILE FRIENDLY STYLES
                          ------------------------------------- */
                          @media only screen and (max-width: 620px) {
                            table[class=body] h1 {
                              font-size: 28px !important;
                              margin-bottom: 10px !important; }
                            table[class=body] p,
                            table[class=body] ul,
                            table[class=body] ol,
                            table[class=body] td,
                            table[class=body] span,
                            table[class=body] a {
                              font-size: 16px !important; }
                            table[class=body] .wrapper,
                            table[class=body] .article {
                              padding: 10px !important; }
                            table[class=body] .content {
                              padding: 0 !important; }
                            table[class=body] .container {
                              padding: 0 !important;
                              width: 100% !important; }
                            table[class=body] .main {
                              border-left-width: 0 !important;
                              border-radius: 0 !important;
                              border-right-width: 0 !important; }
                            table[class=body] .btn table {
                              width: 100% !important; }
                            table[class=body] .btn a {
                              width: 100% !important; }
                            table[class=body] .img-responsive {
                              height: auto !important;
                              max-width: 100% !important;
                              width: auto !important; }}
                          /* -------------------------------------
                              PRESERVE THESE STYLES IN THE HEAD
                          ------------------------------------- */
                          @media all {
                            .ExternalClass {
                              width: 100%; }
                            .ExternalClass,
                            .ExternalClass p,
                            .ExternalClass span,
                            .ExternalClass font,
                            .ExternalClass td,
                            .ExternalClass div {
                              line-height: 100%; }
                            .apple-link a {
                              color: inherit !important;
                              font-family: inherit !important;
                              font-size: inherit !important;
                              font-weight: inherit !important;
                              line-height: inherit !important;
                              text-decoration: none !important; }
                            .btn-primary table td:hover {
                              background-color: #34495e !important; }
                            .btn-primary a:hover {
                              background-color: #34495e !important;
                              border-color: #34495e !important; } }
                          </style>
                        </head>
                        <body class="" style="background-color:#f6f6f6;font-family:sans-serif;-webkit-font-smoothing:antialiased;font-size:14px;line-height:1.4;margin:0;padding:0;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
                          <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;background-color:#f6f6f6;width:100%;">
                            <tr>
                              <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td>
                              <td class="container" style="font-family:sans-serif;font-size:14px;vertical-align:top;display:block;max-width:580px;padding:10px;width:580px;Margin:0 auto !important;">
                                <div class="content" style="box-sizing:border-box;display:block;Margin:0 auto;max-width:580px;padding:10px;">
                                  <!-- START CENTERED WHITE CONTAINER -->
                                  <span class="preheader" style="color:transparent;display:none;height:0;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;visibility:hidden;width:0;">Class_Cloud | New student details</span>
                                  <table class="main" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#fff;border-radius:3px;width:100%;">
                                    <!-- START MAIN CONTENT AREA -->
                                    <tr>
                                      <td class="wrapper" style="font-family:sans-serif;font-size:14px;vertical-align:top;box-sizing:border-box;padding:20px;">
                                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;">
                                          <tr>
                                            <td style="width:100%;height:50px;background-color:#2196F3 !important;margin:10px 0;float:left;">
                                              <img src="assets/images/ccwhite.png" height="40px" width="40px;" style="float:left;margin:5px;">
                                              <p style="color:#fff;font-size: 17px;float: left;margin: 14px 0;font-family: Roboto, Helvetica Neue, Arial, Helvetica, sans-serif;">Class_Cloud</p>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">
                                              <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">Hi <span style="text-transform:capitalize;">'.$adfname.'</span>,</p>
                                              <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">Here is the login details of the student you added.</p>
                                              <p>Email: <span style="font-size:20px;color:#333;font-weight:bold">'.$email.'</span></p>
                                              <p>Passowrd: <span style="font-size:20px;color:#333;font-weight:bold">'.$rawpassword.'</span></p>
                                              <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;box-sizing:border-box;width:100%;">
                                              </table>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                    <!-- END MAIN CONTENT AREA -->
                                  </table>
                                  <!-- END CENTERED WHITE CONTAINER -->
                                </div>
                              </td>
                              <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td>
                            </tr>
                          </table>
                        </body>
                      </html>';

          $headers = "From: Class_Cloud.com <info@class_cloud.com>\r\n";
          $headers .= "X-Mailer: PHP/" . phpversion();
          $headers .= "MIME-Version: 1.0\n";
          $headers .= "Return-Path: info@class_cloud.com\r\n";
          $headers .= "Content-type: text/html; charset=iso-8859-1 \r\n";

          

          $sendmail = mail($to, $subject, $message, $headers);
          if($sendmail){
            $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Student Added!",
                                      text: "Student has been added successfully",
                                      type: "success",
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: false
                                  });
                          </script>';
          }
          else{
            $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "Error sending email, but the student has been addded with password: '.$rawpassword.'",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
          }


        }
      }

      
    }
    else{
      $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "'.$errormesg.'",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
    }
  }

  if((isset($_GET['s'])) && (empty($_GET['s']) == false)){
    $stid = clean($link, $_GET['s']);
    $stq = mysqli_query($link, "SELECT * FROM student WHERE stid='$stid'");
    $scount = mysqli_num_rows($stq);
    if($scount == 0){
      $wards = '<nav class="center" style="margin-top: 100px;">
                  <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                  <p>No Student found</p>
                </nav>';
    }
    else{
      $studr = mysqli_fetch_assoc($stq);
      $fullname = $studr['lname'].' '.$studr['mname'].' '.$studr['fname'];
      $lvid = $studr['lid'];
      $level = get_level($link, $lvid);

      if(empty($studr['avatar'] == true)){
          $stavatar = 'default.png';
      }
      else{
          $stavatar = $studr['avatar'];
      }
      $ensubs = '';
      $getstsub = mysqli_query($link, "SELECT slid FROM student_subject_level WHERE stid='$stid' ");
      $gsubnum =mysqli_num_rows($getstsub);
      if($gsubnum == 0){
        $ensubs = '<div class="center" style="margin-top: 50px;color: #9e9e9e;">
                      <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                      <p>No enrolled course(s)</p>
                    </div>';
      }
      else{
        while ($gr = mysqli_fetch_array($getstsub, MYSQLI_ASSOC)) {
          $gradez = '';
          $slidz = $gr['slid'];
          $suballq = mysqli_query($link, "SELECT sid,lid FROM subject_level WHERE slid='$slidz' ");
          $subr = mysqli_fetch_assoc($suballq);
          $sidz = $subr['sid'];
          $lidz = $subr['lid'];

          $levelnm = get_level($link, $lidz);
          $subjx = get_subject($link, $sidz);
          $subjx = explode('|', $subjx);
          $subjectnm = $subjx[0];
          $subicon = $subjx[1];

          $stprog = mysqli_query($link, "SELECT progress FROM student_progress WHERE stid='$stid' AND slcid IN (SELECT slcid FROM subject_level_curriculum WHERE slid='$slidz') ");
          $stpnum = mysqli_num_rows($stprog);
          if($stpnum == 0){
            $progressx = 0;
          }
          else{
            $spr = mysqli_fetch_assoc($stprog);
            $progressx = $spr['progress'];
          }

          $gradz = grade_student($link, $stid, $slidz);
          if($gradz != '0'){
            $gradez = '<div class="media-right">
                          <div class="gradebx">'.$gradz.'</div>
                        </div>';
          }


          $ensubs .='<div class="card">
                      <div class="card-button-wrapper" style="bottom: 1.4rem;top:4.5rem;">
                        <a onclick="progressview(\''.$stid.'\',\''.$slidz.'\')" class="btn btn-white btn-sm"><i class="material-icons">select_all</i> </a>
                      </div>
                      <div class="card-header bg-white">
                        <div class="media">
                          <div class="media-left">
                            <img src="assets/images/'.$subicon.'" alt="" class="img-rounded" width="60">
                          </div>
                          <div class="media-body media-middle">
                            <h4 class="card-title"><a href="#">'.$subjectnm.'</a></h4>
                            <span class="label label-primary">'.$levelnm.'</span>
                          </div>
                          '.$gradez.'
                        </div>
                        <p style="margin: 5px 0;color:#999;">Completed: '.$progressx.'%</p>
                        <progress class="progress progress-primary progress-striped m-b-0" value="'.$progressx.'" max="100">'.$progressx.'%</progress>
                      </div>
                    </div>';
        }
        $ensubs = '<div class="card-columns">
                    '.$ensubs.'
                  </div>';
      }

      $wards = '<ol class="breadcrumb m-b-0">
                      <li class="admin-students-menu" onclick="goBack()">
        <a href="javascript:void(0);">Your Students</a>
    </li>
                  <li class="active" style="text-transform:capitalize;">'.$fullname.'</li>
                </ol>
                <div class="center">
                  <p>
                    <a href="#">
                      <img src="assets/avatar/'.$stavatar.'" alt="" width="80" class="img-circle">
                    </a>
                  </p>
                  <h1 class="h3 m-b-0" style="text-transform:capitalize;">'.$fullname.'</h1>
                  <p class="lead text-muted m-b-0">'.$level.'</p>
                  <p><span class="label label-default">STUDENT</span></p>
                  <button onclick="enroll(\''.$stid.'\')" type="button" class="btn btn-primary">
                    <i class="material-icons">book</i>
                    <span class="icon-text">Enroll</span>
                  </button>
                </div>
                <hr>
                '.$ensubs.'';
    }

  }
  else{
    $getwards = mysqli_query($link, "SELECT * FROM student");
    $studnum = mysqli_num_rows($getwards);
    if($studnum == 0){
      $wards = '<nav class="center" style="margin-top: 100px;">
                  <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">person_outline</i>
                  <p>You have no Student(s)</p>
                  <button onclick="addward()" type="button" class="btn btn-primary">
                    <i class="material-icons">person_add</i>
                    <span class="icon-text">Add Student</span>
                  </button>
                </nav>';
    }
    else{
      $wards = '';
      while ($wr = mysqli_fetch_array($getwards, MYSQLI_ASSOC)) {
        $fname = $wr['lname'].' '.$wr['mname'].' '.$wr['fname'];
        if(empty($wr['avatar'] == true)){
            $stavatar = 'default.png';
        }
        else{
            $stavatar = $wr['avatar'];
        }

        $lvid = $wr['lid'];
        $level = get_level($link, $lvid);

        $wards .= '<div class="card">
                    <div class="card-header bg-white">
                      <div class="media">
                        <div class="media-left media-middle">
                          <img src="assets/avatar/'.$stavatar.'" alt="About '.$fname.'" width="50" class="img-circle">
                        </div>
                        <div class="media-body media-middle">
                          <h4 class="card-title"><a href="hub?s='.$wr['stid'].'">'.$fname.'</a></h4>
                          <p class="card-subtitle">'.$level.'</p>
                        </div>
                      </div>
                    </div>
                  </div>';
      }
      $wards = '<h1 class="page-heading h2">Your Students 
                  <button onclick="addward()" style="float: right;" type="button" class="btn btn-primary">
                    <i class="material-icons">person_add</i>
                    <span class="icon-text">Add Student</span>
                  </button>
                </h1>
                <div class="card-columns">
                  '.$wards.'
                </div>';
      
    }
  }

  if ($lastPartUrlResult == "s") {
    $menu = '<li class="sidebar-menu-item admin-courses-menu">
                <a class="sidebar-menu-button" href="hub">
                  <i class="sidebar-menu-icon material-icons">import_contacts</i> Course Manager
                </a>
              </li>
              <li class="sidebar-menu-item admin-students-menu active" style="cursor: pointer;">
                <a class="sidebar-menu-button">
                  <i class="sidebar-menu-icon material-icons">people</i> Students
                </a>
              </li>';
  } else {
    $menu = '<li class="sidebar-menu-item admin-courses-menu active">
                <a class="sidebar-menu-button" href="hub">
                  <i class="sidebar-menu-icon material-icons">import_contacts</i> Course Manager
                </a>
              </li>
              <li class="sidebar-menu-item admin-students-menu" style="cursor: pointer;">
                <a class="sidebar-menu-button">
                  <i class="sidebar-menu-icon material-icons">people</i> Students
                </a>
              </li>';
  }

              if ($lastPartUrlResult == "s") {
                $dataresult = '
              <div class="container-fluid admin-students-page">
                '.$wards.'
              </div>';
              } else if ($lastPartUrlResult == "sub") {
                $dataresult = '<div class="container-fluid admin-courses-page">
                '.$allcourses.'
              </div>';
              }else {
                $dataresult = '<div class="container-fluid admin-courses-page">
                '.$allcourses.'
              </div>
              <div class="container-fluid admin-students-page cc-hide">
                '.$wards.'
              </div>';
              }
  }
//end::admin

//begin::instructor
else if($rlid == 1){
  $wards = '';

  if(isset($_POST['erollstudent'])){
    $studenten = clean($link, $_GET['s']);

    $subcount = 0;
    foreach($_POST['subject'] as $subject) {
        $subject = clean($link, $subject);

        if(empty($subject) == false){
            $subcount++;
        }   
    }

    if($subcount == 0){
      $error++;
    }

    if(empty($studenten) == true){
      $error++;
    }

    if($error == 0){
      $aerror=$gadded= 0;
      foreach($_POST['subject'] as $subject) {
          $subject = clean($link, $subject);
          $enchkq = mysqli_query($link, "SELECT sslid FROM student_subject_level WHERE stid='$studenten' AND slid='$subject' ");
          $encknum = mysqli_num_rows($enchkq);
          if($encknum == 0){
            $einq = mysqli_query($link, "INSERT INTO student_subject_level(stid,slid,added) VALUES('$studenten','$subject',now()) ");
            if($einq){
              $gadded++;
            }
            else{
              $aerror++;
            }
          }
          else{
              $aerror++;
            }
             
      }
      if($gadded > 0){
          $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "All done",
                                      text: "Student has been erolled for '.$gadded.' course(s)",
                                      type: "success",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
      }
      else{
        $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "Failed to enroll Student for '.$aerror.' course(s)<br>Cause: the student has enrolled for the course already.",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
      }
    }
    else{
      $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "'.$errormesg.'",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
    }

  }

  if(isset($_POST['addstudent'])){
    $user = '2';
    $fname = clean($link, $_POST['fname']);
    $lname = clean($link, $_POST['lname']);
    $mname = clean($link, $_POST['mname']);
    $dob = clean($link, $_POST['dob']);
    $gender = clean($link, $_POST['gender']);
    $email = clean($link, $_POST['email']);
    $slevel = clean($link, $_POST['level']);

    if(empty($user) == true){
      $error++;
    }
    if(empty($slevel) == true){
      $error++;
    }
    if(empty($fname) == true){
      $error++;
    }
    if(empty($lname) == true){
      $error++;
    }
    if(empty($dob) == true){
      $error++;
    }
    if(empty($gender) == true){
      $error++;
    }
    if(empty($email) == true){
        $error++;
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error++;
    }
    else{
        $chkmq = mysqli_query($link, "SELECT uid FROM appuser WHERE email='$email' ");
        $emnum = mysqli_num_rows($chkmq);
        if($emnum > 0) {
            $error++;
            $errormesg .= 'Email already exits<br>';
        }   
    }


    if($error == 0){
      $dob = date( "Y-m-d", strtotime(str_replace('/', '-', $dob)));
      if(empty($fname) == true){
        $mname = NULL;
      }

        $insq = mysqli_query($link, "INSERT INTO student(fname,lname,mname,gender,inst_id,lid,dob,added) VALUES('$fname','$lname','$mname','$gender','$uidx','$slevel','$dob',now())");


      if($insq){
        $uid = mysqli_insert_id($link);
        $rawpassword = 'CC'.$fname.rand(1000,9999);
        $password = md5($rawpassword);
        $ainsq = mysqli_query($link, "INSERT INTO appuser(uid,email,password,rlid) VALUES('$uid','$email','$password','$user')");
        if($ainsq){

          $to = $email;
          $subject = 'Class Cloud |New Student';

          $message = '<!DOCTYPE html>
                      <html>
                        <head>
                          <meta name="viewport" content="width=device-width">
                          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                          <link href="http://fonts.googleapis.com/css?family=Raleway:400,900,600|Pacifico" rel="stylesheet" type="text/css">
                          <style type="text/css">
                          /* -------------------------------------
                              INLINED WITH https://putsmail.com/inliner
                          ------------------------------------- */
                          /* -------------------------------------
                              RESPONSIVE AND MOBILE FRIENDLY STYLES
                          ------------------------------------- */
                          @media only screen and (max-width: 620px) {
                            table[class=body] h1 {
                              font-size: 28px !important;
                              margin-bottom: 10px !important; }
                            table[class=body] p,
                            table[class=body] ul,
                            table[class=body] ol,
                            table[class=body] td,
                            table[class=body] span,
                            table[class=body] a {
                              font-size: 16px !important; }
                            table[class=body] .wrapper,
                            table[class=body] .article {
                              padding: 10px !important; }
                            table[class=body] .content {
                              padding: 0 !important; }
                            table[class=body] .container {
                              padding: 0 !important;
                              width: 100% !important; }
                            table[class=body] .main {
                              border-left-width: 0 !important;
                              border-radius: 0 !important;
                              border-right-width: 0 !important; }
                            table[class=body] .btn table {
                              width: 100% !important; }
                            table[class=body] .btn a {
                              width: 100% !important; }
                            table[class=body] .img-responsive {
                              height: auto !important;
                              max-width: 100% !important;
                              width: auto !important; }}
                          /* -------------------------------------
                              PRESERVE THESE STYLES IN THE HEAD
                          ------------------------------------- */
                          @media all {
                            .ExternalClass {
                              width: 100%; }
                            .ExternalClass,
                            .ExternalClass p,
                            .ExternalClass span,
                            .ExternalClass font,
                            .ExternalClass td,
                            .ExternalClass div {
                              line-height: 100%; }
                            .apple-link a {
                              color: inherit !important;
                              font-family: inherit !important;
                              font-size: inherit !important;
                              font-weight: inherit !important;
                              line-height: inherit !important;
                              text-decoration: none !important; }
                            .btn-primary table td:hover {
                              background-color: #34495e !important; }
                            .btn-primary a:hover {
                              background-color: #34495e !important;
                              border-color: #34495e !important; } }
                          </style>
                        </head>
                        <body class="" style="background-color:#f6f6f6;font-family:sans-serif;-webkit-font-smoothing:antialiased;font-size:14px;line-height:1.4;margin:0;padding:0;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;">
                          <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;background-color:#f6f6f6;width:100%;">
                            <tr>
                              <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td>
                              <td class="container" style="font-family:sans-serif;font-size:14px;vertical-align:top;display:block;max-width:580px;padding:10px;width:580px;Margin:0 auto !important;">
                                <div class="content" style="box-sizing:border-box;display:block;Margin:0 auto;max-width:580px;padding:10px;">
                                  <!-- START CENTERED WHITE CONTAINER -->
                                  <span class="preheader" style="color:transparent;display:none;height:0;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;visibility:hidden;width:0;">Class_Cloud | New student details</span>
                                  <table class="main" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;background:#fff;border-radius:3px;width:100%;">
                                    <!-- START MAIN CONTENT AREA -->
                                    <tr>
                                      <td class="wrapper" style="font-family:sans-serif;font-size:14px;vertical-align:top;box-sizing:border-box;padding:20px;">
                                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;width:100%;">
                                          <tr>
                                            <td style="width:100%;height:50px;background-color:#2196F3 !important;margin:10px 0;float:left;">
                                              <img src="assets/images/ccwhite.png" height="40px" width="40px;" style="float:left;margin:5px;">
                                              <p style="color:#fff;font-size: 17px;float: left;margin: 14px 0;font-family: Roboto, Helvetica Neue, Arial, Helvetica, sans-serif;">Class_Cloud</p>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">
                                              <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">Hi <span style="text-transform:capitalize;">'.$adfname.'</span>,</p>
                                              <p style="font-family:sans-serif;font-size:14px;font-weight:normal;margin:0;Margin-bottom:15px;">Here is the login details of the student you added.</p>
                                              <p>Email: <span style="font-size:20px;color:#333;font-weight:bold">'.$email.'</span></p>
                                              <p>Passowrd: <span style="font-size:20px;color:#333;font-weight:bold">'.$rawpassword.'</span></p>
                                              <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse:separate;mso-table-lspace:0pt;mso-table-rspace:0pt;box-sizing:border-box;width:100%;">
                                              </table>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                    <!-- END MAIN CONTENT AREA -->
                                  </table>
                                  <!-- END CENTERED WHITE CONTAINER -->
                                </div>
                              </td>
                              <td style="font-family:sans-serif;font-size:14px;vertical-align:top;">&nbsp;</td>
                            </tr>
                          </table>
                        </body>
                      </html>';

          $headers = "From: Class_Cloud.com <info@class_cloud.com>\r\n";
          $headers .= "X-Mailer: PHP/" . phpversion();
          $headers .= "MIME-Version: 1.0\n";
          $headers .= "Return-Path: info@class_cloud.com\r\n";
          $headers .= "Content-type: text/html; charset=iso-8859-1 \r\n";

          

          $sendmail = mail($to, $subject, $message, $headers);
          if($sendmail){
            $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Student Added!",
                                      text: "Student has been added successfully",
                                      type: "success",
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: false
                                  });
                          </script>';
          }
          else{
            $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "Error sending email, but the student has been addded with password: '.$rawpassword.'",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
          }


        }
      }

      
    }
    else{
      $echoscript = '<script type="text/javascript">
                                  swal({
                                      title: "Error",
                                      text: "'.$errormesg.'",
                                      type: "error",
                                      html: true,
                                      showCancelButton: false,
                                      confirmButtonText: "Okay!",
                                      closeOnConfirm: true
                                  });
                          </script>';
    }
  }

  if((isset($_GET['s'])) && (empty($_GET['s']) == false)){
    $stid = clean($link, $_GET['s']);
    $stq = mysqli_query($link, "SELECT * FROM student WHERE stid='$stid' AND inst_id='$uidx' ");
    $scount = mysqli_num_rows($stq);
    if($scount == 0){
      $wards = '<nav class="center" style="margin-top: 100px;">
                  <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                  <p>No Student found</p>
                </nav>';
    }
    else{
      $studr = mysqli_fetch_assoc($stq);
      $fullname = $studr['lname'].' '.$studr['mname'].' '.$studr['fname'];
      $lvid = $studr['lid'];
      $level = get_level($link, $lvid);

      if(empty($studr['avatar'] == true)){
          $stavatar = 'default.png';
      }
      else{
          $stavatar = $studr['avatar'];
      }
      $ensubs = '';
      $getstsub = mysqli_query($link, "SELECT slid FROM student_subject_level WHERE stid='$stid' ");
      $gsubnum =mysqli_num_rows($getstsub);
      if($gsubnum == 0){
        $ensubs = '<div class="center" style="margin-top: 50px;color: #9e9e9e;">
                      <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                      <p>No enrolled course(s)</p>
                    </div>';
      }
      else{
        while ($gr = mysqli_fetch_array($getstsub, MYSQLI_ASSOC)) {
          $gradez = '';
          $slidz = $gr['slid'];
          $suballq = mysqli_query($link, "SELECT sid,lid FROM subject_level WHERE slid='$slidz' ");
          $subr = mysqli_fetch_assoc($suballq);
          $sidz = $subr['sid'];
          $lidz = $subr['lid'];

          $levelnm = get_level($link, $lidz);
          $subjx = get_subject($link, $sidz);
          $subjx = explode('|', $subjx);
          $subjectnm = $subjx[0];
          $subicon = $subjx[1];

          $stprog = mysqli_query($link, "SELECT progress FROM student_progress WHERE stid='$stid' AND slcid IN (SELECT slcid FROM subject_level_curriculum WHERE slid='$slidz') ");
          $stpnum = mysqli_num_rows($stprog);
          if($stpnum == 0){
            $progressx = 0;
          }
          else{
            $spr = mysqli_fetch_assoc($stprog);
            $progressx = $spr['progress'];
          }

          $gradz = grade_student($link, $stid, $slidz);
          if($gradz != '0'){
            $gradez = '<div class="media-right">
                          <div class="gradebx">'.$gradz.'</div>
                        </div>';
          }

          $ensubs .='<div class="card">
                      <div class="card-button-wrapper" style="bottom: 1.4rem;top:4.5rem;">
                        <a onclick="progressview(\''.$stid.'\',\''.$slidz.'\')" class="btn btn-white btn-sm"><i class="material-icons">select_all</i> </a>
                      </div>
                      <div class="card-header bg-white">
                        <div class="media">
                          <div class="media-left">
                            <img src="assets/images/'.$subicon.'" alt="" class="img-rounded" width="60">
                          </div>
                          <div class="media-body media-middle">
                            <h4 class="card-title"><a href="#">'.$subjectnm.'</a></h4>
                            <span class="label label-primary">'.$levelnm.'</span>
                          </div>
                          '.$gradez.'
                        </div>
                        <p style="margin: 5px 0;color:#999;">Completed: '.$progressx.'%</p>
                        <progress class="progress progress-primary progress-striped m-b-0" value="'.$progressx.'" max="100">'.$progressx.'%</progress>
                      </div>
                    </div>';
        }
        $ensubs = '<div class="card-columns">
                    '.$ensubs.'
                  </div>';
      }

      $wards = '<ol class="breadcrumb m-b-0">
                  <li><a href="hub">Your Students</a></li>
                  <li class="active" style="text-transform:capitalize;">'.$fullname.'</li>
                </ol>
                <div class="center">
                  <p>
                    <a href="#">
                      <img src="assets/avatar/'.$stavatar.'" alt="" width="80" class="img-circle">
                    </a>
                  </p>
                  <h1 class="h3 m-b-0" style="text-transform:capitalize;">'.$fullname.'</h1>
                  <p class="lead text-muted m-b-0">'.$level.'</p>
                  <p><span class="label label-default">STUDENT</span></p>
                  <button onclick="enroll(\''.$stid.'\')" type="button" class="btn btn-primary">
                    <i class="material-icons">book</i>
                    <span class="icon-text">Enroll</span>
                  </button>
                </div>
                <hr>
                '.$ensubs.'';
    }

    
  }
  else{
    $getwards = mysqli_query($link, "SELECT * FROM student WHERE inst_id='$uidx' ");
    $studnum = mysqli_num_rows($getwards);
    if($studnum == 0){
      $wards = '<nav class="center" style="margin-top: 100px;">
                  <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">person_outline</i>
                  <p>You have no Student(s)</p>
                  <button onclick="addward()" type="button" class="btn btn-primary">
                    <i class="material-icons">person_add</i>
                    <span class="icon-text">Add Student</span>
                  </button>
                </nav>';
    }
    else{
      $wards = '';
      while ($wr = mysqli_fetch_array($getwards, MYSQLI_ASSOC)) {
        $fname = $wr['lname'].' '.$wr['mname'].' '.$wr['fname'];
        if(empty($wr['avatar'] == true)){
            $stavatar = 'default.png';
        }
        else{
            $stavatar = $wr['avatar'];
        }

        $lvid = $wr['lid'];
        $level = get_level($link, $lvid);

        $wards .= '<div class="card">
                    <div class="card-header bg-white">
                      <div class="media">
                        <div class="media-left media-middle">
                          <img src="assets/avatar/'.$stavatar.'" alt="About '.$fname.'" width="50" class="img-circle">
                        </div>
                        <div class="media-body media-middle">
                          <h4 class="card-title"><a href="hub?s='.$wr['stid'].'">'.$fname.'</a></h4>
                          <p class="card-subtitle">'.$level.'</p>
                        </div>
                      </div>
                    </div>
                  </div>';
      }
      $wards = '<h1 class="page-heading h2">Your Students 
                  <button onclick="addward()" style="float: right;" type="button" class="btn btn-primary">
                    <i class="material-icons">person_add</i>
                    <span class="icon-text">Add Student</span>
                  </button>
                </h1>
                <div class="card-columns">
                  '.$wards.'
                </div>';
      
    }
  }

  $menu = '<li class="sidebar-menu-item active">
              <a class="sidebar-menu-button" href="hub">
                <i class="sidebar-menu-icon material-icons">people</i> Your Students
              </a>
            </li>';
  $dataresult = '<div class="container-fluid">
                  '.$wards.'
                  </div>';
}
//end::instructor

//begin::student
else{
  $gradez = '';
  $menu = '<li class="sidebar-menu-item active">
              <a class="sidebar-menu-button" href="hub">
                <i class="sidebar-menu-icon material-icons">school</i> My Courses
              </a>
            </li>
            <li class="sidebar-menu-item">
              <a class="sidebar-menu-button" href="view_subjects">
                <i class="sidebar-menu-icon material-icons">class</i> View Courses
              </a>
            </li>
            <li class="sidebar-menu-item">
              <a class="sidebar-menu-button" href="take_lesson">
                <i class="sidebar-menu-icon material-icons">import_contacts</i> Take Course
              </a>
            </li>
            <li class="sidebar-menu-item">
              <a class="sidebar-menu-button" href="take_quiz">
                <i class="sidebar-menu-icon material-icons">dvr</i> Take a Quiz
              </a>
            </li>';


    if(isset($_GET['subject_level']) && empty($_GET['subject_level']) == false){
      $slidx = clean($link, $_GET['subject_level']);
      $ensubs = '';
      $getstsub = mysqli_query($link, "SELECT * FROM student_subject_level WHERE stid='$uidx' AND slid='$slidx' ");
      $gsubnum =mysqli_num_rows($getstsub);
      if($gsubnum == 0){
        $ensubs = '<div class="center" style="margin-top: 50px;color: #999;">
                      <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                      <p>You have not enrolled for this course</p>
                      <a href="hub"><button type="button" class="btn btn-primary">
                        <i class="material-icons">home</i>
                        <span class="icon-text">Go Home</span>
                      </button></a>
                    </div>';
      }
      else{
          $gr = mysqli_fetch_assoc($getstsub);
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
          }
          else{
            $spr = mysqli_fetch_assoc($stprog);
            $progressx = $spr['progress'];
          }

          

          $scq = mysqli_query($link, "SELECT * FROM subject_level_curriculum WHERE slid='$slidx' ORDER BY lesson_number ASC ");
          $sqnum = mysqli_num_rows($scq);
          if($sqnum == 0){
            $startlesson = '';
            $lessonx = '<div class="center" style="margin-top: 50px;color: #999;">
                      <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                      <p>This course has no lessons yet...</p>
                      <a href="hub"><button type="button" class="btn btn-primary">
                        <i class="material-icons">home</i>
                        <span class="icon-text">Go Home</span>
                      </button></a>
                    </div>';
          }
          else{
            $lessonx = '';
            while ($scx = mysqli_fetch_array($scq, MYSQLI_ASSOC)) {
              $slcid = $scx['slcid'];
              $lessonnm = $scx['lesson_name'];

              $queriex = '';

              $scqx = mysqli_query($link, "SELECT * FROM questions WHERE qid IN (SELECT mid FROM lesson_structure WHERE mtype='q' AND slcid='$slcid' ) ");
              $scnum = mysqli_num_rows($scqx);
              if($scnum > 0){
                while ($qwr = mysqli_fetch_array($scqx, MYSQLI_ASSOC)) {
                  $qidz = $qwr['qid'];
                  $qtitle = $qwr['title'];
                  $qusnum = $qwr['quizqnum'];
                  $scorex = 0;
                  $totalscore = '';

                  $ssq = mysqli_query($link, "SELECT * FROM student_queries WHERE qqid IN (SELECT qqid FROM queries WHERE qid='$qidz') AND slcid='$slcid' AND stid='$uidx' ");
                  $ssqnum = mysqli_num_rows($ssq);
                  if($ssqnum > 0){
                    $qbutton = '<a href="take_quiz?quiz='.$qidz.'">
                                  <button type="button" class="btn btn-white" style="margin: 0 10px;">
                                    <i class="material-icons">settings_backup_restore</i>
                                    <span class="icon-text" style="font-size:12px;">Retake quiz</span>
                                  </button>
                                </a>
                                <a href="quiz_results?quiz='.$qidz.'">
                                  <button type="button" class="btn btn-success" style="margin: 0 10px;">
                                    <i class="material-icons">assessment</i>
                                    <span class="icon-text" style="font-size:12px;">View Results</span>
                                  </button>
                                </a>';
                    while ($ssr = mysqli_fetch_array($ssq, MYSQLI_ASSOC)) {
                      $qqidx = $ssr['qqid'];
                      $answerx = $ssr['answer'];
                      $qaq = mysqli_query($link, "SELECT answer FROM queries WHERE qqid='$qqidx' ");
                      $scx = mysqli_fetch_assoc($qaq);
                      if($answerx == $scx['answer']){
                        $scorex++;
                      }
                    }
                    $totalscore = '| Score: '.$scorex.'/'.$qusnum;
                  }
                  else{
                    $qbutton = '<a href="take_quiz?quiz='.$qidz.'">
                                  <button type="button" class="btn btn-success" style="margin: 0 10px;">
                                    <span class="icon-text" style="font-size:12px;">Take quiz</span>
                                  </button>
                                </a>';

                  }
                  $queriex .='<p style="font-size:13px;">
                                <i class="material-icons" style="font-size:16px;">help</i> Question: <b>'.$qtitle.' <span style="color:green;">'.$totalscore.'</span></b>
                                '.$qbutton.'
                              </p>';
                }
              }
              $lessonx .= '<div class="card-header bg-white" style="margin-top:10px;">
                              <h5 class="card-title"><b>'.$lessonnm.'</b></h5>
                              '.$queriex.'
                            </div>';
            }

            if($sqnum == 1){
              $slcidd = $slcid;

                $startlesson = '<p style="text-align:right;margin-top: 15px;">
                                  <a href="take_lesson?lesson='.$slcidd.'"><button type="button" class="btn btn-primary">
                                        <span class="icon-text">Start Lesson</span>
                                        <i class="material-icons">play_arrow</i>
                                      </button>
                                  </a>
                                </p>';
            }
            else{
              $sxcq = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' ORDER BY lesson_number ASC LIMIT 1");
              $sxr = mysqli_fetch_assoc($sxcq);
              $slcidd = $sxr['slcid'];

                if($progressx == 0){
                  $startlesson = '<p style="text-align:right;margin-top: 15px;">
                                    <a href="take_lesson?lesson='.$slcidd.'"><button type="button" class="btn btn-primary">
                                          <span class="icon-text">Start Lesson</span>
                                          <i class="material-icons">play_arrow</i>
                                        </button>
                                    </a>
                                  </p>';
                }
                else{
                  $scqx = mysqli_query($link, "SELECT * FROM subject_level_curriculum WHERE slid='$slidx' ");
                  $sqnumx = mysqli_num_rows($scqx);
                  if($progressx == 100){
                    $lessonxnum = ceil(($progressx/100)*$sqnumx);
                  }
                  else{
                    $lessonxnum = ceil(($progressx/100)*$sqnumx);
                    $lessonxnum++;
                  }
                  

                  $scq = mysqli_query($link, "SELECT slcid FROM subject_level_curriculum WHERE slid='$slidx' AND lesson_number='$lessonxnum'");
                  $scr = mysqli_fetch_assoc($scq);
                  $slcidd = $scr['slcid'];
                  if($progressx == 100){
                    $startlesson = '<p style="text-align:right;margin-top: 15px;">
                                    <a href="take_lesson?lesson='.$slcidd.'">
                                      <button type="button" class="btn btn-success">
                                        <span class="icon-text">Completed</span>
                                        <i class="material-icons">play_arrow</i>
                                      </button>
                                    </a>
                                  </p>';
                  }
                  else{
                    $startlesson = '<p style="text-align:right;margin-top: 15px;">
                                    <a href="take_lesson?lesson='.$slcidd.'">
                                      <button type="button" class="btn btn-primary">
                                        <span class="icon-text">Continue Lesson</span>
                                        <i class="material-icons">play_arrow</i>
                                      </button>
                                    </a>
                                  </p>';
                  }

                  
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
                        '.$startlesson.'
                        <p style="margin: 5px 0;color:#999;text-align:right;">Completed: '.$progressx.'%</p>
                        <progress class="progress progress-primary progress-striped m-b-0" value="'.$progressx.'" max="100">'.$progressx.'%</progress>
                      </div>';

      $dataresult = '<div class="container-fluid">
                    <ol class="breadcrumb">
                      <li><a href="hub">Home</a></li>
                      <li class="active">Dashboard</li>
                    </ol>

                    '.$ensubs.$lessonx.'
                  </div>';


    }
    else{
      $ensubs = '';
      $getstsub = mysqli_query($link, "SELECT slid FROM student_subject_level WHERE stid='$uidx' ");
      $gsubnum =mysqli_num_rows($getstsub);
      if($gsubnum == 0){
        $ensubs = '<div class="center" style="margin-top: 50px;color: #999;">
                      <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                      <p>You have not enrolled for any course</p>
                      <a href="view_subjects"><button type="button" class="btn btn-primary">
                        <i class="material-icons">class</i>
                        <span class="icon-text">View Courses</span>
                      </button></a>
                    </div>';
      }
      else{
        while ($gr = mysqli_fetch_array($getstsub, MYSQLI_ASSOC)) {
          $slidz = $gr['slid'];
          $suballq = mysqli_query($link, "SELECT sid,lid FROM subject_level WHERE slid='$slidz' ");
          $subr = mysqli_fetch_assoc($suballq);
          $sidz = $subr['sid'];
          $lidz = $subr['lid'];

          $levelnm = get_level($link, $lidz);
          $subjx = get_subject($link, $sidz);
          $subjx = explode('|', $subjx);
          $subjectnm = $subjx[0];
          $subicon = $subjx[1];

          $stprog = mysqli_query($link, "SELECT progress FROM student_progress WHERE stid='$uidx' AND slcid IN (SELECT slcid FROM subject_level_curriculum WHERE slid='$slidz') ");
          $stpnum = mysqli_num_rows($stprog);
          if($stpnum == 0){
            $progressx = 0;
          }
          else{
            $spr = mysqli_fetch_assoc($stprog);
            $progressx = $spr['progress'];
          }

          $gradz = grade_student($link, $uidx, $slidz);
          if($gradz != '0'){
            $gradez = '<div class="media-right">
                          <div class="gradebx">'.$gradz.'</div>
                        </div>';
          }

          $ensubs .='<div class="card-header bg-white">
                        <div class="media">
                          <div class="media-left">
                            <img src="assets/images/'.$subicon.'" alt="" class="img-rounded" width="60">
                          </div>
                          <div class="media-body media-middle">
                            <h4 class="card-title"><a href="hub?subject_level='.$slidz.'">'.$subjectnm.'</a></h4>
                            <span class="label label-primary">'.$levelnm.'</span>
                          </div>
                          '.$gradez.'
                        </div>
                        <p style="margin: 5px 0;color:#999;">Completed: '.$progressx.'%</p>
                        <progress class="progress progress-primary progress-striped m-b-0" value="'.$progressx.'" max="100">'.$progressx.'%</progress>
                      </div>';
          $gradez = '';
        }

        $ensubs = '<div class="row">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header bg-white">
                            <div class="media">
                              <div class="media-body">
                                <h4 class="card-title">My Courses</h4>
                              </div>
                            </div>
                          </div>
                          <ul class="list-group list-group-fit m-b-0">
                            '.$ensubs.'
                          </ul>
                        </div>
                      </div>
                    </div>';
    }

      $dataresult = '<div class="container-fluid">
                    <ol class="breadcrumb">
                      <li><a href="#">Home</a></li>
                      <li class="active">Dashboard</li>
                    </ol>
                    '.$ensubs.'
                  </div>';

  }
}
//end::student

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
</head>

<body class="layout-container ls-top-navbar si-l3-md-up">
<?php echo $echoscript;?>
  <!-- Navbar -->
  <nav class="navbar navbar-dark bg-primary navbar-full navbar-fixed-top">

    <!-- Toggle sidebar -->
    <button class="navbar-toggler pull-xs-left" type="button" data-toggle="sidebar" data-target="#sidebarLeft"><span class="material-icons">menu</span></button>

    <!-- Brand -->
    <a href="hub" class="navbar-brand" style="background-image:url(assets/images/ccwhite.png);width: 40px;margin: 8px;height: 40px;background-size: 100%;"></a>

    <ul class="nav navbar-nav hidden-sm-down">
    </ul>

    <!-- Menu -->
    <ul class="nav navbar-nav pull-xs-right">
      <!-- User dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link active dropdown-toggle p-a-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false">
          <span class="usernmx"><?php echo $adminnm;?></span>
          <img src="assets/avatar/<?php echo $avatar;?>" alt="Avatar" class="img-circle" width="40">
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-list" aria-labelledby="Preview">
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
            <h4 class="card-title">Course Form</h4>
            <p class="card-subtitle">Add a new course</p>
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
                <button type="submit" name="addsubject" class="btn btn-success btn-rounded btn-block">Add Course</button>
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
            <h4 class="card-title">Course Level Form</h4>
            <p class="card-subtitle">Create course for a level</p>
          </div>
          <div class="p-a-2">
            <form action="" method="POST" data-parsley-validate>
              <div class="form-group">
              <p style="color: red;" class="card-subtitle">Placeholders marked with (*) are required</p><br>
               <select class="form-control" name="subject" required>
                  <option value="" selected disabled>Select Course *</option>
                  <?php echo $subjectz;?>
                </select>
              </div>
              <div class="form-group">
               <select class="form-control" name="level" required>
                  <option value="" selected disabled>Select Level *</option>
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
  <div id="grayoverlay">
    <div class="row">
      <div class="col-sm-8 col-sm-push-1 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
        <div class="center m-a-2">
          <div class="icon-block img-circle" onclick="hideform()" style="background-color: #fff;cursor:pointer;">
            <i class="material-icons md-36 text-muted" style="color: #ff6666;font-weight: 800;">close</i>
          </div>
        </div>
        <div class="card">
          <div class="card-header bg-white center">
            <h4 class="card-title">Enroll Form</h4>
            <p class="card-subtitle">Select course(s) for student</p>
          </div>
          <div class="p-a-2">
            <form action="" method="POST" data-parsley-validate>
              <p style="margin: 5px 0;font-weight: bold;">Junior Course(s)</p>
              <hr style="margin:0px;">
              <?php echo $jhssubs;?>
              <p style="margin: 5px 0;font-weight: bold;">Senior Course(s)</p>
              <hr style="margin:0px;">
              <?php echo $shssubs;?>
              <p class="center">
                <button type="submit" name="erollstudent" class="btn btn-success btn-rounded btn-block">Eroll</button>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="overlaybxz">
    <div class="row">
      <div class="col-sm-8 col-sm-push-1 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
        <div class="center m-a-2">
          <div class="icon-block img-circle" onclick="hideform()" style="background-color: #fff;cursor:pointer;">
            <i class="material-icons md-36 text-muted" style="color: #ff6666;font-weight: 800;">close</i>
          </div>
        </div>
        <div class="card">
          <div class="card-header bg-white center">
            <h4 class="card-title">Student Form</h4>
            <p class="card-subtitle">Add a new student</p>
          </div>
          <div class="p-a-2">
            <form action="" method="POST" data-parsley-validate>
            <p style="color: red;" class="card-subtitle">Placeholders marked with (*) are required</p><br>
              <div class="form-group">
                <input type="text" class="form-control" name="fname" placeholder="First Name *" required>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" name="mname" placeholder="Middle Name">
              </div>
              <div class="form-group">
                <input type="text" class="form-control" name="lname" placeholder="Last Name *" required>
              </div>
              <div class="form-group">
                  <input class="datepicker form-control" type="text" placeholder="Date of birth *" name="dob" required>
              </div>
              <div class="form-group">
                <div class="c-inputs-stacked">
                  <div class="row">
                    <div class="col-md-3 col-xs-4 col-sm-4">
                      Gender *:
                    </div>
                    <div class="col-md-3 col-xs-4 col-sm-4" style="padding: 4px;">
                      <label class="c-input c-radio">
                        <input id="radioStacked1" name="gender" value="m" type="radio" required>
                        <span class="c-indicator"></span> Male
                      </label>
                    </div>
                    <div class="col-md-3 col-xs-4 col-sm-4" style="padding: 4px;">
                      <label class="c-input c-radio">
                        <input id="radioStacked2" name="gender" value="f" type="radio">
                        <span class="c-indicator"></span> Female
                      </label>
                    </div>
                    <div class="col-md-3 col-xs-4 col-sm-4" style="padding: 4px;">
                      <label class="c-input c-radio">
                        <input id="radioStacked2" name="gender" value="f" type="radio">
                        <span class="c-indicator"></span> Other
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <input type="email" class="form-control" name="email" data-parsley-type="email" placeholder="Email *" required>
              </div>
              <div class="form-group">
                <select class="form-control" name="level" required>
                  <option value="" selected disabled>Select Level *</option>
                  <?php echo $levels;?>
                </select>
              </div>
              <p class="center">
                <button type="submit" name="addstudent" class="btn btn-success btn-rounded btn-block">Add Student</button>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="addlessonover">
    <div class="row">
    <div style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
      <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="center m-a-2">
          <div class="icon-block img-circle" onclick="hideform()" style="background-color: #fff;cursor:pointer;">
            <i class="material-icons md-36 text-muted" style="color: #ff6666;font-weight: 800;">close</i>
          </div>
        </div>
        <div class="card">
          <div class="card-header bg-white center">
            <h4 class="card-title">Lesson Form</h4>
            <p class="card-subtitle">Add a new lesson</p>
          </div>
          <div class="p-a-2">
            <form action="" method="POST" data-parsley-validate enctype="multipart/form-data">
              <div class="form-group">
              <p style="color: red;" class="card-subtitle">Placeholders marked with (*) are required</p><br>
                <input type="text" class="form-control" name="lessontitle" placeholder="Lesson Title *" required>
              </div>
              <input type="hidden" name="subject_level" value="<?php echo $subcx;?>">
              <div class="form-group">
                <input type="number" class="form-control" name="lnumber" placeholder="Lesson Number *" required>
              </div>
              <p class="center">
                <button type="submit" name="addlesson" class="btn btn-success btn-rounded btn-block" style="width:40%; margin:5px auto;">Submit</button>
              </p>
            </form> 
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
  <div id="addlessonmatover">
    <div class="row">
    <div style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
      <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="center m-a-2">
          <div class="icon-block img-circle" onclick="hideform()" style="background-color: #fff;cursor:pointer;">
            <i class="material-icons md-36 text-muted" style="color: #ff6666;font-weight: 800;">close</i>
          </div>
        </div>
        <div class="card">
          <div class="card-header bg-white center">
            <h4 class="card-title">Lesson Material Form</h4>
            <p class="card-subtitle">Add a new lesson material</p>
          </div>
          <div class="p-a-2">
            <form action="" method="POST" data-parsley-validate enctype="multipart/form-data">
              <p style="color: red;" class="card-subtitle">Placeholders marked with (*) are required</p><br>
              <div class="form-group">
                <select class="form-control" name="mattype" id="mattype" onchange="addmatcont()" required>
                  <option value="" selected disabled>Select Material Type  *</option>
                  <option value="a">Audio Material</option>
                  <option value="d">Document Material</option>
                  <option value="g">Game Material</option>
                  <option value="i">Image Material</option>
                  <option value="q">Question Material</option>
                  <option value="v">Video Material</option>
                </select>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" name="materialtitle" placeholder="Material Title *" required>
              </div>
              <input type="hidden" name="slcid" value="<?php echo $slcidx;?>">
              <div class="form-group">
                <input type="number" class="form-control" data-parsley-min="1" name="mtnumber" placeholder="Material Number  *" required>
              </div>
              <div id="lessonstbx">
                
              </div>
              <p class="center">
                <button type="submit" name="addlessonmatx" class="btn btn-success btn-rounded btn-block" style="width:40%; margin:5px auto;">Submit</button>
              </p>
            </form> 
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
  <div id="addquestionover">
    <div class="row">
      <div class="col-sm-12 col-md-8 col-md-push-2 col-lg-8 col-lg-push-2">
        <div class="center m-a-2">
          <div class="icon-block img-circle" onclick="hideform()" style="background-color: #fff;cursor:pointer;">
            <i class="material-icons md-36 text-muted" style="color: #ff6666;font-weight: 800;">close</i>
          </div>
        </div>
        <div class="card">
          <div class="card-header bg-white center">
            <h4 class="card-title">Question Form</h4>
            <p class="card-subtitle">Add a new question</p>
          </div>
          <div class="p-a-2">
            <form action="" method="POST" data-parsley-validate enctype="multipart/form-data">
              <div class="form-group" id="xa">
                
              </div>
              <div id="questionbx">
                
              </div>
              <p class="center">
                <button type="submit" name="addquestionz" class="btn btn-success btn-rounded btn-block" style="width:40%; margin:5px auto;">Submit</button>
              </p>
            </form> 
          </div>
        </div>
      </div>
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

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Select the list item for admin-students-menu
    const studentsMenuItem = document.querySelector('.admin-students-menu');
    const coursesMenuItem = document.querySelector('.admin-courses-menu');
    
    // Select the containers
    const coursesContainer = document.querySelector('.container-fluid.admin-courses-page');
    const studentsContainer = document.querySelector('.container-fluid.admin-students-page');

    // Add a click event listener to admin-students-menu
    studentsMenuItem.addEventListener('click', function() {
      // Add cc-hide to courses container
      coursesContainer.classList.add('cc-hide');

      // Remove cc-hide from students container
      studentsContainer.classList.remove('cc-hide');

      studentsMenuItem.classList.add('active');
      coursesMenuItem.classList.remove('active');
    });
  });
          // Function to simulate the browser's back button
          function goBack() {
        window.history.back();
    }
</script>

</body>
</html>