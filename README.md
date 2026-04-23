# Cronograma de Marketing Digital (PHP)

Estrutura inicial de plataforma para gestão de campanhas, posts e métricas com cronograma visual em modos **Mês / Semana / Dia**.

## Stack
- PHP 8+
- HTML/CSS/JS
- Bootstrap 5
- MySQL (script em `database.sql`)

## Funcionalidades incluídas
- Login com perfis **admin** e **cliente** (sessão PHP).
- Dashboard com KPI e cronograma visual interativo.
- CRUD básico de campanhas e posts (admin).
- Visão cliente com detalhes de campanha e custo.
- Página de métricas com resumo de desempenho.

## Estrutura de pastas
```
/admin
/client
/actions
/includes
/assets
```

## Credenciais de teste
- admin: `codigocosme` / `CC.2026`
- cliente: `cliente` / `CC.2026`

> Em produção, use hash de senha (`password_hash`) e persistência real em base de dados.

## Como executar
```bash
php -S localhost:8000
```
Aceda a `http://localhost:8000/login.php`.
