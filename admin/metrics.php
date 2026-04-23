<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('admin');
$title = 'Métricas';
$metrics = metrics();
include __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="col-12 col-lg-10">
        <h1 class="h3 mb-3">Métricas de Tráfego</h1>
        <table class="table table-bordered table-sm">
            <thead><tr><th>Campanha</th><th>Plataforma</th><th>Impressões</th><th>Cliques</th><th>Leads</th><th>Conversões</th><th>Gasto</th><th>CPC</th></tr></thead>
            <tbody>
            <?php foreach ($metrics as $m):
                $camp = findCampaign((int) $m['campaign_id']);
                $cpc = $m['cliques'] > 0 ? $m['spent'] / $m['cliques'] : 0;
                ?>
                <tr>
                    <td><?= htmlspecialchars($camp['titulo'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($m['plataforma']) ?></td>
                    <td><?= (int) $m['impressoes'] ?></td>
                    <td><?= (int) $m['cliques'] ?></td>
                    <td><?= (int) $m['leads'] ?></td>
                    <td><?= (int) $m['conversoes'] ?></td>
                    <td>Kz <?= number_format((float) $m['spent'], 2, ',', '.') ?></td>
                    <td>Kz <?= number_format($cpc, 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
