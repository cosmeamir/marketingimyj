<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireLogin();
$id = (int) ($_GET['id'] ?? 0);
$campaign = findCampaign($id);
if (!$campaign) {
    header('Location: /client/overview.php');
    exit;
}

$posts = array_filter(posts(), fn($p) => (int) $p['campaign_id'] === $id);
$title = 'Detalhe da Campanha';
include __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="col-md-10">
        <h1 class="h3"><?= htmlspecialchars($campaign['titulo']) ?></h1>
        <p><?= htmlspecialchars($campaign['descricao']) ?></p>
        <ul>
            <li>Objetivo: <?= htmlspecialchars($campaign['objetivo']) ?></li>
            <li>Responsável: <?= htmlspecialchars($campaign['responsavel']) ?></li>
            <li>Orçamento: R$ <?= number_format((float) $campaign['budget'], 2, ',', '.') ?></li>
            <li>Gasto atual: R$ <?= number_format((float) $campaign['spent'], 2, ',', '.') ?></li>
            <li>Status: <?= htmlspecialchars($campaign['status']) ?></li>
        </ul>

        <h2 class="h5">Posts associados</h2>
        <div class="list-group">
            <?php foreach ($posts as $p): ?>
                <div class="list-group-item">
                    <strong><?= htmlspecialchars($p['titulo']) ?></strong>
                    <span class="small text-muted">- <?= htmlspecialchars($p['post_date']) ?> <?= htmlspecialchars($p['post_time']) ?> (<?= htmlspecialchars($p['plataforma']) ?>)</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
