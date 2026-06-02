<?php
// Enable comprehensive error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Turn off display in production
ini_set('log_errors', 1);
ini_set('error_log', '../assets/error.log');

// Initialize session
session_start();

// Load config
require('../assets/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Forgot Password | Windedgesoft</title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../../assets/img/favicon_io/favicon.ico">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../assets/plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.css">
</head>
<body class="hold-transition login-page">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="../assets/dist/img/logo/logo.png" alt="Windedgesoft Logo"height="50" width="50">
  </div>
  <!-- /.Preloader -->
  
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>Windedge</b>soft</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Forgot password? Here you can easily retrieve a new password.</p>

        <form method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Request New Password</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <p class="mb-1">
          <a href="login.php">Sign in</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Toastr -->
<script src="../assets/plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script src="../assets/dist/js/adminlte.min.js"></script>
<!-- Page specific script -->
<script>
  <?php if (isset($_SESSION['success'])): ?>
    toastr.success("<?= $_SESSION['success'] ?>", "Success");
    <?php unset($_SESSION['success']); ?>

  <?php elseif (isset($_SESSION['error'])): ?>
    toastr.error("<?= $_SESSION['error'] ?>", "Error");
    <?php unset($_SESSION['error']); ?>

  <?php elseif (isset($_SESSION['warning'])): ?>
    toastr.warning("<?= $_SESSION['warning'] ?>", "Warning");
    <?php unset($_SESSION['warning']); ?>

  <?php elseif (isset($_SESSION['info'])): ?>
    toastr.info("<?= $_SESSION['info'] ?>", "Info");
    <?php unset($_SESSION['info']); ?>
  <?php endif; ?>
</script>
</body>
</html>