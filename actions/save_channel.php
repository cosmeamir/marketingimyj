<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('admin');

addChannel(trim($_POST['nome'] ?? ''), trim($_POST['tipo'] ?? ''), trim($_POST['status'] ?? 'Ativo'));
header('Location: /admin/dashboard.php');
