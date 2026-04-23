<?php
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: /index.php');
    exit;
}

$title = 'Login';
include __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h1 class="h4 mb-3">Entrar</h1>
                <?php if (($_GET['error'] ?? '') === '1'): ?>
                    <div class="alert alert-danger">Credenciais inválidas.</div>
                <?php endif; ?>
                <?php if (($_GET['error'] ?? '') === 'db'): ?>
                    <div class="alert alert-warning">
                        Não foi possível ligar à base de dados. Verifique `includes/db.php`, importação do `database.sql` e permissões MySQL na Hostinger.
                    </div>
                <?php endif; ?>
                <form method="post" action="/actions/login_action.php">
                    <div class="mb-3">
                        <label class="form-label">Utilizador</label>
                        <input name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
