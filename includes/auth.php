<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool
{
    return !empty($_SESSION['user']);
}

function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

function requireRole(string $role): void
{
    requireLogin();
    if (($_SESSION['user']['role'] ?? null) !== $role) {
        header('Location: /index.php');
        exit;
    }
}

function requireAnyRole(array $roles): void
{
    requireLogin();
    if (!in_array($_SESSION['user']['role'] ?? '', $roles, true)) {
        header('Location: /index.php');
        exit;
    }
}

function logout(): void
{
    $_SESSION = [];
    session_destroy();
}
