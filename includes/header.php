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
    <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/index.php">Cronograma Marketing</a>
        <div class="d-flex text-white small align-items-center gap-3">
            <?php if ($user): ?>
                <span><?= htmlspecialchars($user['nome']) ?> (<?= htmlspecialchars($user['role']) ?>)</span>
                <a href="/logout.php" class="btn btn-sm btn-outline-light">Sair</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container-fluid py-3">
