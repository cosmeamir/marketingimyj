<?php
$path = $_SERVER['REQUEST_URI'] ?? '';
$role = $_SESSION['user']['role'] ?? '';

$itemsByRole = [
    'admin' => [
        '/index.php' => 'Dashboard',
        '/admin/campaigns.php' => 'Campanhas',
        '/admin/posts.php' => 'Posts',
        '/admin/metrics.php' => 'Métricas',
        '/client/overview.php' => 'Visão Cliente',
        '/admin/dashboard.php' => 'Configuração',
    ],
    'design' => [
        '/index.php' => 'Dashboard',
        '/admin/posts.php' => 'Posts',
    ],
    'cliente' => [
        '/index.php' => 'Dashboard',
        '/client/overview.php' => 'Minhas campanhas',
    ],
];

$items = $itemsByRole[$role] ?? ['/index.php' => 'Dashboard'];
?>
<div class="col-12 col-lg-2 mb-3 mb-lg-0">
    <div class="list-group sticky-lg-top">
        <?php foreach ($items as $url => $label): ?>
            <a class="list-group-item list-group-item-action <?= str_contains($path, trim($url, '/')) ? 'active' : '' ?>" href="<?= $url ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="small text-muted mt-3 px-1">
        Desenvolvido por <a href="https://www.codigocosme.com" target="_blank" rel="noopener">Código Cosme</a>
    </div>
</div>
