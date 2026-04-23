<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireLogin();
$user = currentUser();
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
    <div class="col-12 col-lg-10">
        <h1 class="h3"><?= htmlspecialchars($campaign['titulo']) ?></h1>
        <p><?= htmlspecialchars($campaign['descricao']) ?></p>
        <ul>
            <li>Objetivo: <?= htmlspecialchars($campaign['objetivo']) ?></li>
            <li>Responsável: <?= htmlspecialchars($campaign['responsavel']) ?></li>
            <li>Orçamento: Kz <?= number_format((float) $campaign['budget'], 2, ',', '.') ?></li>
            <li>Gasto atual: Kz <?= number_format((float) $campaign['spent'], 2, ',', '.') ?></li>
            <li>Status: <?= htmlspecialchars($campaign['status']) ?></li>
        </ul>

        <h2 class="h5">Posts associados</h2>
        <div class="list-group">
            <?php foreach ($posts as $p): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                        <div>
                            <strong><?= htmlspecialchars($p['titulo']) ?></strong>
                            <span class="small text-muted">- <?= htmlspecialchars($p['post_date']) ?> <?= htmlspecialchars($p['post_time']) ?> (<?= htmlspecialchars($p['plataforma']) ?>)</span>
                            <div><span class="badge text-bg-secondary mt-1"><?= htmlspecialchars($p['status']) ?></span></div>
                        </div>
                        <?php if (($user['role'] ?? '') === 'cliente'): ?>
                            <div class="d-flex gap-2">
                                <form method="post" action="/actions/client_post_review.php">
                                    <input type="hidden" name="post_id" value="<?= (int) $p['id'] ?>">
                                    <input type="hidden" name="status" value="Aprovado">
                                    <button class="btn btn-sm btn-outline-success">Aprovar</button>
                                </form>
                                <form method="post" action="/actions/client_post_review.php">
                                    <input type="hidden" name="post_id" value="<?= (int) $p['id'] ?>">
                                    <input type="hidden" name="status" value="Alteração solicitada">
                                    <button class="btn btn-sm btn-outline-warning">Solicitar alteração</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
