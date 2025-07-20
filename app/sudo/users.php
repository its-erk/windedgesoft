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
  <title>Users | Windedgesoft</title>

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
  <!-- DataTables -->
  <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.css">
</head>

<body class="hold-transition sidebar-mini sidebar-collapse layout-navbar-fixed layout-fixed pace-primary">
  <div class="wrapper">

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
              <h1 class="m-0">Users</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#">System</a></li>
                <li class="breadcrumb-item active">Users</li>
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
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">User <small>Details</small></h3>
                  <div class="card-tools">
                    <a type="button" class="btn btn-tool" href="#" title="New User">
                      <i class="fas fa-user-plus"></i>
                    </a>
                    <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="employees.php" data-source-selector="#card-refresh-content" data-load-on-init="false">
                      <i class="fas fa-sync-alt"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                      <i class="fas fa-expand"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="tableUsers" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Staff No.</th>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Log</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $stmt = $conn->prepare("
                        SELECT 
                        users.*, 
                        employees.name, 
                        employees.staff_no 
                        FROM 
                        users 
                        LEFT JOIN 
                        employees 
                        ON 
                        users.employee_id = employees.id
                        ");

                      $stmt->execute();
                      $fetchUsers = $stmt->get_result();

                      $count = 1;
                      while ($user = $fetchUsers->fetch_assoc()) {
                        $last_log = $user['last_log'];
                        $last_log_timestamp = date("d M Y, H:i", strtotime($last_log));
                        ?>
                        <tr>
                          <td><?php echo $count++; ?></td>
                          <td><?php echo htmlentities($user['staff_no']); ?></td>
                          <td><?php echo htmlentities($user['name']); ?></td>
                          <td><?php echo htmlentities($user['role']); ?></td>
                          <td>
                            <?php
                            $status = strtolower($user['status']);
                            $badgeClass = match ($status) {
                              'active' => 'badge-success',
                              'inactive' => 'badge-secondary',
                              default => 'badge-light'
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                          </td>
                          <td><?php echo $last_log_timestamp; ?></td>
                          <td class="project-actions text-right">
                            <a class="btn btn-default btn-sm" href="#"  title="View">
                              <i class="fas fa-eye"></i>
                            </a>
                            <a class="btn btn-default btn-sm" href="#" title="Edit">
                              <i class="fas fa-edit"></i>
                            </a>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>#</th>
                        <th>Staff No</th>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Action</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>

              </div>
              <!-- /.card -->
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
  <!-- DataTables  & Plugins -->
  <script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="../assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="../assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="../assets/plugins/jszip/jszip.min.js"></script>
  <script src="../assets/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="../assets/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="../assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="../assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="../assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../assets/dist/js/adminlte.min.js"></script>
  <!-- Page specific script -->
  <script>
    $(function () {
    // Initialize Tooltip
      $('[title]').tooltip();

    // Initialize table button controls
      $("#tableUsers").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#tableUsers_wrapper .col-md-6:eq(0)');
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
</body>
</html>