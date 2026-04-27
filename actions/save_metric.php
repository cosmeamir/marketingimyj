<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('admin');

saveMetric([
    'id' => (int) ($_POST['id'] ?? 0),
    'campaign_id' => (int) ($_POST['campaign_id'] ?? 0),
    'data_registo' => $_POST['data_registo'] ?? date('Y-m-d'),
    'plataforma' => trim($_POST['plataforma'] ?? ''),
    'impressoes' => (int) ($_POST['impressoes'] ?? 0),
    'cliques' => (int) ($_POST['cliques'] ?? 0),
    'leads' => (int) ($_POST['leads'] ?? 0),
    'conversoes' => (int) ($_POST['conversoes'] ?? 0),
    'cpc' => (float) ($_POST['cpc'] ?? 0),
    'cpm' => (float) ($_POST['cpm'] ?? 0),
    'spent' => (float) ($_POST['spent'] ?? 0),
    'resultado' => (float) ($_POST['resultado'] ?? 0),
]);

header('Location: /admin/metrics.php');
