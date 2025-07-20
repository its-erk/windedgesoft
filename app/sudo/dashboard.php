<?php
// Enable comprehensive error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Turn off display in production
ini_set('log_errors', 1);
ini_set('error_log', '../assets/error.log');

// Initialize session and verify authentication
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sudo') {
  header('Location: ../auth/logout.php');
  exit();
}

// Load config
require('../assets/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | Windedgesoft</title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../../assets/img/favicon_io/favicon.ico">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
  <!-- pace-progress -->
  <link rel="stylesheet" href="../assets/plugins/pace-progress/themes/red/pace-theme-minimal.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../assets/plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.css">
</head>

<body class="hold-transition sidebar-mini sidebar-collapse layout-navbar-fixed layout-fixed pace-primary">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__wobble" src="../assets/dist/img/logo/logo.png" alt="Windedgesoft Logo"height="50" width="50">
    </div>
    <!-- /.Preloader -->

    <!-- Navbar -->
    <?php include ('ui/top-nav.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include('ui/side-nav.php'); ?>
    <!-- /.sidebar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <?php
              // Fetching the logged-in user's name from the linked employees table
              $userId = $_SESSION['user_id'];

              $query = mysqli_query($conn, "
                SELECT e.name 
                FROM users u
                LEFT JOIN employees e ON u.employee_id = e.id
                WHERE u.id = '$userId'
                ");

                if ($fetch = mysqli_fetch_assoc($query)) { ?>
                  <h1 class="m-0">Hi, <?php echo htmlentities($fetch['name']); } ?></h1>
              </div>
              <!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">Dashboard</li>
                </ol>
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
            	<div class="col-md-12">
                <p>Hurray...</p>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

          </div><!-- /.container-fluid -->

        </div>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <!-- Control Sidebar -->
      <?php include('ui/toolkit.php'); ?>
      <!-- /.control-sidebar -->

      <!-- Main Footer -->
      <?php include('../assets/global/ui/footer.php'); ?>
      <!-- /.Main Footer -->
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- pace-progress -->
    <script src="../assets/plugins/pace-progress/pace.min.js"></script>
    <!-- Toastr -->
    <script src="../assets/plugins/toastr/toastr.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
    <script>
  $(function () {
    // Initialize Tooltip
    $('[title]').tooltip();
  });
</script>
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
  </script>
</body>
</html>