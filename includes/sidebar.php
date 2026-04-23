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
