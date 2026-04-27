<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('admin');

if (($_POST['confirm'] ?? '') === 'RESETAR') {
    resetSystemData();
}

header('Location: /admin/dashboard.php?reset=1');
