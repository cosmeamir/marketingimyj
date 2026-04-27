<?php
$path = $_SERVER['REQUEST_URI'] ?? '';
$role = $_SESSION['user']['role'] ?? '';

$itemsByRole = [
    'admin' => [
        ['/index.php', 'Dashboard', 'bi-speedometer2'],
        ['/admin/campaigns.php', 'Campanhas', 'bi-megaphone'],
        ['/admin/posts.php', 'Posts', 'bi-image'],
        ['/admin/metrics.php', 'Métricas', 'bi-bar-chart'],
        ['/client/overview.php', 'Visão Cliente', 'bi-eye'],
        ['/admin/dashboard.php', 'Configuração', 'bi-gear'],
    ],
    'design' => [
        ['/index.php', 'Dashboard', 'bi-speedometer2'],
        ['/admin/posts.php', 'Posts', 'bi-image'],
    ],
    'cliente' => [
        ['/index.php', 'Dashboard', 'bi-speedometer2'],
        ['/client/overview.php', 'Minhas campanhas', 'bi-kanban'],
    ],
];

$items = $itemsByRole[$role] ?? [['/index.php', 'Dashboard', 'bi-speedometer2']];
?>
<div class="col-12 col-lg-2 mb-3 mb-lg-0">
    <aside class="sidebar-panel">
        <?php foreach ($items as [$url, $label, $icon]): ?>
            <a class="side-link <?= str_contains($path, trim($url, '/')) ? 'active' : '' ?>" href="<?= $url ?>">
                <i class="bi <?= $icon ?>"></i>
                <span><?= $label ?></span>
            </a>
        <?php endforeach; ?>
        <div class="sidebar-footer">
            Desenvolvido por <a href="https://www.codigocosme.com" target="_blank" rel="noopener">Código Cosme</a>
        </div>
    </aside>
</div>
