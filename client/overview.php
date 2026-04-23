<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireLogin();
$title = 'Visão do Cliente';
$campaigns = campaigns();
include __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="col-md-10">
        <h1 class="h3 mb-3">Visão do Cliente</h1>
        <div class="row g-3">
            <?php foreach ($campaigns as $c): ?>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="h5"><?= htmlspecialchars($c['titulo']) ?></h2>
                            <p class="mb-1">Canal: <strong><?= htmlspecialchars($c['canal']) ?></strong></p>
                            <p class="mb-1">Período: <?= htmlspecialchars($c['start_date']) ?> até <?= htmlspecialchars($c['end_date']) ?></p>
                            <p class="mb-1">Gasto: R$ <?= number_format((float) $c['spent'], 2, ',', '.') ?></p>
                            <p class="mb-0">Status: <span class="badge text-bg-info"><?= htmlspecialchars($c['status']) ?></span></p>
                        </div>
                        <div class="card-footer bg-white"><a href="/client/campaign-details.php?id=<?= (int) $c['id'] ?>">Ver detalhes</a></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
