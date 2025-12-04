<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$dept = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM departments WHERE id = ?');
    $stmt->execute([$id]);
    $dept = $stmt->fetch();
    if (!$dept) { header('Location: departments.php'); exit; }
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') $errors[] = 'Name wajib diisi.';
    if (empty($errors)) {
        if ($id) {
            $pdo->prepare('UPDATE departments SET name = ? WHERE id = ?')->execute([$name, $id]);
        } else {
            $pdo->prepare('INSERT INTO departments (name) VALUES (?)')->execute([$name]);
        }
        header('Location: departments.php'); exit;
    }
}
?>
<?php include __DIR__ . '/inc/header.php'; ?>
<div class="container py-4">
  <h2><?php echo $id ? 'Edit Department' : 'New Department'; ?></h2>
  <?php if ($errors): ?>
    <div class="alert alert-danger"><?php foreach ($errors as $e) echo '<div>'.htmlspecialchars($e).'</div>'; ?></div>
  <?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input name="name" class="form-control" required value="<?php echo htmlspecialchars($_POST['name'] ?? $dept['name'] ?? ''); ?>">
    </div>
    <button class="btn btn-primary">Save</button>
    <a href="departments.php" class="btn btn-link">Cancel</a>
  </form>
</div>
<?php include __DIR__ . '/inc/footer.php'; ?>
