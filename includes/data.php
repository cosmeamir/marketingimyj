<?php
require_once __DIR__ . '/db.php';

function db(): PDO
{
    global $pdo;
    if (!$pdo) {
        throw new RuntimeException('Sem ligação à base de dados. Configure o ficheiro includes/db.php');
    }
    return $pdo;
}

function bootstrapTables(): void
{
    $pdo = db();
    try {
        $pdo->exec("ALTER TABLE users MODIFY role ENUM('admin','cliente','design') NOT NULL");
    } catch (Throwable $e) {
        // ignora em ambientes onde não seja necessário
    }
    try {
        $pdo->exec("ALTER TABLE posts ADD COLUMN review_comment TEXT NULL AFTER status");
    } catch (Throwable $e) {
        // ignora se já existe
    }
        $pdo->exec('CREATE TABLE IF NOT EXISTS app_config (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tipo VARCHAR(50) NOT NULL,
        valor VARCHAR(120) NOT NULL,
        ativo TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )');
}

function seedIfEmpty(): void
{
    $pdo = db();

    $usersCount = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    $stmt = $pdo->prepare('INSERT INTO users (username, password, role, nome, email) VALUES (?, ?, ?, ?, ?)');
    if ($usersCount === 0) {
        $stmt->execute(['codigocosme', password_hash('CC.2026', PASSWORD_DEFAULT), 'admin', 'Administrador', 'admin@local']);
        $stmt->execute(['imyj', password_hash('IMYJ.2026', PASSWORD_DEFAULT), 'cliente', 'Cliente IMYJ', 'cliente@local']);
        $stmt->execute(['design', password_hash('desing.2026', PASSWORD_DEFAULT), 'design', 'Utilizador Design', 'design@local']);
    } else {
        $check = $pdo->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
        $check->execute(['design']);
        $existingDesign = $check->fetch();
        if (!$existingDesign) {
            $stmt->execute(['design', password_hash('desing.2026', PASSWORD_DEFAULT), 'design', 'Utilizador Design', 'design@local']);
        } else {
            $upd = $pdo->prepare('UPDATE users SET password = ?, role = ?, nome = ? WHERE username = ?');
            $upd->execute([password_hash('desing.2026', PASSWORD_DEFAULT), 'design', 'Utilizador Design', 'design']);
        }
    }
}

function campaigns(): array { return db()->query('SELECT * FROM campaigns ORDER BY start_date, id DESC')->fetchAll(); }
function posts(): array { return db()->query('SELECT * FROM posts ORDER BY post_date, post_time, id DESC')->fetchAll(); }
function metrics(): array { return db()->query('SELECT * FROM traffic_metrics ORDER BY data_registo DESC, id DESC')->fetchAll(); }
function channels(): array { return db()->query('SELECT * FROM channels ORDER BY nome')->fetchAll(); }

function configByType(string $type): array
{
    $stmt = db()->prepare('SELECT * FROM app_config WHERE tipo = ? ORDER BY valor');
    $stmt->execute([$type]);
    return $stmt->fetchAll();
}

function findCampaign(int $id): ?array { $s=db()->prepare('SELECT * FROM campaigns WHERE id = ? LIMIT 1'); $s->execute([$id]); return $s->fetch() ?: null; }
function findPost(int $id): ?array { $s=db()->prepare('SELECT * FROM posts WHERE id = ? LIMIT 1'); $s->execute([$id]); return $s->fetch() ?: null; }
function findMetric(int $id): ?array { $s=db()->prepare('SELECT * FROM traffic_metrics WHERE id = ? LIMIT 1'); $s->execute([$id]); return $s->fetch() ?: null; }

function saveCampaign(array $p): void
{
    if (!empty($p['id'])) {
        $sql = 'UPDATE campaigns SET titulo=:titulo, descricao=:descricao, objetivo=:objetivo, canal=:canal, start_date=:start_date, end_date=:end_date, budget=:budget, spent=:spent, status=:status, responsavel=:responsavel WHERE id=:id';
        $params = [
            ':id' => $p['id'], ':titulo' => $p['titulo'], ':descricao' => $p['descricao'], ':objetivo' => $p['objetivo'], ':canal' => $p['canal'],
            ':start_date' => $p['start_date'], ':end_date' => $p['end_date'], ':budget' => $p['budget'], ':spent' => $p['spent'],
            ':status' => $p['status'], ':responsavel' => $p['responsavel'],
        ];
    } else {
        $sql = 'INSERT INTO campaigns (titulo, descricao, objetivo, canal, start_date, end_date, budget, spent, status, responsavel) VALUES (:titulo, :descricao, :objetivo, :canal, :start_date, :end_date, :budget, :spent, :status, :responsavel)';
        $params = [
            ':titulo' => $p['titulo'], ':descricao' => $p['descricao'], ':objetivo' => $p['objetivo'], ':canal' => $p['canal'],
            ':start_date' => $p['start_date'], ':end_date' => $p['end_date'], ':budget' => $p['budget'], ':spent' => $p['spent'],
            ':status' => $p['status'], ':responsavel' => $p['responsavel'],
        ];
    }
    db()->prepare($sql)->execute($params);
}

