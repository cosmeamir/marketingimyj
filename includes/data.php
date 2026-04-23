<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['seeded'])) {
    $_SESSION['campaigns'] = [
        [
            'id' => 1,
            'titulo' => 'Lançamento Produto X',
            'descricao' => 'Campanha de awareness + tráfego',
            'objetivo' => 'Leads',
            'canal' => 'Instagram',
            'start_date' => date('Y-m-01'),
            'end_date' => date('Y-m-18'),
            'budget' => 2500,
            'spent' => 1240,
            'status' => 'Em execução',
            'responsavel' => 'Ana Silva',
        ],
        [
            'id' => 2,
            'titulo' => 'Promoção Outono',
            'descricao' => 'Conversão para ecommerce',
            'objetivo' => 'Conversões',
            'canal' => 'Google Ads',
            'start_date' => date('Y-m-10'),
            'end_date' => date('Y-m-28'),
            'budget' => 4000,
            'spent' => 1980,
            'status' => 'Planeado',
            'responsavel' => 'Bruno Costa',
        ],
    ];

    $_SESSION['posts'] = [
        [
            'id' => 1,
            'campaign_id' => 1,
            'titulo' => 'Teaser em Reel',
            'tipo_conteudo' => 'Reel',
            'plataforma' => 'Instagram',
            'post_date' => date('Y-m-03'),
            'post_time' => '10:30',
            'legenda' => 'Algo grande está a chegar 👀',
            'cta' => 'Saber mais',
            'status' => 'Aprovado',
            'creative_url' => '#',
        ],
        [
            'id' => 2,
            'campaign_id' => 2,
            'titulo' => 'Banner pesquisa',
            'tipo_conteudo' => 'Banner',
            'plataforma' => 'Google Ads',
            'post_date' => date('Y-m-12'),
            'post_time' => '09:00',
            'legenda' => 'Oferta limitada',
            'cta' => 'Comprar agora',
            'status' => 'Planeado',
            'creative_url' => '#',
        ],
    ];

    $_SESSION['metrics'] = [
        ['campaign_id' => 1, 'plataforma' => 'Instagram', 'impressoes' => 38000, 'cliques' => 2100, 'leads' => 240, 'conversoes' => 62, 'spent' => 1240],
        ['campaign_id' => 2, 'plataforma' => 'Google Ads', 'impressoes' => 22000, 'cliques' => 1300, 'leads' => 160, 'conversoes' => 48, 'spent' => 1980],
    ];

    $_SESSION['seeded'] = true;
}

function campaigns(): array
{
    return $_SESSION['campaigns'] ?? [];
}

function posts(): array
{
    return $_SESSION['posts'] ?? [];
}

function metrics(): array
{
    return $_SESSION['metrics'] ?? [];
}

function findCampaign(int $id): ?array
{
    foreach (campaigns() as $campaign) {
        if ((int) $campaign['id'] === $id) {
            return $campaign;
        }
    }
    return null;
}

function saveCampaign(array $payload): void
{
    $list = campaigns();

    if (!empty($payload['id'])) {
        foreach ($list as &$item) {
            if ((int) $item['id'] === (int) $payload['id']) {
                $item = array_merge($item, $payload);
            }
        }
    } else {
        $payload['id'] = count($list) ? max(array_column($list, 'id')) + 1 : 1;
        $list[] = $payload;
    }

    $_SESSION['campaigns'] = array_values($list);
}

function deleteCampaign(int $id): void
{
    $_SESSION['campaigns'] = array_values(array_filter(campaigns(), fn ($c) => (int) $c['id'] !== $id));
    $_SESSION['posts'] = array_values(array_filter(posts(), fn ($p) => (int) $p['campaign_id'] !== $id));
}

function savePost(array $payload): void
{
    $list = posts();

    if (!empty($payload['id'])) {
        foreach ($list as &$item) {
            if ((int) $item['id'] === (int) $payload['id']) {
                $item = array_merge($item, $payload);
            }
        }
    } else {
        $payload['id'] = count($list) ? max(array_column($list, 'id')) + 1 : 1;
        $list[] = $payload;
    }

    $_SESSION['posts'] = array_values($list);
}

function deletePost(int $id): void
{
    $_SESSION['posts'] = array_values(array_filter(posts(), fn ($p) => (int) $p['id'] !== $id));
}
