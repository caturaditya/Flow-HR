<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_login();

// Ensure tables exist
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

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        $stmt = $pdo->prepare('DELETE FROM employees WHERE id = ?');
        $stmt->execute([$id]);
    }
    header('Location: employees.php');
    exit;
}

$q = trim($_GET['q'] ?? '');
$params = [];
$where = '1=1';
if ($q !== '') {
    $where = '(e.name LIKE ? OR e.email LIKE ? OR e.position LIKE ?)';
    $like = "%{$q}%";
    $params = [$like, $like, $like];
}

$sql = "SELECT e.*, d.name AS department_name FROM employees e LEFT JOIN departments d ON e.department_id = d.id WHERE {$where} ORDER BY e.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();
?>

<?php include __DIR__ . '/inc/header.php'; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Employees</h2>
    <div>
      <a href="employee_form.php" class="btn btn-primary">New Employee</a>
    </div>
  </div>

  <form class="row g-2 mb-3">
    <div class="col-auto">
      <input name="q" value="<?php echo htmlspecialchars($q); ?>" class="form-control" placeholder="Search name, email, position">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Search</button>
      <a href="employees.php" class="btn btn-link">Reset</a>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-striped table-sm">
      <thead>
        <tr><th>#</th><th>Name</th><th>Email</th><th>Department</th><th>Position</th><th>Hire Date</th><th>Salary</th><th>Action</th></tr>
      </thead>
      <tbody>
      <?php foreach ($items as $row): ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['email']); ?></td>
          <td><?php echo htmlspecialchars($row['department_name']); ?></td>
          <td><?php echo htmlspecialchars($row['position']); ?></td>
          <td><?php echo htmlspecialchars($row['hire_date']); ?></td>
          <td><?php echo $row['salary']; ?></td>
          <td>
            <a href="employee_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <form method="post" style="display:inline-block;margin:0 0 0 4px;" onsubmit="return confirm('Hapus employee ini?');">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/inc/footer.php'; ?>
