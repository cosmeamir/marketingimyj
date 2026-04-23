<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/data.php';
requireLogin();

$view = $_GET['view'] ?? 'month';
$ym = $_GET['ym'] ?? date('Y-m');
if (!preg_match('/^\d{4}-\d{2}$/', $ym)) { $ym = date('Y-m'); }
$currentDate = DateTime::createFromFormat('Y-m', $ym) ?: new DateTime('first day of this month');
$prevYm = (clone $currentDate)->modify('-1 month')->format('Y-m');
$nextYm = (clone $currentDate)->modify('+1 month')->format('Y-m');
$meses = [1=>'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
$monthLabel = ($meses[(int)$currentDate->format('n')] ?? $currentDate->format('m')) . ' de ' . $currentDate->format('Y');
$title = 'Dashboard';
$campaigns = campaigns();
$posts = posts();
$totalSpent = array_sum(array_column($campaigns, 'spent'));

include __DIR__ . '/includes/header.php';
?>
<div class="row">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="col-12 col-lg-10">
        <?php if (($_GET['db'] ?? '') === 'offline'): ?>
            <div class="alert alert-warning">Entrou em modo de contingência (sem BD). Configure a ligação MySQL para guardar dados de forma permanente.</div>
        <?php endif; ?>

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h1 class="h3 mb-0">Cronograma de Marketing</h1>
                <small class="text-muted text-capitalize"><?= htmlspecialchars($monthLabel) ?></small>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <div class="btn-group">
                    <a href="?view=<?= urlencode($view) ?>&ym=<?= urlencode($prevYm) ?>" class="btn btn-outline-secondary">◀</a>
                    <a href="?view=<?= urlencode($view) ?>&ym=<?= date('Y-m') ?>" class="btn btn-outline-secondary">Hoje</a>
                    <a href="?view=<?= urlencode($view) ?>&ym=<?= urlencode($nextYm) ?>" class="btn btn-outline-secondary">▶</a>
                </div>
                <div class="btn-group">
                    <a href="?view=month&ym=<?= urlencode($ym) ?>" class="btn btn-outline-primary <?= $view === 'month' ? 'active' : '' ?>">Mês</a>
                    <a href="?view=week&ym=<?= urlencode($ym) ?>" class="btn btn-outline-primary <?= $view === 'week' ? 'active' : '' ?>">Semana</a>
                    <a href="?view=day&ym=<?= urlencode($ym) ?>" class="btn btn-outline-primary <?= $view === 'day' ? 'active' : '' ?>">Dia</a>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-3"><div class="kpi-card"><span>Total campanhas</span><strong><?= count($campaigns) ?></strong></div></div>
            <div class="col-md-3"><div class="kpi-card"><span>Total posts</span><strong><?= count($posts) ?></strong></div></div>
            <div class="col-md-3"><div class="kpi-card"><span>Total gasto</span><strong>Kz <?= number_format($totalSpent, 2, ',', '.') ?></strong></div></div>
            <div class="col-md-3"><div class="kpi-card"><span>Campanhas ativas</span><strong><?= count(array_filter($campaigns, fn($c) => $c['status'] === 'Em execução')) ?></strong></div></div>
        </div>

        <div class="card">
            <div class="card-header">Visual <?= htmlspecialchars($view) ?></div>
            <div class="card-body">
                <div id="timeline" data-view="<?= htmlspecialchars($view) ?>" data-ym="<?= htmlspecialchars($ym) ?>" data-posts='<?= json_encode($posts, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'></div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
