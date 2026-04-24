<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('cliente');

$postId = (int) ($_POST['post_id'] ?? 0);
$status = trim($_POST['status'] ?? '');
$comment = trim($_POST['comment'] ?? '');
$allowed = ['Aprovado', 'Alteração solicitada'];

if ($postId > 0 && in_array($status, $allowed, true)) {
    if ($status === 'Alteração solicitada' && $comment === '') {
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/client/overview.php') . '#comment-required');
        exit;
    }

    $post = findPost($postId);
    if ($post) {
        savePost([
            'id' => (int) $post['id'],
            'campaign_id' => (int) $post['campaign_id'],
            'titulo' => $post['titulo'],
            'tipo_conteudo' => $post['tipo_conteudo'],
            'plataforma' => $post['plataforma'],
            'post_date' => $post['post_date'],
            'post_time' => $post['post_time'],
            'legenda' => $post['legenda'],
            'cta' => $post['cta'],
            'status' => $status,
            'review_comment' => $status === 'Alteração solicitada' ? $comment : '',
            'creative_url' => $post['creative_url'],
        ]);
    }
}

header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/client/overview.php'));
