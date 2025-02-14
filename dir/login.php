<?php
session_start();
require 'inc/connect.php'; //include database connection script
require 'inc/login.php'; //include login script

if(isset($_SESSION['apid']) == true){
  header('location: hub');
}
 $showerror = '';

if(isset($_POST['send'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $data = user_login($link, $email, $password);
    $result = $data;
    if(empty($result) == false){
        $_SESSION['apid'] = $result;
        header("location: hub");  
    }
    else{
        $showerror = 'style="display: block;"';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Class_Cloud | Login</title>

  <meta name="robots" content="noindex">
  <link rel="shortcut icon" href="assets/images/ccwhite.png">
  <!-- Material Design Icons  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- Roboto Web Font -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">

  <!-- App CSS -->
  <link type="text/css" href="assets/css/style.min.css" rel="stylesheet">
  <link type="text/css" href="assets/css/style.css" rel="stylesheet">
</head>

<body class="login">
  <div class="row">
    <div class="col-sm-8 col-sm-push-1 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
      <div class="center m-a-2">
        <div class="icon-block img-circle">
          <i class="material-icons md-36 text-muted">person</i>
        </div>
      </div>
      <div class="card bg-transparent">
        <div class="card-header bg-white center">
          <div class="logodl">
            <a href="../home"><img src="assets/images/cc.png" width="150"></a>
          </div>
          <p class="card-subtitle">Welcome</p>
          <div id="errorbx" <?php echo $showerror;?>>Email and Password combination is invalid</div>
        </div>
        <div class="p-a-2">
          <form action="" method="POST">
            <div class="form-group">
              <input type="email" name="email" class="form-control" placeholder="Email Address">
            </div>
            <div class="form-group">
              <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
            <div class="form-group ">
              <button type="submit" name="send" class="btn  btn-primary  btn-block btn-rounded">
                Login
              </button>
            </div>
            <div class="center">
              <a href="#">
                <small>Forgot Password?</small>
              </a>
            </div>
          </form>
        </div>
        <div class="card-footer center bg-white">
          Don't have an account? <a href="signup">Sign up</a>
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

  <!-- App JS -->
  <script src="assets/js/main.min.js"></script>

</body>
</html>