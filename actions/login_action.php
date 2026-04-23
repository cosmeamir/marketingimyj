<?php
require_once __DIR__ . '/../includes/auth.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

$users = [
    'codigocosme' => ['password' => 'CC.2026', 'role' => 'admin', 'nome' => 'Administrador'],
    'cliente' => ['password' => 'CC.2026', 'role' => 'cliente', 'nome' => 'Cliente'],
];

if (isset($users[$username]) && $users[$username]['password'] === $password) {
    $_SESSION['user'] = [
        'username' => $username,
        'nome' => $users[$username]['nome'],
        'role' => $users[$username]['role'],
    ];

    header('Location: /index.php');
    exit;
}

header('Location: /login.php?error=1');
