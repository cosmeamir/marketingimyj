<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireAnyRole(['admin', 'design']);

$user = currentUser();
$isDesign = ($user['role'] ?? '') === 'design';
$postId = (int) ($_POST['id'] ?? 0);

if ($isDesign && $postId <= 0) {
    header('Location: /admin/posts.php');
    exit;
}

$creativeUrl = trim($_POST['creative_url'] ?? '');
$currentCreative = trim($_POST['current_creative_url'] ?? '');

if (!empty($_FILES['cover_image']['tmp_name']) && is_uploaded_file($_FILES['cover_image']['tmp_name'])) {
    $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    if (in_array($ext, $allowed, true)) {
        $fileName = 'post_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destinationDir = __DIR__ . '/../assets/uploads/';
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0775, true);
        }

        $destination = $destinationDir . $fileName;
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $destination)) {
            $creativeUrl = '/assets/uploads/' . $fileName;
        }
    }
}

if ($creativeUrl === '') {
    $creativeUrl = $currentCreative;
}

$status = trim($_POST['status'] ?? 'Planeado');
if ($isDesign) {
    $status = 'Pendente aprovação';
}

savePost([
    'id' => $postId,
    'campaign_id' => (int) ($_POST['campaign_id'] ?? 0),
    'titulo' => trim($_POST['titulo'] ?? ''),
    'tipo_conteudo' => trim($_POST['tipo_conteudo'] ?? 'Post'),
    'plataforma' => trim($_POST['plataforma'] ?? ''),
    'post_date' => $_POST['post_date'] ?? date('Y-m-d'),
    'post_time' => $_POST['post_time'] ?? '00:00',
    'legenda' => trim($_POST['legenda'] ?? ''),
    'cta' => trim($_POST['cta'] ?? ''),
    'status' => $status,
    'creative_url' => $creativeUrl,
]);

header('Location: /admin/posts.php');
