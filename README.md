# PULSE

A PULSE é um e-commerce de venda de tênis desenvolvido com PHP, HTML, CSS, JavaScript e MySQL. O projeto reúne catálogo de produtos, carrinho no navegador, cadastro e autenticação de clientes, painel administrativo e formulário de pagamento.

## Requisitos e instalação local

- XAMPP com Apache, PHP, MariaDB/MySQL e extensão PDO MySQL (e mbstring).
- Navegador com JavaScript habilitado.

1. Extraia o projeto em `C:\xampp\htdocs\pulse`, sem criar uma pasta pulse adicional.
2. Inicie Apache e MySQL no XAMPP Control Panel.
3. Abra `http://localhost/phpmyadmin/` e prepare uma base vazia.
4. Importe os scripts abaixo, na ordem indicada.
5. Confira o banco de destino e as credenciais em `database/conexao.php`. O ambiente validado na Sprint utiliza `db_pulse_instalacao_teste`.
6. Abra `http://localhost/pulse/`.

### Ordem de instalação da Sprint 01

1. `database/instruction.sql`
2. `database/migrations/001_criacao_setores_perfis.sql`
3. `database/migrations/002_adequacao_tabela_usuarios.sql`
4. `database/migrations/003_relacionamentos_indices.sql`
5. `database/migrations/004_dados_ficticios_homologacao.sql`

Confira previamente os comandos CREATE DATABASE e USE de todos os scripts, ajustando-os para a mesma base de destino. Não execute migrations novamente em uma base já migrada. Preserve o banco original.

A instalação em base independente foi registrada com 15 tabelas, 19 chaves estrangeiras, cinco setores, cinco perfis, seis usuários fictícios (cinco ativos e um baixado) e nenhum usuário órfão. Esses números descrevem a instalação inicial, não a base após os testes funcionais.

### Solução de problemas

- Conexão recusada: confira o Apache e a porta configurada.
- Erro 404: confira o nome da pasta e a extensão PHP.
- Erro de banco: confira o serviço MySQL, a importação e a configuração PDO.
- Não abra arquivos PHP diretamente pelo Explorador de Arquivos.

## Funcionalidades

- Catálogo, busca, carrinho local e revisão de pagamento.
- Cadastro com setores e perfis consultados no banco, validação no servidor e senha em hash.
- Login de usuários ativos e gestão de usuários restrita a administradores.
- Listagem de ativos, pesquisa por nome/e-mail e JOIN com setores e perfis.
- Confirmação de exclusão, baixa lógica e auditoria de cadastro e baixa.

## Estrutura do projeto

```text
pulse/
├── index.php
├── produtos.php
├── carrinho.php
├── pagamento.php
├── sobre.php
├── contato.php
├── cadastro.php
├── login.php
├── dashboard.php
├── usuarios.php
├── excluir_usuario.php
├── css/style.css
├── js/site.js
├── js/carrinho.js
├── includes/auth.php
├── includes/auditoria.php
├── database/conexao.php
├── database/instruction.sql
├── database/migrations/
├── docs/
├── img/
└── font/
```

## Documentação

- [Visão geral](docs/visao-geral.md)
- [Manual do usuário](docs/manual-do-usuario.md)
- [Modelo de dados](docs/modelo-de-dados.md)
- [Relatório da Sprint 01](docs/relatorio-sprint-01.md)
- Evidências: `docs/evidencias/`

## Segurança e limites

O módulo utiliza PDO com consultas preparadas, `password_hash`, `password_verify`, proteção CSRF e autorização administrativa no servidor. A baixa é realizada por POST, sem remoção física do usuário. O cadastro público não concede perfil administrativo. O usuário root do MySQL não equivale a um administrador da aplicação.

O carrinho utiliza armazenamento local. O checkout não efetua pagamentos reais nem persiste dados financeiros. Utilize apenas dados fictícios nos testes. Antes de publicar, configure HTTPS e credenciais próprias, com privilégios mínimos.

## Conferência antes da entrega

Conferir os scripts definitivos, os caminhos de evidências, a matriz de testes e a instalação a partir do ZIP final. Não incluir backups, credenciais reais ou arquivos temporários de diagnóstico. A implementação funcional não substitui a homologação do pacote final.
