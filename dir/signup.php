<?php
session_start();
require 'inc/connect.php';
require 'inc/clean.php';

if(isset($_SESSION['apid']) == true){
  header('location: hub');
}

$errorshow=$echoscript=$errormesg = '';
$error = 0;

if(isset($_POST['register'])){
  //begin::handle sql injection
  $user = clean($link, $_POST['user']);
  $fname = clean($link, $_POST['fname']);
  $lname = clean($link, $_POST['lname']);
  $mname = clean($link, $_POST['mname']);
  $dob = clean($link, $_POST['dob']);
  $gender = clean($link, $_POST['gender']);
  $email = clean($link, $_POST['email']);
  $password = clean($link, $_POST['password']);
  //end::handle sql injection

  //begin::validate form
  if(empty($user) == true){
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
  //check if user already exist
  else{
      $chkmq = mysqli_query($link, "SELECT uid FROM appuser WHERE email='$email' ");
      $emnum = mysqli_num_rows($chkmq);
      if($emnum != 0) {
          $error++;
          $errormesg .= 'Email already exits';
      }   
  }
  if(empty($password) == true){
      $error++;
  }
  //end::validate form

  if($error == 0){
    $dob = date( "Y-m-d", strtotime(str_replace('/', '-', $dob)));
    if(empty($fname) == true){
      $mname = NULL;
    }

    //create account for instructor
    if($user == '1'){
      $insq = mysqli_query($link, "INSERT INTO instructor(fname,lname,mname,gender,dob,added) VALUES('$fname','$lname','$mname','$gender','$dob',now())");
    }
    //create account for student
    else if($user == '2'){
      $insq = mysqli_query($link, "INSERT INTO student(fname,lname,mname,gender,dob,added) VALUES('$fname','$lname','$mname','$gender','$dob',now())");
    }

    if($insq){
      $uid = mysqli_insert_id($link);
      $password = md5($password);
      $ainsq = mysqli_query($link, "INSERT INTO appuser(uid,email,password,rlid) VALUES('$uid','$email','$password','$user')");
      if($ainsq){
        $apid = mysqli_insert_id($link);
        $_SESSION['apid'] = $apid;
        $echoscript = '<script type="text/javascript">
                                swal({
                                    title: "You have been registed!",
                                    text: "You have signed up successfully",
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonText: "Okay!",
                                    closeOnConfirm: false
                                }, function (isConfirm) {
                                    if (isConfirm) {
                                        window.location.href = "hub";
                                    }
                                });
                        </script>';
      }
    }

    
  }
  else{
    $echoscript = '<script type="text/javascript">
                                swal({
                                    title: "Error",
                                    text: "'.$errormesg.'",
                                    type: "error",
                                    showCancelButton: false,
                                    confirmButtonText: "Okay!",
                                    closeOnConfirm: true
                                });
                        </script>';
  }


}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Class_Cloud | Signup</title>

  <meta name="robots" content="noindex">
  <link rel="shortcut icon" href="../lib/images/favicon.png">

  <!-- Material Design Icons  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- Roboto Web Font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">

  <!-- Datepicker -->
  <link rel="stylesheet" href="examples/css/bootstrap-datepicker.min.css">

  <!-- App CSS -->
  <link type="text/css" href="assets/css/style.min.css" rel="stylesheet">
  <link type="text/css" href="assets/css/style.css" rel="stylesheet">

  <link rel="stylesheet" href="assets/css/parsley.css">  
  <link rel="stylesheet" type="text/css" href="assets/css/sweetalert.css">
  <script src="assets/js/sweetalert.min.js"></script>  
</head>

<body class="login">
<?php echo $echoscript;?>
  <div class="row">
    <div class="col-sm-8 col-sm-push-1 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
      <div class="center m-a-2">
        <div class="icon-block img-circle">
          <i class="material-icons md-36 text-muted">edit</i>
        </div>
      </div>
      <div class="card">
        <div class="card-header bg-white center">
          <div class="logodl">
            <a href="../home"><img src="assets/images/cc.png" width="150"></a>
          </div>
          <p class="card-subtitle">Create a new account</p>
        </div>
        <div class="p-a-2">
          <form action="" method="POST" data-parsley-validate>
            <div class="form-group">
              <select class="form-control" name="user" required>
                <option value="">*-- Sign up as --*</option>
                <option value="1">Instructor</option>
                <option value="2">Student</option>
              </select>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="fname" placeholder="First Name" required>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="mname" placeholder="Middle Name">
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="lname" placeholder="Last Name" required>
            </div>
            <div class="form-group">
                <input class="datepicker form-control" type="text" placeholder="Date of birth" name="dob" required>
            </div>
            <div class="form-group">
              <div class="c-inputs-stacked">
                <div class="row">
                  <div class="col-md-4 col-xs-4 col-sm-4">
                    Gender:
                  </div>
                  <div class="col-md-4 col-xs-4 col-sm-4" style="padding: 4px;">
                    <label class="c-input c-radio">
                      <input id="radioStacked1" name="gender" value="m" type="radio" required>
                      <span class="c-indicator"></span> Male
                    </label>
                  </div>
                  <div class="col-md-4 col-xs-4 col-sm-4" style="padding: 4px;">
                    <label class="c-input c-radio">
                      <input id="radioStacked2" name="gender" value="f" type="radio">
                      <span class="c-indicator"></span> Female
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <input type="email" class="form-control" name="email" data-parsley-type="email" placeholder="Email" required>
            </div>
            <div class="form-group">
              <input type="password" class="form-control" id="password1" name="password"  placeholder="Password" required>
            </div>
            <div class="form-group">
              <input type="password" class="form-control" data-parsley-equalto="#password1" placeholder="Confirm Password" required>
            </div>
            <div class="form-group center">
              <label class="c-input c-checkbox">
                <input type="checkbox" name="agree" checked required>
                <span class="c-indicator"></span> I agree to the <a href="#">Terms of Use</a>
              </label>
            </div>
            <p class="center">
              <button type="submit" name="register" class="btn btn-success btn-rounded btn-block">Sign Up</button>
            </p>
            <div class="center">Already signed up? <a href="login">Log in</a></div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="assets/vendor/jquery.min.js"></script>

  <!-- Bootstrap -->
  <script src="assets/vendor/tether.min.js"></script>
  <script src="assets/vendor/bootstrap.min.js"></script>

  <!-- AdminPlus -->
  <script src="assets/vendor/adminplus.js"></script>

  <!-- Vendor JS -->
  <script src="assets/vendor/bootstrap-datepicker.min.js"></script>

  <!-- Init -->
  <script src="examples/js/date-time.js"></script>

  <!-- App JS -->
  <script src="assets/js/main.min.js"></script>
  <script src="assets/js/parsley.js"></script>

</body>
</html>