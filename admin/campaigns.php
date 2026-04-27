<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('admin');
$title = 'Campanhas';
$campaigns = campaigns();
$channels = channels();
$statuses = configByType('status_campanha');
$editId = (int) ($_GET['edit'] ?? 0);
$editing = $editId ? findCampaign($editId) : null;
include __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="col-12 col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3"><?= $editing ? 'Editar campanha' : 'Campanhas' ?></h1>
            <?php if ($editing): ?><a class="btn btn-outline-secondary" href="/admin/campaigns.php">Cancelar edição</a><?php endif; ?>
        </div>
        <div class="card mb-3"><div class="card-body">
            <form class="row g-2" method="post" action="/actions/save_campaign.php">
                <input type="hidden" name="id" value="<?= (int) ($editing['id'] ?? 0) ?>">
                <div class="col-12 col-lg-3"><input name="titulo" class="form-control" placeholder="Título" required value="<?= htmlspecialchars($editing['titulo'] ?? '') ?>"></div>
                <div class="col-12 col-lg-2">
                    <?php if (!empty($channels)): ?>
                        <select name="canal" class="form-select" required>
                            <?php foreach ($channels as $ch): ?><option value="<?= htmlspecialchars($ch['nome']) ?>" <?= (($editing['canal'] ?? '') === $ch['nome']) ? 'selected' : '' ?>><?= htmlspecialchars($ch['nome']) ?></option><?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input name="canal" class="form-control" placeholder="Canal" value="<?= htmlspecialchars($editing['canal'] ?? '') ?>" required>
                    <?php endif; ?>
                </div>
                <div class="col-6 col-lg-2"><input type="date" name="start_date" class="form-control" required value="<?= htmlspecialchars($editing['start_date'] ?? '') ?>"></div>
                <div class="col-6 col-lg-2"><input type="date" name="end_date" class="form-control" required value="<?= htmlspecialchars($editing['end_date'] ?? '') ?>"></div>
                <div class="col-6 col-lg-1"><input type="number" step="0.01" name="budget" class="form-control" placeholder="Orç." value="<?= htmlspecialchars($editing['budget'] ?? '') ?>"></div>
                <div class="col-6 col-lg-1"><input type="number" step="0.01" name="spent" class="form-control" placeholder="Gasto" value="<?= htmlspecialchars($editing['spent'] ?? '') ?>"></div>
                <div class="col-12 col-lg-1"><button class="btn btn-primary w-100"><?= $editing ? 'Salvar' : 'Criar' ?></button></div>
                <div class="col-12 col-lg-3"><input name="objetivo" class="form-control" placeholder="Objetivo" value="<?= htmlspecialchars($editing['objetivo'] ?? '') ?>"></div>
                <div class="col-12 col-lg-3"><input name="responsavel" class="form-control" placeholder="Responsável" value="<?= htmlspecialchars($editing['responsavel'] ?? '') ?>"></div>
                <div class="col-12 col-lg-3">
                    <?php if (!empty($statuses)): ?>
                        <select name="status" class="form-select">
                            <?php foreach ($statuses as $st): ?><option value="<?= htmlspecialchars($st['valor']) ?>" <?= (($editing['status'] ?? '') === $st['valor']) ? 'selected' : '' ?>><?= htmlspecialchars($st['valor']) ?></option><?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input name="status" class="form-control" placeholder="Status" value="<?= htmlspecialchars($editing['status'] ?? 'Planeado') ?>">
                    <?php endif; ?>
                </div>
                <div class="col-12 col-lg-3"><input name="descricao" class="form-control" placeholder="Descrição" value="<?= htmlspecialchars($editing['descricao'] ?? '') ?>"></div>
            </form>
        </div></div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead><tr><th>Título</th><th>Canal</th><th>Período</th><th>Orçamento</th><th>Gasto</th><th>Status</th><th>Ações</th></tr></thead>
                <tbody>
                <?php foreach ($campaigns as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['titulo']) ?></td>
                        <td><?= htmlspecialchars($c['canal']) ?></td>
                        <td><?= htmlspecialchars($c['start_date']) ?> até <?= htmlspecialchars($c['end_date']) ?></td>
                        <td>Kz <?= number_format((float) $c['budget'], 2, ',', '.') ?></td>
                        <td>Kz <?= number_format((float) $c['spent'], 2, ',', '.') ?></td>
                        <td><span class="badge text-bg-secondary"><?= htmlspecialchars($c['status']) ?></span></td>
                        <td class="d-flex gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="/admin/campaigns.php?edit=<?= (int) $c['id'] ?>">Editar</a>
                            <a class="btn btn-sm btn-outline-danger" href="/actions/delete_campaign.php?id=<?= (int) $c['id'] ?>" onclick="return confirm('Apagar campanha?')">Apagar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
