<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('admin');

saveCampaign([
    'id' => (int) ($_POST['id'] ?? 0),
    'titulo' => trim($_POST['titulo'] ?? ''),
    'descricao' => trim($_POST['descricao'] ?? ''),
    'objetivo' => trim($_POST['objetivo'] ?? 'Awareness'),
    'canal' => trim($_POST['canal'] ?? ''),
    'start_date' => $_POST['start_date'] ?? date('Y-m-d'),
    'end_date' => $_POST['end_date'] ?? date('Y-m-d'),
    'budget' => (float) ($_POST['budget'] ?? 0),
    'spent' => (float) ($_POST['spent'] ?? 0),
    'status' => trim($_POST['status'] ?? 'Planeado'),
    'responsavel' => trim($_POST['responsavel'] ?? '—'),
]);

header('Location: /admin/campaigns.php');
