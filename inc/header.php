<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
  <a class="navbar-brand" href="dashboard.php">Flow HR</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="employees.php">Employees</a></li>
        <li class="nav-item"><a class="nav-link" href="departments.php">Departments</a></li>
        <li class="nav-item"><a class="nav-link" href="attendance.php">Attendance</a></li>
      </ul>
      <div class="d-flex">
        <?php if (!empty($_SESSION['user_id'])): ?>
          <span class="navbar-text text-light me-3"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
          <form method="post" action="logout.php">
            <button class="btn btn-outline-light btn-sm">Logout</button>
          </form>
        <?php else: ?>
          <a class="btn btn-outline-light btn-sm" href="index.php">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
