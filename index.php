<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/auth.php';

// First-run: create users table if not exists and create default admin if no users
$pdo->exec("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$stmt = $pdo->query('SELECT COUNT(*) AS c FROM users');
$row = $stmt->fetch();
if ($row && $row['c'] == 0) {
    $hash = password_hash('admin', PASSWORD_DEFAULT);
    $pdo->prepare('INSERT INTO users (username, password, name) VALUES (?, ?, ?)')
        ->execute(['admin', $hash, 'Administrator']);
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || $password === '') {
        $err = 'Isi username dan password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, password FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $u = $stmt->fetch();
        if ($u && password_verify($password, $u['password'])) {
            session_start();
            $_SESSION['user_id'] = $u['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $err = 'Username atau password salah.';
        }
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Flow HR - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-3">Flow HR - Login</h4>
                    <?php if ($err): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" required>
                        </div>
                        <button class="btn btn-primary">Masuk</button>
                        <p class="mt-3"><small>Default admin: <code>admin</code> / <code>admin</code> (ubah setelah login)</small></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
