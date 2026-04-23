<?php
$path = $_SERVER['REQUEST_URI'] ?? '';
$items = [
    '/index.php' => 'Dashboard',
    '/admin/campaigns.php' => 'Campanhas',
    '/admin/posts.php' => 'Posts',
    '/admin/metrics.php' => 'Métricas',
    '/client/overview.php' => 'Visão Cliente',
    '/admin/dashboard.php' => 'Configuração',
];
?>
<div class="col-md-2">
    <div class="list-group sticky-top">
        <?php foreach ($items as $url => $label): ?>
            <a class="list-group-item list-group-item-action <?= str_contains($path, trim($url, '/')) ? 'active' : '' ?>" href="<?= $url ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
