<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_login();

$pdo->exec("CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    `date` DATE NOT NULL,
    status VARCHAR(50) NOT NULL,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) $pdo->prepare('DELETE FROM attendance WHERE id = ?')->execute([$id]);
    header('Location: attendance.php'); exit;
}

$q = trim($_GET['q'] ?? '');
$employee_id = (int)($_GET['employee_id'] ?? 0);
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

$where = [];
$params = [];
if ($q !== '') {
    $where[] = '(e.name LIKE ? OR a.status LIKE ?)';
    $like = "%{$q}%";
    $params[] = $like; $params[] = $like;
}
if ($employee_id) { $where[] = 'a.employee_id = ?'; $params[] = $employee_id; }
if ($from) { $where[] = 'a.date >= ?'; $params[] = $from; }
if ($to) { $where[] = 'a.date <= ?'; $params[] = $to; }
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT a.*, e.name AS employee_name FROM attendance a JOIN employees e ON a.employee_id = e.id {$where_sql} ORDER BY a.date DESC, a.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();
$employees = $pdo->query('SELECT id, name FROM employees ORDER BY name')->fetchAll();
?>
<?php include __DIR__ . '/inc/header.php'; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Attendance</h2>
    <a href="attendance_form.php" class="btn btn-primary">New Record</a>
  </div>
  <form class="row g-2 mb-3">
    <div class="col-md-3">
      <input name="q" value="<?php echo htmlspecialchars($q); ?>" class="form-control" placeholder="Search name or status">
    </div>
    <div class="col-md-3">
      <select name="employee_id" class="form-select">
        <option value="">-- all employees --</option>
        <?php foreach ($employees as $emp): ?>
          <option value="<?php echo $emp['id']; ?>" <?php if ($employee_id == $emp['id']) echo 'selected'; ?>><?php echo htmlspecialchars($emp['name']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2"><input type="date" name="from" value="<?php echo htmlspecialchars($from); ?>" class="form-control"></div>
    <div class="col-md-2"><input type="date" name="to" value="<?php echo htmlspecialchars($to); ?>" class="form-control"></div>
    <div class="col-md-2">
      <button class="btn btn-outline-secondary">Filter</button>
      <a href="attendance.php" class="btn btn-link">Reset</a>
    </div>
  </form>

  <table class="table table-striped table-sm">
    <thead><tr><th>#</th><th>Date</th><th>Employee</th><th>Status</th><th>Note</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach ($items as $row): ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['date']); ?></td>
          <td><?php echo htmlspecialchars($row['employee_name']); ?></td>
          <td><?php echo htmlspecialchars($row['status']); ?></td>
          <td><?php echo htmlspecialchars($row['note']); ?></td>
          <td>
            <a href="attendance_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <form method="post" style="display:inline-block;margin-left:6px;" onsubmit="return confirm('Hapus record absensi?');">
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
<?php include __DIR__ . '/inc/footer.php'; ?>
