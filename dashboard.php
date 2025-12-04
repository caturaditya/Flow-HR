<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_login();
$user = current_user($pdo);

// Create essential tables if missing
$pdo->exec("CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
$pdo->exec("CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    department_id INT NULL,
    position VARCHAR(255),
    hire_date DATE NULL,
    salary DECIMAL(12,2) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
$pdo->exec("CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    `date` DATE NOT NULL,
    status VARCHAR(50) NOT NULL,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

// Counts
$counts = [];
foreach (['employees'=>'SELECT COUNT(*) FROM employees','departments'=>'SELECT COUNT(*) FROM departments','attendance'=>'SELECT COUNT(*) FROM attendance'] as $k=>$q) {
    $counts[$k] = (int)$pdo->query($q)->fetchColumn();
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Flow HR - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/inc/header.php'; ?>
<div class="container py-4">
    <h1>Dashboard</h1>
    <p>Selamat datang, <?php echo htmlspecialchars($user['name'] ?? $user['username']); ?>.</p>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Employees</h5>
                    <h2><?php echo $counts['employees']; ?></h2>
                    <a href="employees.php" class="btn btn-sm btn-outline-primary">Kelola Employee</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Departments</h5>
                    <h2><?php echo $counts['departments']; ?></h2>
                    <a href="departments.php" class="btn btn-sm btn-outline-primary">Kelola Department</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Attendance Records</h5>
                    <h2><?php echo $counts['attendance']; ?></h2>
                    <a href="attendance.php" class="btn btn-sm btn-outline-primary">Kelola Attendance</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/inc/footer.php'; ?>
</body>
</html>
