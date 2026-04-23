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

function seedIfEmpty(): void
{
    $pdo = db();

    $usersCount = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($usersCount === 0) {
        $stmt = $pdo->prepare('INSERT INTO users (username, password, role, nome, email) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute(['codigocosme', password_hash('CC.2026', PASSWORD_DEFAULT), 'admin', 'Administrador', 'admin@local']);
        $stmt->execute(['imyj', password_hash('IMYJ.2026', PASSWORD_DEFAULT), 'cliente', 'Cliente IMYJ', 'cliente@local']);
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

function campaigns(): array
{
    $stmt = db()->query('SELECT * FROM campaigns ORDER BY start_date, id DESC');
    return $stmt->fetchAll();
}

function posts(): array
{
    $stmt = db()->query('SELECT * FROM posts ORDER BY post_date, post_time, id DESC');
    return $stmt->fetchAll();
}

function metrics(): array
{
    $stmt = db()->query('SELECT * FROM traffic_metrics ORDER BY data_registo DESC, id DESC');
    return $stmt->fetchAll();
}

function findCampaign(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM campaigns WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    return $item ?: null;
}

function saveCampaign(array $payload): void
{
    $sql = 'INSERT INTO campaigns (titulo, descricao, objetivo, canal, start_date, end_date, budget, spent, status, responsavel)
            VALUES (:titulo, :descricao, :objetivo, :canal, :start_date, :end_date, :budget, :spent, :status, :responsavel)';
    $stmt = db()->prepare($sql);
    $stmt->execute([
        ':titulo' => $payload['titulo'],
        ':descricao' => $payload['descricao'],
        ':objetivo' => $payload['objetivo'],
        ':canal' => $payload['canal'],
        ':start_date' => $payload['start_date'],
        ':end_date' => $payload['end_date'],
        ':budget' => $payload['budget'],
        ':spent' => $payload['spent'],
        ':status' => $payload['status'],
        ':responsavel' => $payload['responsavel'],
    ]);
}

function deleteCampaign(int $id): void
{
    $stmt = db()->prepare('DELETE FROM campaigns WHERE id = ?');
    $stmt->execute([$id]);
}

function savePost(array $payload): void
{
    $sql = 'INSERT INTO posts (campaign_id, titulo, tipo_conteudo, plataforma, post_date, post_time, legenda, cta, status, creative_url)
            VALUES (:campaign_id, :titulo, :tipo_conteudo, :plataforma, :post_date, :post_time, :legenda, :cta, :status, :creative_url)';
    $stmt = db()->prepare($sql);
    $stmt->execute([
        ':campaign_id' => $payload['campaign_id'],
        ':titulo' => $payload['titulo'],
        ':tipo_conteudo' => $payload['tipo_conteudo'],
        ':plataforma' => $payload['plataforma'],
        ':post_date' => $payload['post_date'],
        ':post_time' => $payload['post_time'],
        ':legenda' => $payload['legenda'],
        ':cta' => $payload['cta'],
        ':status' => $payload['status'],
        ':creative_url' => $payload['creative_url'],
    ]);
}

function deletePost(int $id): void
{
    $stmt = db()->prepare('DELETE FROM posts WHERE id = ?');
    $stmt->execute([$id]);
}

function findUserByUsername(string $username): ?array
{
    $stmt = db()->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    return $user ?: null;
}

try { seedIfEmpty(); } catch (Throwable $e) { /* base não disponível */ }

