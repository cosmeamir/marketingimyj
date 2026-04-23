<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('admin');

savePost([
    'id' => (int) ($_POST['id'] ?? 0),
    'campaign_id' => (int) ($_POST['campaign_id'] ?? 0),
    'titulo' => trim($_POST['titulo'] ?? ''),
    'tipo_conteudo' => trim($_POST['tipo_conteudo'] ?? 'Post'),
    'plataforma' => trim($_POST['plataforma'] ?? ''),
    'post_date' => $_POST['post_date'] ?? date('Y-m-d'),
    'post_time' => $_POST['post_time'] ?? '00:00',
    'legenda' => trim($_POST['legenda'] ?? ''),
    'cta' => trim($_POST['cta'] ?? ''),
    'status' => trim($_POST['status'] ?? 'Planeado'),
    'creative_url' => trim($_POST['creative_url'] ?? '#'),
]);

header('Location: /admin/posts.php');
