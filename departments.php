<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_login();

$pdo->exec("CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['action'] ?? '') === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $pdo->prepare('DELETE FROM departments WHERE id = ?')->execute([$id]);
        }
        header('Location: departments.php'); exit;
    }
}

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $pdo->prepare('SELECT * FROM departments WHERE name LIKE ? ORDER BY id DESC');
    $stmt->execute(["%{$q}%"]);
} else {
    $stmt = $pdo->query('SELECT * FROM departments ORDER BY id DESC');
}
$items = $stmt->fetchAll();
?>
<?php include __DIR__ . '/inc/header.php'; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Departments</h2>
    <a href="department_form.php" class="btn btn-primary">New Dept</a>
  </div>
  <form class="row g-2 mb-3">
    <div class="col-auto">
      <input name="q" value="<?php echo htmlspecialchars($q); ?>" class="form-control" placeholder="Search departments">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Search</button>
      <a href="departments.php" class="btn btn-link">Reset</a>
    </div>
  </form>
  <table class="table table-striped table-sm">
    <thead><tr><th>#</th><th>Name</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach ($items as $row): ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td>
            <a href="department_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <form method="post" style="display:inline-block;margin-left:6px;" onsubmit="return confirm('Hapus department?');">
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
