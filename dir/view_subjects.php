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
  $menu = '<li class="sidebar-menu-item">
              <a class="sidebar-menu-button" href="hub">
                <i class="sidebar-menu-icon material-icons">school</i> My Courses
              </a>
            </li>
            <li class="sidebar-menu-item active">
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

      $ensubs = '';
      $levelarray[0] = '';
      $getstsub = mysqli_query($link, "SELECT slid FROM subject_level WHERE lid IN (SELECT lid FROM level WHERE 1 ORDER BY level_name ASC) ");
      $gsubnum =mysqli_num_rows($getstsub);
      if($gsubnum == 0){
        $ensubs = '<div class="center" style="margin-top: 50px;color: #999;">
                      <i class="sidebar-menu-icon material-icons" style="font-size: 50px;width: 70px;">info</i>
                      <p>No course found</p>
                    </div>';
      }
      else{
        $levelcnt = 0;
        while ($gr = mysqli_fetch_array($getstsub, MYSQLI_ASSOC)) {
          $slidz = $gr['slid'];
          $suballq = mysqli_query($link, "SELECT sid,lid FROM subject_level WHERE slid='$slidz' ");
          $subr = mysqli_fetch_assoc($suballq);
          $sidz = $subr['sid'];
          $lidz = $subr['lid'];

          $levelnm = get_level($link, $lidz);
          if($levelarray[0] != $levelnm){
            $ensubs .= '<p style="padding:10px;background-color:#eee">'.$levelnm.' Courses</p>';
            $levelarray[0] = $levelnm;
          }

          $subjx = get_subject($link, $sidz);
          $subjx = explode('|', $subjx);
          $subjectnm = $subjx[0];
          $subicon = $subjx[1];

          $chq = mysqli_query($link, "SELECT slid FROM student_subject_level WHERE slid='$slidz' AND stid='$uidx' ");
          $chnum = mysqli_num_rows($chq);
          if($chnum == 0){
            $ebutton = '<button onclick="enrollx(\''.$slidz.'\')" type="button" style="float:right" class="btn btn-primary">
                          <i class="material-icons">class</i>
                          <span class="icon-text">Enroll</span>
                        </button>';
          }
          else{
            $ebutton = '<button onclick="unenroll(\''.$slidz.'\')" type="button" style="float:right" class="btn btn-danger">
                          <i class="material-icons">close</i>
                          <span class="icon-text">Unenroll</span>
                        </button>';
          }


          $ensubs .='<div class="card-header bg-white">
                        <div class="media">
                          <div class="media-left">
                            <img src="assets/images/'.$subicon.'" alt="" class="img-rounded" width="60">
                          </div>
                          <div class="media-body media-middle">
                            <h4 class="card-title"><a href="#">'.$subjectnm.'</a>
                              '.$ebutton.'
                            </h4>
                            <span class="label label-primary">'.$levelnm.'</span>
                          </div>
                        </div>
                      </div>';
        }

        $ensubs = '<div class="row">
                      <div class="col-md-12">
                        <div class="card">
                          <div class="card-header bg-white">
                            <div class="media">
                              <div class="media-body">
                                <h4 class="card-title">Courses</h4>
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
                      <li class="active">Courses</li>
                    </ol>
                    '.$ensubs.'
                  </div>';
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