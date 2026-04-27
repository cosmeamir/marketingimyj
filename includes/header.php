<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['user'] ?? null;
?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Cronograma de Marketing') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="/assets/img/logo-imyj.svg">
</head>
<body>
<div class="app-shell">
    <header class="topbar">
        <div class="d-flex align-items-center gap-2">
            <img src="/assets/img/logo-imyj.svg" alt="logo" class="topbar-logo">
            <strong>Cronograma Marketing</strong>
        </div>
        <div class="d-flex align-items-center gap-3 small">
            <?php if ($user): ?>
                <span><?= htmlspecialchars($user['nome']) ?> (<?= htmlspecialchars($user['role']) ?>)</span>
                <a href="/logout.php" class="btn btn-sm btn-outline-secondary">Sair</a>
            <?php endif; ?>
        </div>
    </header>
    <div class="container-fluid py-3">
