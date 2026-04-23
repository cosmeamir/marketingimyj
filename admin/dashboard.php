<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireRole('admin');
$title = 'Configuração';
$channels = channels();
$statusCamp = configByType('status_campanha');
$tipos = configByType('tipo_conteudo');
include __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="col-12 col-lg-10">
        <h1 class="h3">Configuração</h1>
        <?php if (!empty($_GET['reset'])): ?><div class="alert alert-success">Sistema limpo com sucesso.</div><?php endif; ?>

        <div class="row g-3 mt-1">
            <div class="col-12 col-lg-6">
                <div class="card h-100"><div class="card-body">
                    <h2 class="h5">Canais</h2>
                    <form class="row g-2 mb-3" method="post" action="/actions/save_channel.php">
                        <div class="col-5"><input name="nome" class="form-control" placeholder="Nome" required></div>
                        <div class="col-4"><input name="tipo" class="form-control" placeholder="Tipo"></div>
                        <div class="col-3"><button class="btn btn-primary w-100">Adicionar</button></div>
                    </form>
                    <?php foreach ($channels as $ch): ?>
                        <div class="d-flex justify-content-between border-bottom py-1">
                            <span><?= htmlspecialchars($ch['nome']) ?> <small class="text-muted">(<?= htmlspecialchars($ch['tipo']) ?>)</small></span>
                            <a href="/actions/delete_channel.php?id=<?= (int) $ch['id'] ?>" class="text-danger" onclick="return confirm('Apagar canal?')">remover</a>
                        </div>
                    <?php endforeach; ?>
                </div></div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card h-100"><div class="card-body">
                    <h2 class="h5">Estados e tipos</h2>
                    <form class="row g-2 mb-3" method="post" action="/actions/save_config.php">
                        <div class="col-4"><select name="tipo" class="form-select"><option value="status_campanha">Status campanha</option><option value="tipo_conteudo">Tipo conteúdo</option></select></div>
                        <div class="col-5"><input name="valor" class="form-control" placeholder="Novo valor" required></div>
                        <div class="col-3"><button class="btn btn-primary w-100">Salvar</button></div>
                    </form>
                    <p class="mb-1"><strong>Status:</strong></p>
                    <?php foreach ($statusCamp as $item): ?><div class="d-flex justify-content-between border-bottom py-1"><span><?= htmlspecialchars($item['valor']) ?></span><a class="text-danger" href="/actions/delete_config.php?id=<?= (int) $item['id'] ?>">remover</a></div><?php endforeach; ?>
                    <p class="mt-3 mb-1"><strong>Tipos:</strong></p>
                    <?php foreach ($tipos as $item): ?><div class="d-flex justify-content-between border-bottom py-1"><span><?= htmlspecialchars($item['valor']) ?></span><a class="text-danger" href="/actions/delete_config.php?id=<?= (int) $item['id'] ?>">remover</a></div><?php endforeach; ?>
                </div></div>
            </div>
            <div class="col-12">
                <div class="card border-danger"><div class="card-body">
                    <h2 class="h5 text-danger">Reset total do sistema</h2>
                    <p class="small mb-2">Apaga campanhas, posts e métricas para começar do zero.</p>
                    <form class="row g-2" method="post" action="/actions/reset_data.php">
                        <div class="col-12 col-lg-3"><input name="confirm" class="form-control" placeholder="Digite RESETAR"></div>
                        <div class="col-12 col-lg-3"><button class="btn btn-danger">Limpar sistema</button></div>
                    </form>
                </div></div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
