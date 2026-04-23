<?php
require_once __DIR__ . '/../includes/auth.php';
requireRole('admin');
$title = 'Configuração';
include __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>
    <div class="col-md-10">
        <h1 class="h3">Configuração</h1>
        <div class="card mt-3"><div class="card-body">Gestão de utilizadores, canais, categorias e estados (estrutura inicial).</div></div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
