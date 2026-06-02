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
  <title>Employees | Windedgesoft</title>

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
  <!-- Select2 -->
  <link rel="stylesheet" href="../assets/plugins/select2/css/select2.min.css">
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
              <h1 class="m-0">Employees</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Employees</li>
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
                  <h3 class="card-title">Employee <small>Details</small></h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#modal-new-employee" title="New employee">
                      <i class="fas fa-user-plus"></i>
                    </button>
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
                  <table id="tableEmployees" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Staff No.</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Job Title</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $fetchEmployees = "SELECT id, staff_no, name, email, phone, department, job_title, status, created FROM employees ORDER BY id DESC";
                      $employeeData = mysqli_query($conn, $fetchEmployees);
                      $count = 1;

                      if ($employeeData && mysqli_num_rows($employeeData) > 0):
                        while ($employee = mysqli_fetch_assoc($employeeData)):
                          ?>
                          <tr>
                            <td><?= $count++ ?></td>
                            <td><?= htmlspecialchars($employee['staff_no']) ?></td>
                            <td><?= htmlspecialchars($employee['name']) ?></td>
                            <td><?= strtoupper($employee['department']) ?></td>
                            <td><?= htmlspecialchars($employee['job_title']) ?></td>
                            <td><?= htmlspecialchars($employee['phone']) ?></td>
                            <td><?= htmlspecialchars($employee['email']) ?></td>
                            <td>
                              <?php
                              $status = strtolower($employee['status']);
                              $badgeClass = match ($status) {
                                'active' => 'badge-success',
                                'inactive' => 'badge-secondary',
                                'terminated' => 'badge-danger',
                                'on_leave' => 'badge-warning',
                                default => 'badge-light'
                              };
                              ?>
                              <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                            </td>
                            <td><?= date('d M Y, H:i', strtotime($employee['created'])) ?></td>
                            <td class="project-actions text-right">
                              <a href="employee-details.php?id=<?= $employee['id'] ?>" class="btn btn-default btn-sm" title="View">
                                <i class="fas fa-eye"></i>
                              </a>
                              <a href="edit-employee.php?id=<?= $employee['id'] ?>" class="btn btn-default btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                              </a>
                            </td>
                          </tr>
                          <?php
                        endwhile;
                      endif;
                      ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>#</th>
                        <th>Staff No.</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Job Title</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
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

        <div class="modal fade" id="modal-new-employee">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">New Employee</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <!-- form start -->
              <form action="func/employee-save.php" method="post" id="newEmployeeForm">
                <div class="modal-body">
                  <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="e.g., John Doe">
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="someone@example.com">
                  </div>
                  <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" id="phone" data-inputmask='"mask": "(254) 999 999-999"' data-mask placeholder="(254) 123 456-789">
                  </div>
                  <div class="form-group">
                    <label>Department</label>
                    <select name="department" class="form-control select2" id="department" style="width: 100%;">
                      <option selected="selected">A</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Job Title</label>
                    <select name="jobTitle" class="form-control select2" id="jobTitle" style="width: 100%;">
                      <option selected="selected">Job Title</option>
                    </select>
                  </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" name="saveEmployee" class="btn btn-primary">Save employee</button>
                </div>
              </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

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
  <!-- Select2 -->
  <script src="../assets/plugins/select2/js/select2.full.min.js"></script>
  <!-- InputMask -->
  <script src="../assets/plugins/moment/moment.min.js"></script>
  <!-- jquery-validation -->
  <script src="../assets/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="../assets/plugins/jquery-validation/additional-methods.min.js"></script>
  <script src="../assets/plugins/inputmask/jquery.inputmask.min.js"></script>
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

      //Initialize Select2 Elements
      $('.select2').select2()

      //Money Euro
      $('[data-mask]').inputmask()

      // Initialize table button controls
      $("#tableEmployees").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#tableEmployees_wrapper .col-md-6:eq(0)');

      $('#newEmployeeForm').validate({
        rules: {
          name: {
            required: true
          },
          email: {
            required: true,
            email: true,
          },
          phone: {
            required: true
          },
          department: {
            required: true
          },
          jobTitle: {
            required: true
          },
        },
        messages: {
          name: {
            required: "Please enter a name"
          },
          email: {
            required: "Please enter an email address",
            email: "Please enter a valid email address"
          },
          phone: {
            required: "Please enter a phone number"
          },
          department: {
            required: "Please select a department"
          },
          jobTitle: {
            required: "Please select a job title"
          },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
        }
      });
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