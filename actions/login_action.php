<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

try {
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
    exit;
} catch (Throwable $e) {
    // Fallback de emergência para evitar HTTP 500 se a BD não estiver configurada.
    $fallbackUsers = [
        'codigocosme' => ['password' => 'CC.2026', 'role' => 'admin', 'nome' => 'Administrador'],
        'imyj' => ['password' => 'IMYJ.2026', 'role' => 'cliente', 'nome' => 'Cliente IMYJ'],
    ];

    if (isset($fallbackUsers[$username]) && $fallbackUsers[$username]['password'] === $password) {
        $_SESSION['user'] = [
            'id' => 0,
            'username' => $username,
            'nome' => $fallbackUsers[$username]['nome'],
            'role' => $fallbackUsers[$username]['role'],
        ];

        header('Location: /index.php?db=offline');
        exit;
    }

    header('Location: /login.php?error=db');
    exit;
}
