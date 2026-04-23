<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('admin');
$title = 'Campanhas';
$campaigns = campaigns();
include __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="col-12 col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3">Campanhas</h1>
        </div>
        <div class="card mb-3"><div class="card-body">
            <form class="row g-2" method="post" action="/actions/save_campaign.php">
                <div class="col-md-3"><input name="titulo" class="form-control" placeholder="Título" required></div>
                <div class="col-12 col-lg-2"><input name="canal" class="form-control" placeholder="Canal" required></div>
                <div class="col-12 col-lg-2"><input type="date" name="start_date" class="form-control" required></div>
                <div class="col-12 col-lg-2"><input type="date" name="end_date" class="form-control" required></div>
                <div class="col-md-1"><input type="number" step="0.01" name="budget" class="form-control" placeholder="Orç."></div>
                <div class="col-12 col-lg-2"><button class="btn btn-primary w-100">Criar campanha</button></div>
            </form>
        </div></div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead><tr><th>Título</th><th>Canal</th><th>Período</th><th>Orçamento</th><th>Gasto</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($campaigns as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['titulo']) ?></td>
                        <td><?= htmlspecialchars($c['canal']) ?></td>
                        <td><?= htmlspecialchars($c['start_date']) ?> até <?= htmlspecialchars($c['end_date']) ?></td>
                        <td>Kz <?= number_format((float) $c['budget'], 2, ',', '.') ?></td>
                        <td>Kz <?= number_format((float) $c['spent'], 2, ',', '.') ?></td>
                        <td><span class="badge text-bg-secondary"><?= htmlspecialchars($c['status']) ?></span></td>
                        <td><a class="btn btn-sm btn-outline-danger" href="/actions/delete_campaign.php?id=<?= (int) $c['id'] ?>">Apagar</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
