# Cronograma de Marketing Digital (PHP + MySQL)

Aplicação web para gestão de campanhas, posts e métricas com cronograma visual em **Mês / Semana / Dia**, com perfis **Admin** e **Cliente**.

## Stack
- PHP 8+
- MySQL/MariaDB
- HTML/CSS/JavaScript
- Bootstrap 5

## Credenciais iniciais
> As credenciais não aparecem no formulário de login.

- **Admin**: `codigocosme` / `CC.2026`
- **Cliente**: `imyj` / `IMYJ.2026`

As senhas são guardadas com `password_hash` no seed automático.

## Moeda
Todos os valores no sistema estão apresentados em **Kwanzas (Kz)**.

## Funcionalidades já implementadas
- Login/logout com sessão e níveis de acesso.
- Dashboard com filtros de visualização (Mês, Semana, Dia).
- Cronograma responsivo para desktop e mobile.
- Módulo Admin para criar/apagar campanhas e posts.
- Módulo de métricas com CPC e gastos.
- Visão Cliente com os mesmos dados gravados pelo Admin (dados partilhados via base de dados).

## Estrutura
```
/admin
/client
/actions
/includes
/assets
```

## Como executar localmente
1. Crie a base de dados MySQL.
2. Importe o ficheiro `database.sql`.
3. Ajuste as credenciais em `includes/db.php` (ou variáveis de ambiente `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`).
4. Inicie o servidor PHP:
   ```bash
   php -S localhost:8000
   ```
5. Abra `http://localhost:8000/login.php`.

---

## Como criar a base de dados na Hostinger (passo a passo)

### 1) Criar base de dados MySQL
1. Entre no **hPanel da Hostinger**.
2. Vá em **Websites > Gerenciar** (site onde vai publicar).
3. Entre em **Databases > MySQL Databases**.
4. Crie:
   - Nome da base de dados (ex.: `u123456_marketing`)
   - Utilizador MySQL
   - Palavra-passe MySQL
5. Guarde os dados: **host**, **database**, **username**, **password**.

### 2) Importar o schema
1. Abra **phpMyAdmin** pela Hostinger.
2. Selecione a base criada.
3. Clique em **Import**.
4. Envie o ficheiro `database.sql`.
5. Execute a importação.

### 3) Publicar o projeto
1. Faça upload dos ficheiros do projeto para a pasta `public_html` (ou subpasta do domínio).
2. Confirme permissões padrão da Hostinger (normalmente já corretas para PHP).

### 4) Configurar ligação da aplicação
No ficheiro `includes/db.php`, configure os dados da Hostinger (ou variables de ambiente):
- `DB_HOST` (geralmente `localhost`, confirmar no painel)
- `DB_PORT` (normalmente `3306`)
- `DB_NAME`
- `DB_USER`
- `DB_PASS`

### 5) Primeiro acesso
1. Abra a URL do projeto.
2. Faça login com os utilizadores iniciais.
3. O sistema cria seeds automáticos se as tabelas estiverem vazias.

### 6) Recomendação de produção
- Trocar credenciais iniciais após o primeiro login.
- Ativar HTTPS no domínio.
- Restringir acesso a rotas admin.
- Fazer backups periódicos da base.

## Troubleshooting: erro 500 ao fazer login (`/actions/login_action.php`)
Se aparecer **HTTP ERROR 500** na Hostinger, normalmente é problema de base de dados/configuração.

Checklist rápido:
1. Confirmar que importou `database.sql` na base certa.
2. Confirmar host, nome, user e password MySQL no `includes/db.php`.
3. Confirmar permissões do utilizador MySQL para essa base.
4. No hPanel, escolher uma versão suportada (PHP 8.2, 8.3, 8.4 ou 8.5) e ativar `mysqli/pdo_mysql` (recomendado: PHP 8.3).
5. Verificar logs de erro da Hostinger (Errors/Logs) para mensagem exata.

O sistema agora evita erro fatal no login: se a BD falhar, mostra aviso e usa modo de contingência temporário.


## Compatibilidade de versão PHP (Hostinger)
As versões mostradas no seu painel (**8.2, 8.3, 8.4, 8.5**) são compatíveis com este projeto.

Recomendação prática:
- Use **PHP 8.3** (equilíbrio entre estabilidade e suporte atual).
- Se continuar com erro 500, o problema costuma ser **BD/credenciais/permissões**, não a versão PHP em si.
