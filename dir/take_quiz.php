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
  $echoscript = '';
  if(isset($_POST['quizout'])){
    if(!isset($_POST['qans'])){
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
      $slcidi = clean($link, $_POST['slcidx']);
      $q = mysqli_query($link, "DELETE FROM student_queries WHERE slcid='$slcidi' AND stid='$uidx' ");

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
      if($qq){
        header("Location: hub");
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
                <i class="sidebar-menu-icon material-icons">class</i> View Courses
              </a>
            </li>
            <li class="sidebar-menu-item">
              <a class="sidebar-menu-button" href="take_lesson">
                <i class="sidebar-menu-icon material-icons">import_contacts</i> Take Course
              </a>
            </li>
            <li class="sidebar-menu-item active">
              <a class="sidebar-menu-button" href="take_quiz">
                <i class="sidebar-menu-icon material-icons">dvr</i> Take a Quiz
              </a>
            </li>';

  if(isset($_GET['quiz']) && empty($_GET['quiz']) == false){
        $qidz = clean($link, $_GET['quiz']);
        $ensubs = '';
        $getstsub = mysqli_query($link, "SELECT * FROM questions WHERE qid='$qidz' AND qid IN (SELECT mid FROM lesson_structure WHERE mtype='q' AND slcid IN (SELECT slcid FROM subject_level_curriculum WHERE slid IN (SELECT slid FROM student_subject_level WHERE stid='$uidx'))) ");
        $gsubnum =mysqli_num_rows($getstsub);
        if($gsubnum == 0){
          $ensubs = '<div class="center" style="margin-top: 50px;color: #999;">
                        <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                        <p>Sorry you can not take this quiz, You have not enrolled for this course</p>
                        <a href="hub"><button type="button" class="btn btn-primary">
                          <i class="material-icons">home</i>
                          <span class="icon-text">Go Home</span>
                        </button></a>
                      </div>';
        }
        else{
          $slq = mysqli_query($link, "SELECT slid,lesson_name,slcid FROM subject_level_curriculum WHERE slcid IN (SELECT slcid FROM lesson_structure WHERE mtype='q' AND mid='$qidz')");

            $gr = mysqli_fetch_assoc($slq);
            $slidx = $gr['slid'];
            $slcidz = $gr['slcid'];
            $lessontitle = $gr['lesson_name'];
            $suballq = mysqli_query($link, "SELECT sid,lid FROM subject_level WHERE slid='$slidx' ");
            $subr = mysqli_fetch_assoc($suballq);
            $sidz = $subr['sid'];
            $lidz = $subr['lid'];

            $levelnm = get_level($link, $lidz);
            $subjx = get_subject($link, $sidz);
            $subjx = explode('|', $subjx);
            $subjectnm = $subjx[0];
            $subicon = $subjx[1];


            $mq = mysqli_query($link, "SELECT * FROM questions WHERE qid='$qidz' ");
            $mr = mysqli_fetch_assoc($mq);
            $qtnum = $mr['quizqnum'];

            $qqq = mysqli_query($link, "SELECT * FROM queries WHERE qid='$qidz' ORDER BY RAND() LIMIT $qtnum ");
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
                                      <input name="qans['.$qqid.']" type="radio" value="'.$opr['options'].'" require>
                                      <span class="c-indicator"></span> '.$opr['options'].'
                                    </label>
                                  </div>';
                }

                $queries .= '<p><b>'.$questionx.'</b>'.$qattach .'<br></p>'.$options.'<hr>';
              }
              $queries = '<form method="POST" action="">
                          '.$queries.'
                          <input type="hidden" name="slcidx" value="'.$slcidz.'">
                          <p style="margin-bottom: 10px;float: right;">
                              <button type="submit" name="quizout" class="btn btn-success" style="float:right;">
                                <span class="icon-text">Submit</span>
                                <i class="material-icons">send</i>
                              </button>
                          </p>
                        </form>';
            }

            $lessonx = '<div class="card col-lg-12" style="margin-top:20px;">
                        <p style="margin-bottom: 5px;margin-top: 20px;">Title: <span style="font-weight: bold;">'.$mr['title'].'</span></p>
                        <hr>
                        '.$queries.'
                      </div>';

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
                        </div>'.$lessonx;

            

          }
          $dataresult = '<div class="container-fluid">
                              <ol class="breadcrumb">
                                <li><a href="#">Take Quiz</a></li>
                              </ol>

                              '.$ensubs.'
                            </div>';

  }
  else{
          $dataresult = '<div class="container-fluid">
                              <ol class="breadcrumb">
                                <li><a href="#">Take Quiz</a></li>
                              </ol>
                              <div class="center" style="margin-top: 50px;color: #999;">
                                <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                                <p>No quiz selected</p>
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
  <title>Take quiz</title>

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

    <ul class="nav navbar-nav hidden-sm-down"></ul>

    <!-- Menu -->
    <ul class="nav navbar-nav pull-xs-right">
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
               <select class="form-control" name="subject" required>
                  <option value="">*-- Course --*</option>
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