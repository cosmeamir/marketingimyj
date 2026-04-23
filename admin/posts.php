<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('admin');
$title = 'Posts';
$posts = posts();
$campaigns = campaigns();
include __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="col-md-10">
        <h1 class="h3 mb-3">Posts</h1>
        <div class="card mb-3"><div class="card-body">
            <form class="row g-2" method="post" action="/actions/save_post.php">
                <div class="col-md-3"><input name="titulo" class="form-control" placeholder="Título" required></div>
                <div class="col-md-2"><input name="plataforma" class="form-control" placeholder="Plataforma" required></div>
                <div class="col-md-2"><input type="date" name="post_date" class="form-control" required></div>
                <div class="col-md-1"><input type="time" name="post_time" class="form-control" required></div>
                <div class="col-md-2"><select class="form-select" name="campaign_id" required>
                    <?php foreach ($campaigns as $c): ?><option value="<?= (int) $c['id'] ?>"><?= htmlspecialchars($c['titulo']) ?></option><?php endforeach; ?>
                </select></div>
                <div class="col-md-2"><button class="btn btn-primary w-100">Novo post</button></div>
            </form>
        </div></div>

        <table class="table table-striped">
            <thead><tr><th>Título</th><th>Data/Hora</th><th>Plataforma</th><th>Campanha</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($posts as $p): $camp = findCampaign((int) $p['campaign_id']); ?>
                <tr>
                    <td><?= htmlspecialchars($p['titulo']) ?></td>
                    <td><?= htmlspecialchars($p['post_date']) ?> <?= htmlspecialchars($p['post_time']) ?></td>
                    <td><?= htmlspecialchars($p['plataforma']) ?></td>
                    <td><?= htmlspecialchars($camp['titulo'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($p['status']) ?></td>
                    <td><a class="btn btn-sm btn-outline-danger" href="/actions/delete_post.php?id=<?= (int) $p['id'] ?>">Apagar</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
