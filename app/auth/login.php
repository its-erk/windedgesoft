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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Get form inputs
$input = trim($_POST['username']); // Could be username OR email
$password = trim($_POST['password']);

if (empty($input) || empty($password)) {
  $_SESSION['warning'] = "Username or password cannot be blank.";
  header("Location: login.php");
  exit();
}

// Prepare the SQL query to find the user by username OR email
$stmt = $conn->prepare("
  SELECT users.*, employees.name 
  FROM users 
  LEFT JOIN employees ON users.employee_id = employees.id
  WHERE (users.username = ? OR employees.email = ?)
  AND users.status = 'active'
  LIMIT 1
  ");
$stmt->bind_param("ss", $input, $input);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();

  // Verify the password
  if (password_verify($password, $user['password'])) {

  // Store user info in session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name']; // Prefer employee's name
    $_SESSION['role'] = $user['role'];

    // Update last login
    $updateStmt = $conn->prepare("UPDATE users SET last_log = NOW() WHERE id = ?");
    $updateStmt->bind_param("i", $user['id']);
    $updateStmt->execute();

    // Role-based routing
    switch ($user['role']) {
      case 'sudo':
      header("Location: ../sudo/dashboard.php");
      break;
      case 'blog_editor':
      header("Location: ../../wblog/");
      break;
      default:
      $_SESSION['error'] = "Unauthorized role.";
      header("Location: login.php");
      break;
    }
    exit();
  } else {
    $_SESSION['error'] = "Invalid password.";
    header("Location: login.php");
    exit();
  }
} else {
  $_SESSION['error'] = "User not found.";
  header("Location: login.php");
  exit();
}

$stmt->close();
$conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log in | Windedgesoft</title>

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
      <a href="#"><img src="../assets/dist/img/logo/logo.png" height="100" width="100"></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="username" id="username" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <p class="mb-1">
          <a href="forgot-password.php">I forgot my password</a>
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
