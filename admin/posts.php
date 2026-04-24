<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireAnyRole(['admin', 'design']);

$user = currentUser();
$isAdmin = ($user['role'] ?? '') === 'admin';
$title = 'Posts';
$posts = posts();
$campaigns = campaigns();
$contentTypes = configByType('tipo_conteudo');
$editId = (int) ($_GET['edit'] ?? 0);
$editing = $editId ? findPost($editId) : null;
include __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="col-12 col-lg-10">
        <h1 class="h3 mb-3"><?= $editing ? 'Editar Post' : 'Posts' ?></h1>
        <?php if (!$isAdmin): ?><div class="alert alert-info">Perfil Design: pode apenas atualizar posts existentes. Ao salvar, o status fica <strong>Pendente aprovação</strong>.</div><?php endif; ?>

        <?php if ($isAdmin || $editing): ?>
        <div class="card mb-3"><div class="card-body">
            <form class="row g-2" method="post" action="/actions/save_post.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= (int) ($editing['id'] ?? 0) ?>">
                <input type="hidden" name="current_creative_url" value="<?= htmlspecialchars($editing['creative_url'] ?? "") ?>">
                <input type="hidden" name="current_review_comment" value="<?= htmlspecialchars($editing['review_comment'] ?? "") ?>">
                <div class="col-12 col-lg-3"><input name="titulo" class="form-control" placeholder="Título" required value="<?= htmlspecialchars($editing['titulo'] ?? '') ?>"></div>
                <div class="col-6 col-lg-2"><input name="plataforma" class="form-control" placeholder="Plataforma" required value="<?= htmlspecialchars($editing['plataforma'] ?? '') ?>"></div>
                <div class="col-6 col-lg-2"><input type="date" name="post_date" class="form-control" required value="<?= htmlspecialchars($editing['post_date'] ?? '') ?>"></div>
                <div class="col-6 col-lg-1"><input type="time" name="post_time" class="form-control" required value="<?= htmlspecialchars($editing['post_time'] ?? '') ?>"></div>
                <div class="col-6 col-lg-2"><select class="form-select" name="campaign_id" required>
                    <?php foreach ($campaigns as $c): ?><option value="<?= (int) $c['id'] ?>" <?= ((int) ($editing['campaign_id'] ?? 0) === (int) $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['titulo']) ?></option><?php endforeach; ?>
                </select></div>
                <div class="col-6 col-lg-1">
                    <?php if (!empty($contentTypes)): ?>
                        <select class="form-select" name="tipo_conteudo">
                            <?php foreach ($contentTypes as $ct): ?><option value="<?= htmlspecialchars($ct['valor']) ?>" <?= (($editing['tipo_conteudo'] ?? '') === $ct['valor']) ? 'selected' : '' ?>><?= htmlspecialchars($ct['valor']) ?></option><?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input class="form-control" name="tipo_conteudo" placeholder="Tipo" value="<?= htmlspecialchars($editing['tipo_conteudo'] ?? '') ?>">
                    <?php endif; ?>
                </div>
                <div class="col-6 col-lg-1"><button class="btn btn-primary w-100"><?= $editing ? 'Salvar' : 'Novo' ?></button></div>
                <div class="col-12"><input name="legenda" class="form-control" placeholder="Legenda" value="<?= htmlspecialchars($editing['legenda'] ?? '') ?>"></div>
                <div class="col-12"><input class="form-control" value="<?= htmlspecialchars($editing['review_comment'] ?? '') ?>" placeholder="Comentário do cliente" readonly></div>
                <div class="col-12 col-lg-3"><input name="creative_url" class="form-control" placeholder="URL da imagem de capa (opcional)" value="<?= htmlspecialchars($editing['creative_url'] ?? '') ?>"></div>
                <div class="col-12 col-lg-3"><input type="file" name="cover_image" accept="image/*" class="form-control"></div>
            </form>
        </div></div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead><tr><th>Título</th><th>Data/Hora</th><th>Plataforma</th><th>Campanha</th><th>Status</th><th>Ações</th></tr></thead>
            <tbody>
            <?php foreach ($posts as $p): $camp = findCampaign((int) $p['campaign_id']); ?>
                <tr>
                    <td><?= htmlspecialchars($p['titulo']) ?></td>
                    <td><?= htmlspecialchars($p['post_date']) ?> <?= htmlspecialchars(substr((string) $p['post_time'], 0, 5)) ?></td>
                    <td><?= htmlspecialchars($p['plataforma']) ?></td>
                    <td><?= htmlspecialchars($camp['titulo'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($p['status']) ?></td>
                    <td class="d-flex gap-2">
                        <a class="btn btn-sm btn-outline-primary" href="/admin/posts.php?edit=<?= (int) $p['id'] ?>">Editar</a>
                        <?php if ($isAdmin): ?>
                            <a class="btn btn-sm btn-outline-danger" href="/actions/delete_post.php?id=<?= (int) $p['id'] ?>" onclick="return confirm('Apagar post?')">Apagar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
