<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('admin');

addConfig(trim($_POST['tipo'] ?? ''), trim($_POST['valor'] ?? ''));
header('Location: /admin/dashboard.php');
