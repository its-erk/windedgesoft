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

$sys_user_registrar = $_SESSION['user_id'];

// Save Employee
if (isset($_POST['saveEmployee'])) {
  // Sanitize input
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $department = trim($_POST['department']);
  $job_title = trim($_POST['jobTitle']);

  // Validate required fields
  if (empty($name) || empty($email) || empty($phone) || empty($department) || empty($job_title)) {
    $_SESSION['error'] = "All required fields must be filled.";
    header("Location: ../employees.php");
    exit();
  }

  // Generate unique staff_no: e.g., RO00123
  $prefix = strtoupper(substr($department, 0, 2)); // e.g., "HR", "FI"
  $result = mysqli_query($conn, "SELECT id FROM employees ORDER BY id DESC LIMIT 1");
  $last_id = ($row = mysqli_fetch_assoc($result)) ? (int)$row['id'] + 1 : 1;
  $staff_no = $prefix . str_pad($last_id, 5, '0', STR_PAD_LEFT);

  // Prepare insert statement
  $stmt = mysqli_prepare($conn, "INSERT INTO employees (staff_no, name, email, phone, department, job_title, status, created, updated, sys_user_registrar) VALUES (?, ?, ?, ?, ?, ?, 'active', NOW(), NOW(), ?)");

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssssssi", $staff_no, $name, $email, $phone, $department, $job_title, $sys_user_registrar);

    if (mysqli_stmt_execute($stmt)) {
      $_SESSION['success'] = "Employee added successfully. Staff No: $staff_no";
      header("Location: ../employees.php");
      exit();
    }
  } else {
    $_SESSION['error'] = "Error executing query: " . mysqli_stmt_error($stmt);
    header("Location: ../employees.php");
    exit();
  }

  mysqli_stmt_close($stmt);
}
?>