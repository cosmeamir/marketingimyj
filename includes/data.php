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
    if ($usersCount === 0) {
        $stmt = $pdo->prepare('INSERT INTO users (username, password, role, nome, email) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute(['codigocosme', password_hash('CC.2026', PASSWORD_DEFAULT), 'admin', 'Administrador', 'admin@local']);
        $stmt->execute(['imyj', password_hash('IMYJ.2026', PASSWORD_DEFAULT), 'cliente', 'Cliente IMYJ', 'cliente@local']);
    }

    $channelsCount = (int) $pdo->query('SELECT COUNT(*) FROM channels')->fetchColumn();
    if ($channelsCount === 0) {
        $pdo->exec("INSERT INTO channels (nome, tipo, status) VALUES
            ('Instagram', 'Rede social', 'Ativo'),
            ('Facebook', 'Rede social', 'Ativo'),
            ('Google Ads', 'Ads', 'Ativo'),
            ('Email', 'CRM', 'Ativo')");
    }

    $configCount = (int) $pdo->query('SELECT COUNT(*) FROM app_config')->fetchColumn();
    if ($configCount === 0) {
        $pdo->exec("INSERT INTO app_config (tipo, valor, ativo) VALUES
            ('status_campanha', 'Planeado', 1),
            ('status_campanha', 'Em execução', 1),
            ('status_campanha', 'Concluído', 1),
            ('status_campanha', 'Pausado', 1),
            ('tipo_conteudo', 'Imagem', 1),
            ('tipo_conteudo', 'Vídeo', 1),
            ('tipo_conteudo', 'Reel', 1),
            ('tipo_conteudo', 'Banner', 1)");
    }

    $campaignCount = (int) $pdo->query('SELECT COUNT(*) FROM campaigns')->fetchColumn();
    if ($campaignCount === 0) {
        $pdo->exec("INSERT INTO campaigns (titulo, descricao, objetivo, canal, start_date, end_date, budget, spent, status, responsavel)
            VALUES
            ('Lançamento Produto X', 'Campanha de awareness + tráfego', 'Leads', 'Instagram', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 20 DAY), 2500000, 1240000, 'Em execução', 'Ana Silva'),
            ('Promoção Outono', 'Conversão para ecommerce', 'Conversões', 'Google Ads', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY), 4000000, 1980000, 'Planeado', 'Bruno Costa')");

        $pdo->exec("INSERT INTO posts (campaign_id, titulo, tipo_conteudo, plataforma, post_date, post_time, legenda, cta, status, creative_url)
            VALUES
            (1, 'Teaser em Reel', 'Reel', 'Instagram', CURDATE(), '10:30:00', 'Algo grande está a chegar', 'Saber mais', 'Aprovado', '#'),
            (2, 'Banner pesquisa', 'Banner', 'Google Ads', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '09:00:00', 'Oferta limitada', 'Comprar agora', 'Planeado', '#')");

        $pdo->exec("INSERT INTO traffic_metrics (campaign_id, data_registo, plataforma, impressoes, cliques, leads, conversoes, cpc, cpm, spent, resultado)
            VALUES
            (1, CURDATE(), 'Instagram', 38000, 2100, 240, 62, 590.48, 32631.58, 1240000, 0),
            (2, CURDATE(), 'Google Ads', 22000, 1300, 160, 48, 1523.08, 90000.00, 1980000, 0)");
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
    } else {
        $sql = 'INSERT INTO campaigns (titulo, descricao, objetivo, canal, start_date, end_date, budget, spent, status, responsavel) VALUES (:titulo, :descricao, :objetivo, :canal, :start_date, :end_date, :budget, :spent, :status, :responsavel)';
    }
    $stmt = db()->prepare($sql);
    $stmt->execute([
        ':id' => $p['id'] ?? null,
        ':titulo' => $p['titulo'], ':descricao' => $p['descricao'], ':objetivo' => $p['objetivo'], ':canal' => $p['canal'],
        ':start_date' => $p['start_date'], ':end_date' => $p['end_date'], ':budget' => $p['budget'], ':spent' => $p['spent'],
        ':status' => $p['status'], ':responsavel' => $p['responsavel'],
    ]);
}

function savePost(array $p): void
{
    if (!empty($p['id'])) {
        $sql = 'UPDATE posts SET campaign_id=:campaign_id, titulo=:titulo, tipo_conteudo=:tipo_conteudo, plataforma=:plataforma, post_date=:post_date, post_time=:post_time, legenda=:legenda, cta=:cta, status=:status, creative_url=:creative_url WHERE id=:id';
    } else {
        $sql = 'INSERT INTO posts (campaign_id, titulo, tipo_conteudo, plataforma, post_date, post_time, legenda, cta, status, creative_url) VALUES (:campaign_id, :titulo, :tipo_conteudo, :plataforma, :post_date, :post_time, :legenda, :cta, :status, :creative_url)';
    }
    $stmt = db()->prepare($sql);
    $stmt->execute([
        ':id' => $p['id'] ?? null, ':campaign_id' => $p['campaign_id'], ':titulo' => $p['titulo'], ':tipo_conteudo' => $p['tipo_conteudo'],
        ':plataforma' => $p['plataforma'], ':post_date' => $p['post_date'], ':post_time' => $p['post_time'], ':legenda' => $p['legenda'],
        ':cta' => $p['cta'], ':status' => $p['status'], ':creative_url' => $p['creative_url'],
    ]);
}

function saveMetric(array $p): void
{
    if (!empty($p['id'])) {
        $sql = 'UPDATE traffic_metrics SET campaign_id=:campaign_id, data_registo=:data_registo, plataforma=:plataforma, impressoes=:impressoes, cliques=:cliques, leads=:leads, conversoes=:conversoes, cpc=:cpc, cpm=:cpm, spent=:spent, resultado=:resultado WHERE id=:id';
    } else {
        $sql = 'INSERT INTO traffic_metrics (campaign_id, data_registo, plataforma, impressoes, cliques, leads, conversoes, cpc, cpm, spent, resultado) VALUES (:campaign_id, :data_registo, :plataforma, :impressoes, :cliques, :leads, :conversoes, :cpc, :cpm, :spent, :resultado)';
    }
    db()->prepare($sql)->execute([
        ':id' => $p['id'] ?? null, ':campaign_id' => $p['campaign_id'], ':data_registo' => $p['data_registo'], ':plataforma' => $p['plataforma'],
        ':impressoes' => $p['impressoes'], ':cliques' => $p['cliques'], ':leads' => $p['leads'], ':conversoes' => $p['conversoes'],
        ':cpc' => $p['cpc'], ':cpm' => $p['cpm'], ':spent' => $p['spent'], ':resultado' => $p['resultado'],
    ]);
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
    $pdo->commit();
}

function findUserByUsername(string $username): ?array
{
    $stmt = db()->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    return $stmt->fetch() ?: null;
}

try { bootstrapTables(); seedIfEmpty(); } catch (Throwable $e) { /* base não disponível */ }
