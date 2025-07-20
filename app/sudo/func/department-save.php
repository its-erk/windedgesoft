<?php 

// Enable comprehensive error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Turn off display in production
ini_set('log_errors', 1);
ini_set('error_log', '../assets/error.log');

// Initialize session and verify authentication
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'sudo') {
  header('Location: ../../auth/logout.php');
  exit();
}

// Load config
require('../../assets/config.php');

// Save Department
if (isset($_POST['saveDepartment'])) {
  // Sanitize input
  $name = trim($_POST['name']);
  $general_manager = trim($_POST['generalManager']);

  // Validate required fields
  if (empty($name) || empty($general_manager)) {
    $_SESSION['error'] = "All required fields must be filled.";
    header("Location: ../departments.php");
    exit();
  }

  // Prepare insert statement
  $stmt = mysqli_prepare($conn, "INSERT INTO departments (name, general_manager, status) VALUES (?, ?, 'active')");

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "si", $name, $general_manager);

    if (mysqli_stmt_execute($stmt)) {
      $_SESSION['success'] = "$name department added successfully.";
      header("Location: ../departments.php");
      exit();
    }
  } else {
    $_SESSION['error'] = "Error executing query: " . mysqli_stmt_error($stmt);
    header("Location: ../departments.php");
    exit();
  }

  mysqli_stmt_close($stmt);
}
?>