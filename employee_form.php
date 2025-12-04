<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_login();

// Load departments
$deps = $pdo->query('SELECT id, name FROM departments ORDER BY name')->fetchAll();

$id = (int)($_GET['id'] ?? 0);
$employee = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM employees WHERE id = ?');
    $stmt->execute([$id]);
    $employee = $stmt->fetch();
    if (!$employee) {
        header('Location: employees.php'); exit;
    }
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $department_id = $_POST['department_id'] !== '' ? (int)$_POST['department_id'] : null;
    $position = trim($_POST['position'] ?? '');
    $hire_date = $_POST['hire_date'] ?: null;
    $salary = $_POST['salary'] !== '' ? (float)$_POST['salary'] : null;

    if ($name === '') $errors[] = 'Name wajib diisi.';

    if (empty($errors)) {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE employees SET name=?, email=?, department_id=?, position=?, hire_date=?, salary=? WHERE id=?');
            $stmt->execute([$name, $email, $department_id, $position, $hire_date, $salary, $id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO employees (name, email, department_id, position, hire_date, salary) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$name, $email, $department_id, $position, $hire_date, $salary]);
        }
        header('Location: employees.php'); exit;
    }
}
?>
<?php include __DIR__ . '/inc/header.php'; ?>
<div class="container py-4">
  <h2><?php echo $id ? 'Edit Employee' : 'New Employee'; ?></h2>
  <?php if ($errors): ?>
    <div class="alert alert-danger"><ul><?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div>
  <?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input name="name" class="form-control" required value="<?php echo htmlspecialchars($_POST['name'] ?? $employee['name'] ?? ''); ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input name="email" class="form-control" value="<?php echo htmlspecialchars($_POST['email'] ?? $employee['email'] ?? ''); ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Department</label>
      <select name="department_id" class="form-select">
        <option value="">-- none --</option>
        <?php foreach ($deps as $d): ?>
          <option value="<?php echo $d['id']; ?>" <?php $v = $_POST['department_id'] ?? $employee['department_id'] ?? ''; if ($v == $d['id']) echo 'selected'; ?>><?php echo htmlspecialchars($d['name']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Position</label>
      <input name="position" class="form-control" value="<?php echo htmlspecialchars($_POST['position'] ?? $employee['position'] ?? ''); ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Hire Date</label>
      <input name="hire_date" type="date" class="form-control" value="<?php echo htmlspecialchars($_POST['hire_date'] ?? $employee['hire_date'] ?? ''); ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Salary</label>
      <input name="salary" type="number" step="0.01" class="form-control" value="<?php echo htmlspecialchars($_POST['salary'] ?? $employee['salary'] ?? ''); ?>">
    </div>
    <button class="btn btn-primary">Save</button>
    <a href="employees.php" class="btn btn-link">Cancel</a>
  </form>
</div>
<?php include __DIR__ . '/inc/footer.php'; ?>
