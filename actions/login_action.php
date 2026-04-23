<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$user = findUserByUsername($username);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'username' => $user['username'],
        'nome' => $user['nome'],
        'role' => $user['role'],
    ];

    header('Location: /index.php');
    exit;
}

header('Location: /login.php?error=1');
