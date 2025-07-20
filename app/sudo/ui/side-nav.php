<?php
if (session_status() == PHP_SESSION_NONE) session_start();
$userId = $_SESSION['user_id'] ?? null;
$profileName = 'User';

if ($userId) {
  $userQuery = mysqli_query($conn, "
    SELECT e.name 
    FROM users u 
    JOIN employees e ON u.employee_id = e.id 
    WHERE u.id = '$userId'
    ");
  if ($data = mysqli_fetch_assoc($userQuery)) {
    $profileName = htmlentities($data['name']);
  }
}
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="dashboard.php" class="brand-link">
    <img src="../assets/dist/img/logo/logo_icon.png" alt="" class="brand-image" style="opacity: .8">
    <span class="brand-text font-weight-light"><b>Windedgesoft</b></span>
  </a>

  <div class="sidebar">
    <!-- User Info -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="../assets/dist/img/avatars/default_avatar.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="profile.php" class="d-block"><?= $profileName ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="dashboard.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Employees -->
        <li class="nav-item">
          <a href="employees.php" class="nav-link">
            <i class="nav-icon fas fas fa-users"></i>
            <p>Employees</p>
          </a>
        </li>

        <!-- Company Tools -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-building-columns"></i>
            <p>Company<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="departments.php" class="nav-link">
                <i class="nav-icon fas fa-building-flag"></i>
                <p>Departments</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="settings.php" class="nav-link">
                <i class="fas fa-sliders-h nav-icon"></i>
                <p>Settings</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Admin Tools -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cogs"></i>
            <p>System<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="users.php" class="nav-link">
                <i class="nav-icon fas fa-fingerprint"></i>
                <p>Users</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="settings.php" class="nav-link">
                <i class="fas fa-sliders-h nav-icon"></i>
                <p>Settings</p>
              </a>
            </li>
          </ul>
        </li>
        
      </ul>
    </nav>
  </div>
</aside>