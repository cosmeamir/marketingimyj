<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('admin');
$title = 'Métricas';
$metrics = metrics();
$campaigns = campaigns();
$editId = (int) ($_GET['edit'] ?? 0);
$editing = $editId ? findMetric($editId) : null;
include __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="col-12 col-lg-10">
        <h1 class="h3 mb-3"><?= $editing ? 'Editar Métrica' : 'Métricas de Tráfego' ?></h1>

        <div class="card mb-3"><div class="card-body">
            <form class="row g-2" method="post" action="/actions/save_metric.php">
                <input type="hidden" name="id" value="<?= (int) ($editing['id'] ?? 0) ?>">
                <div class="col-12 col-lg-3"><select name="campaign_id" class="form-select" required>
                    <?php foreach ($campaigns as $c): ?><option value="<?= (int) $c['id'] ?>" <?= ((int) ($editing['campaign_id'] ?? 0) === (int) $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['titulo']) ?></option><?php endforeach; ?>
                </select></div>
                <div class="col-6 col-lg-2"><input type="date" name="data_registo" class="form-control" value="<?= htmlspecialchars($editing['data_registo'] ?? date('Y-m-d')) ?>"></div>
                <div class="col-6 col-lg-2"><input name="plataforma" class="form-control" placeholder="Plataforma" value="<?= htmlspecialchars($editing['plataforma'] ?? '') ?>"></div>
                <div class="col-6 col-lg-1"><input type="number" name="impressoes" class="form-control" placeholder="Imp." value="<?= htmlspecialchars($editing['impressoes'] ?? 0) ?>"></div>
                <div class="col-6 col-lg-1"><input type="number" name="cliques" class="form-control" placeholder="Cliq." value="<?= htmlspecialchars($editing['cliques'] ?? 0) ?>"></div>
                <div class="col-6 col-lg-1"><input type="number" name="leads" class="form-control" placeholder="Leads" value="<?= htmlspecialchars($editing['leads'] ?? 0) ?>"></div>
                <div class="col-6 col-lg-1"><input type="number" name="conversoes" class="form-control" placeholder="Conv." value="<?= htmlspecialchars($editing['conversoes'] ?? 0) ?>"></div>
                <div class="col-6 col-lg-1"><input type="number" step="0.01" name="spent" class="form-control" placeholder="Gasto" value="<?= htmlspecialchars($editing['spent'] ?? 0) ?>"></div>
                <div class="col-6 col-lg-1"><button class="btn btn-primary w-100"><?= $editing ? 'Salvar' : 'Adicionar' ?></button></div>
                <input type="hidden" name="cpc" value="<?= htmlspecialchars($editing['cpc'] ?? 0) ?>">
                <input type="hidden" name="cpm" value="<?= htmlspecialchars($editing['cpm'] ?? 0) ?>">
                <input type="hidden" name="resultado" value="<?= htmlspecialchars($editing['resultado'] ?? 0) ?>">
            </form>
        </div></div>

        <table class="table table-bordered table-sm">
            <thead><tr><th>Campanha</th><th>Plataforma</th><th>Imp.</th><th>Cliques</th><th>Leads</th><th>Conv.</th><th>Gasto</th><th>CPC</th><th>Ações</th></tr></thead>
            <tbody>
            <?php foreach ($metrics as $m): $camp = findCampaign((int) $m['campaign_id']); $cpc = $m['cliques'] > 0 ? $m['spent'] / $m['cliques'] : 0; ?>
                <tr>
                    <td><?= htmlspecialchars($camp['titulo'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($m['plataforma']) ?></td>
                    <td><?= (int) $m['impressoes'] ?></td>
                    <td><?= (int) $m['cliques'] ?></td>
                    <td><?= (int) $m['leads'] ?></td>
                    <td><?= (int) $m['conversoes'] ?></td>
                    <td>Kz <?= number_format((float) $m['spent'], 2, ',', '.') ?></td>
                    <td>Kz <?= number_format($cpc, 2, ',', '.') ?></td>
                    <td class="d-flex gap-2">
                        <a class="btn btn-sm btn-outline-primary" href="/admin/metrics.php?edit=<?= (int) $m['id'] ?>">Editar</a>
                        <a class="btn btn-sm btn-outline-danger" href="/actions/delete_metric.php?id=<?= (int) $m['id'] ?>" onclick="return confirm('Apagar métrica?')">Apagar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
