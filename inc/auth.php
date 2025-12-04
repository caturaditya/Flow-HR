<?php
// Simple auth helpers
if (session_status() === PHP_SESSION_NONE) session_start();

function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function current_user($pdo) {
    if (!is_logged_in()) return null;
    $stmt = $pdo->prepare('SELECT id, username, name FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}
