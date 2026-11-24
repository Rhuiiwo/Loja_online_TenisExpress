# Visão geral do sistema

## Apresentação

A PULSE é uma e-commerce de venda de tênis. O sistema oferece navegação pelo catálogo, busca, carrinho local, cadastro e autenticação de clientes e uma interface para revisão do método de pagamento.

## Objetivos e público

O projeto busca facilitar a compra de tênis, com várias opções de modelos e preços. A interface foi estruturada para permitir que visitantes pesquisem itens, consultem detalhes e montem um carrinho antes de acessar o checkout.

## Tecnologias

- PHP para renderização das páginas e operações de conta.
- MySQL para os dados persistentes definidos no esquema SQL.
- PDO para acesso ao banco de dados.
- HTML semântico, CSS responsivo e JavaScript para interações no navegador.

## Componentes e responsabilidades

| Componente | Responsabilidade |
| --- | --- |
| `index.php` | Apresentação, carrossel e produtos em destaque. |
| `produtos.php` | Catálogo, busca e ações para adicionar itens ao carrinho. |
| `carrinho.php` | Informações detalhadas dos valores dos produtos selecionados. |
| `dashboard.php` | Painel demonstrativo e acesso à gestão de usuários para administradores. |
| `pagamento.php` | Seleção do método e campos correspondentes. |
| `cadastro.php` e `login.php` | Criação de conta e autenticação. |
| `usuarios.php` | Listagem, pesquisa, JOIN e confirmação de baixa. |
| `excluir_usuario.php` | Processamento autorizado da baixa lógica por POST. |
| `includes/auditoria.php` | Registro de operações de auditoria. |
| `includes/auth.php` | Sessão, proteção CSRF e escape de conteúdo HTML. |
| `database/conexao.php` | Conexão PDO reutilizável com o MySQL. |
| `js/site.js` | Tema, carrinho, carrossel, checkout e pesquisa. |
| `css/style.css` | Identidade visual, componentes e adaptação a telas menores. |

## Fluxos principais

### Consulta e seleção de produtos

1. O visitante navega do início para o catálogo.
2. A busca filtra os cartões pelo texto digitado.
3. A ação de adicionar atualiza o carrinho salvo no armazenamento local do navegador.
4. O painel permite remover itens e consultar o total.

### Conta de cliente

1. O cadastro valida nome, e-mail, senha e referências de setor e perfil no servidor.
2. O servidor grava a senha em formato de hash.
3. O login consulta o usuário e verifica a senha.
4. Tokens CSRF são conferidos antes de processar os formulários.

### Gestão administrativa de usuários

O administrador autenticado acessa a listagem de usuários ativos, com pesquisa por nome ou e-mail e associação de setores e perfis via JOIN. A confirmação de exclusão antecede a baixa lógica, processada no servidor por POST com validação CSRF e autorização. O registro permanece no banco com status Baixa e data de baixa; a operação é auditada.

### Limites do sistema

Os indicadores e pedidos demonstrativos do Dashboard não comprovam integração comercial com o banco. O carrinho permanece local ao navegador. A tabela de sessões existe, mas sua integração completa não foi validada nesta Sprint. A entrega concentra-se no cadastro e na gestão de usuários.

### Checkout

O visitante escolhe débito, crédito ou Pix. A interface exibe os campos aplicáveis e valida o preenchimento no navegador. Os dados financeiros não são guardados no carrinho nem no banco. A conclusão de pagamentos reais requer integração com um provedor de pagamentos.

## Execução local

Consulte [README.md](../README.md) para os requisitos, instalação do banco e inicialização pelo XAMPP.
