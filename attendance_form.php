<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$employees = $pdo->query('SELECT id, name FROM employees ORDER BY name')->fetchAll();
$rec = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM attendance WHERE id = ?');
    $stmt->execute([$id]);
    $rec = $stmt->fetch();
    if (!$rec) { header('Location: attendance.php'); exit; }
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = (int)($_POST['employee_id'] ?? 0);
    $date = $_POST['date'] ?? '';
    $status = trim($_POST['status'] ?? '');
    $note = trim($_POST['note'] ?? '');
    if (!$employee_id) $errors[] = 'Pilih employee.';
    if ($date === '') $errors[] = 'Tanggal wajib diisi.';
    if ($status === '') $errors[] = 'Status wajib diisi.';
    if (empty($errors)) {
        if ($id) {
            $pdo->prepare('UPDATE attendance SET employee_id = ?, `date` = ?, status = ?, note = ? WHERE id = ?')
                ->execute([$employee_id, $date, $status, $note, $id]);
        } else {
            $pdo->prepare('INSERT INTO attendance (employee_id, `date`, status, note) VALUES (?,?,?,?)')
                ->execute([$employee_id, $date, $status, $note]);
        }
        header('Location: attendance.php'); exit;
    }
}
?>
<?php include __DIR__ . '/inc/header.php'; ?>
<div class="container py-4">
  <h2><?php echo $id ? 'Edit Attendance' : 'New Attendance'; ?></h2>
  <?php if ($errors): ?>
    <div class="alert alert-danger"><?php foreach ($errors as $e) echo '<div>'.htmlspecialchars($e).'</div>'; ?></div>
  <?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Employee</label>
      <select name="employee_id" class="form-select">
        <option value="">-- select --</option>
        <?php foreach ($employees as $emp): ?>
          <?php $v = $_POST['employee_id'] ?? $rec['employee_id'] ?? ''; ?>
          <option value="<?php echo $emp['id']; ?>" <?php if ($v == $emp['id']) echo 'selected'; ?>><?php echo htmlspecialchars($emp['name']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Date</label>
      <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($_POST['date'] ?? $rec['date'] ?? ''); ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Status</label>
      <input name="status" class="form-control" value="<?php echo htmlspecialchars($_POST['status'] ?? $rec['status'] ?? ''); ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Note</label>
      <textarea name="note" class="form-control"><?php echo htmlspecialchars($_POST['note'] ?? $rec['note'] ?? ''); ?></textarea>
    </div>
    <button class="btn btn-primary">Save</button>
    <a href="attendance.php" class="btn btn-link">Cancel</a>
  </form>
</div>
<?php include __DIR__ . '/inc/footer.php'; ?>
