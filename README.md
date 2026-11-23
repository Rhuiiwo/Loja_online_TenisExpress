# PULSE

A PULSE é um e-commerce de venda de tênis desenvolvido com PHP, HTML, CSS, JavaScript e MySQL. O projeto reúne catálogo de produtos, carrinho no navegador, cadastro e autenticação de clientes, painel administrativo e formulário de pagamento.

## Requisitos

- XAMPP com Apache, PHP e MySQL.
- Navegador com suporte a JavaScript.
- Git, para clonar o repositório, ou um programa para extrair arquivos ZIP.

## Instalação local

1. Instale o [XAMPP](https://www.apachefriends.org/) com Apache, PHP e MySQL.
2. Baixe o projeto para a pasta `C:\xampp\htdocs\pulse`.

   Para clonar com Git, abra o PowerShell e execute:

   ```powershell
   Set-Location C:\xampp\htdocs
   git clone https://github.com/Rhuiiwo/Loja_online_TenisExpress
   ```

   Se baixar o projeto como ZIP, extraia o conteúdo para `C:\xampp\htdocs\pule`.

3. Abra o **XAMPP Control Panel** e clique em **Start** para Apache e MySQL. Aguarde os dois serviços indicarem que estão ativos.

4. Configure o banco pelo phpMyAdmin:
   1. Abra [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   2. Acesse o phpmyadimin no navegador e clique em **Importar**, escolha o arquivo `database/instruction.sql` dentro da pasta do projeto e clique em Confimar.
   3. Se a importação informar que o banco `db_pule` não existe ou não foi selecionado, crie esse banco, selecione-o e repita a importação.

5. Confira [`database/conexao.php`](./database/conexao.php). A configuração padrão é host `localhost`, banco `db_pulse`, usuário `root` e senha vazia. Se alterou as credenciais do MySQL, atualize esse arquivo para usar os mesmos valores.

6. Acesse o site pelo navegador em [http://localhost/pulse/](http://localhost/pulse/). Também é possível abrir diretamente [http://localhost/pulse/index.php](http://localhost/pulse/index.php).

> Não abra `index.php` diretamente pelo Explorador de Arquivos. O Apache precisa estar ativo para executar o PHP.

### Solução de problemas de acesso

- **`ERR_CONNECTION_REFUSED`:** confirme que o Apache está ativo no XAMPP e tente novamente.
- **Página não encontrada:** confira se a pasta do projeto está diretamente em `C:\xampp\htdocs\pulse`.
- **Erro de conexão com o banco:** confirme que o MySQL está ativo, que o banco `db_pulse` foi importado e que as credenciais em `database/conexao.php` estão corretas.
- **Apache usa outra porta:** se estiver configurado, por exemplo, na porta `8080`, acesse `http://localhost:8080/pulse/`.

## Funcionalidades

- Carrinho com inclusão, remoção e cálculo do total; os itens ficam no armazenamento local do navegador.
- Cadastro e login com senha armazenada em hash e validação CSRF nos formulários.
- Alternância entre temas claro e escuro e carrossel na página inicial.
- Formulário de pagamento com campos que variam conforme o método escolhido e validação no navegador.

## Estrutura do projeto

```text
Pulse/
├── index.php                 # Página inicial
├── produtos.php              # Catálogo e busca
├── carrinho.php              # Detalhes dos itens
├── sobre.php                 # Informações da loja
├── contatos.php              # Canais de contato
├── cadastro.php              # Cadastro de cliente
├── login.php                 # Autenticação
├── dashboard.php             # Painel e carrinho
├── pagamento.php             # Revisão dos dados de pagamento
├── css/
│   └── styles.css             # Estilos da interface
├── js/
│   └── site.js                # Interações do site
├── database/
│   ├── conexao.php            # Conexão com MySQL
│   └── instruction.sql        # Estrutura do banco de dados
├── includes/
│   └── auth.php               # Sessão, CSRF e funções de autenticação
├── docs/                      # Documentação complementar
├── img/                       # Imagens da loja e dos produtos
└── font/                      # Arquivos de fontes
```

## Documentação

- [Visão geral e arquitetura](./docs/visao-geral.md)
- [Manual de uso](./docs/manual-do-usuario.md)
- [Modelo de dados](./docs/modelo-de-dados.md)

## Segurança e operação

As senhas de conta são protegidas com `password_hash` e verificadas com `password_verify`. O token CSRF protege as operações de cadastro e login. O carrinho não contém dados de pagamento.

O formulário de pagamento valida os campos no navegador e não grava dados de cartão ou Pix. A aplicação não possui integração com provedor de pagamentos; antes de aceitar pagamentos reais, conecte um provedor compatível e não encaminhe dados financeiros a endpoints não preparados para isso. Configure credenciais e HTTPS antes de disponibilizar o site publicamente.