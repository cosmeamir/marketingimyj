<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('admin');

$id = (int) ($_GET['id'] ?? 0);
if ($id > 0) deleteConfig($id);
header('Location: /admin/dashboard.php');