function savePost(array $p): void
{
    if (!empty($p['id'])) {
        $sql = 'UPDATE posts SET campaign_id=:campaign_id, titulo=:titulo, tipo_conteudo=:tipo_conteudo, plataforma=:plataforma, post_date=:post_date, post_time=:post_time, legenda=:legenda, cta=:cta, status=:status, review_comment=:review_comment, creative_url=:creative_url WHERE id=:id';
        $params = [
            ':id' => $p['id'], ':campaign_id' => $p['campaign_id'], ':titulo' => $p['titulo'], ':tipo_conteudo' => $p['tipo_conteudo'],
            ':plataforma' => $p['plataforma'], ':post_date' => $p['post_date'], ':post_time' => $p['post_time'], ':legenda' => $p['legenda'],
            ':cta' => $p['cta'], ':status' => $p['status'], ':review_comment' => $p['review_comment'] ?? null, ':creative_url' => $p['creative_url'],
        ];
    } else {
        $sql = 'INSERT INTO posts (campaign_id, titulo, tipo_conteudo, plataforma, post_date, post_time, legenda, cta, status, review_comment, creative_url) VALUES (:campaign_id, :titulo, :tipo_conteudo, :plataforma, :post_date, :post_time, :legenda, :cta, :status, :review_comment, :creative_url)';
        $params = [
            ':campaign_id' => $p['campaign_id'], ':titulo' => $p['titulo'], ':tipo_conteudo' => $p['tipo_conteudo'], ':plataforma' => $p['plataforma'],
            ':post_date' => $p['post_date'], ':post_time' => $p['post_time'], ':legenda' => $p['legenda'], ':cta' => $p['cta'], ':status' => $p['status'], ':review_comment' => $p['review_comment'] ?? null, ':creative_url' => $p['creative_url'],
        ];
    }
    db()->prepare($sql)->execute($params);
}

function saveMetric(array $p): void
{
    if (!empty($p['id'])) {
        $sql = 'UPDATE traffic_metrics SET campaign_id=:campaign_id, data_registo=:data_registo, plataforma=:plataforma, impressoes=:impressoes, cliques=:cliques, leads=:leads, conversoes=:conversoes, cpc=:cpc, cpm=:cpm, spent=:spent, resultado=:resultado WHERE id=:id';
        $params = [
            ':id' => $p['id'], ':campaign_id' => $p['campaign_id'], ':data_registo' => $p['data_registo'], ':plataforma' => $p['plataforma'],
            ':impressoes' => $p['impressoes'], ':cliques' => $p['cliques'], ':leads' => $p['leads'], ':conversoes' => $p['conversoes'],
            ':cpc' => $p['cpc'], ':cpm' => $p['cpm'], ':spent' => $p['spent'], ':resultado' => $p['resultado'],
        ];
    } else {
        $sql = 'INSERT INTO traffic_metrics (campaign_id, data_registo, plataforma, impressoes, cliques, leads, conversoes, cpc, cpm, spent, resultado) VALUES (:campaign_id, :data_registo, :plataforma, :impressoes, :cliques, :leads, :conversoes, :cpc, :cpm, :spent, :resultado)';
        $params = [
            ':campaign_id' => $p['campaign_id'], ':data_registo' => $p['data_registo'], ':plataforma' => $p['plataforma'],
            ':impressoes' => $p['impressoes'], ':cliques' => $p['cliques'], ':leads' => $p['leads'], ':conversoes' => $p['conversoes'],
            ':cpc' => $p['cpc'], ':cpm' => $p['cpm'], ':spent' => $p['spent'], ':resultado' => $p['resultado'],
        ];
    }
    db()->prepare($sql)->execute($params);
}

function deleteCampaign(int $id): void { db()->prepare('DELETE FROM campaigns WHERE id = ?')->execute([$id]); }
function deletePost(int $id): void { db()->prepare('DELETE FROM posts WHERE id = ?')->execute([$id]); }
function deleteMetric(int $id): void { db()->prepare('DELETE FROM traffic_metrics WHERE id = ?')->execute([$id]); }

function addChannel(string $nome, string $tipo, string $status): void
{
    db()->prepare('INSERT INTO channels (nome, tipo, status) VALUES (?, ?, ?)')->execute([$nome, $tipo, $status]);
}

function deleteChannel(int $id): void { db()->prepare('DELETE FROM channels WHERE id=?')->execute([$id]); }

function addConfig(string $tipo, string $valor): void
{
    db()->prepare('INSERT INTO app_config (tipo, valor, ativo) VALUES (?, ?, 1)')->execute([$tipo, $valor]);
}

function deleteConfig(int $id): void { db()->prepare('DELETE FROM app_config WHERE id=?')->execute([$id]); }

function resetSystemData(): void
{
    $pdo = db();
    $pdo->beginTransaction();
    $pdo->exec('DELETE FROM calendar_items');
    $pdo->exec('DELETE FROM traffic_metrics');
    $pdo->exec('DELETE FROM posts');
    $pdo->exec('DELETE FROM campaigns');
    $pdo->exec('DELETE FROM channels');
    $pdo->exec('DELETE FROM app_config');
    $pdo->commit();
}

function findUserByUsername(string $username): ?array
{
    $stmt = db()->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    return $stmt->fetch() ?: null;
}

try { bootstrapTables(); seedIfEmpty(); } catch (Throwable $e) { /* base não disponível */ }
