<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho - Pulse</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/site.js" defer></script>
    <script src="js/carrinho.js" defer></script>
</head>

<body>
    <nav class="menu-container">
        <div class="logo">
            <img src="img/logopulse.png" alt="Logo PULSE">
            <span>PULSE</span>
        </div>

        <ul class="menu-centro">
            <li><a href="index.php">Início</a></li>
            <li class="tem-submenu">
                <a href="produtos.php">Serviços ▾</a>
                <ul class="submenu">
                    <li><a href="produtos.php">Produtos</a></li>
                    <li><a href="carrinho.php" id="link-menu-carrinho">Carrinho (0)</a></li>
                </ul>
            </li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="sobre.php">Sobre</a></li>
            <li><a href="contato.php">Contato</a></li>
        </ul>

        <div class="menu-direito">
            <a href="login.php" class="link-login">Entrar / Login</a>
            <button class="theme-toggle" type="button" id="theme-toggle" aria-label="Ativar tema escuro" aria-pressed="false">🌙</button>
        </div>
    </nav>

    <div class="carrinho-container-geral">
        <div class="titulo-secao">
            <h1>SEU CARRINHO <span id="contador-titulo-carrinho" style="font-size: 16px; font-weight: 700; color: var(--pulse-lima);">(0 produtos)</span></h1>
            <p>Os itens do seu carrinho não estão reservados. Finalize esta compra para garantir o seu!</p>
        </div>

        <div class="carrinho-layout-destaque">
            <div class="produtos-selecionados">
                <h2>Itens Selecionados</h2>
                <ul class="lista-itens-carrinho" id="lista-itens-carrinho"></ul>
            </div>

            <div class="resumo-pedido" id="resumo-pedido-dinamico"></div>
        </div>
    </div>

    <footer class="footer-site">
        <p>© <?= date("Y") ?> PULSE</p>
        <div class="footer-conteudo">
            <div class="footer-coluna">
                <h3>Contatos</h3>
                <ul>
                    <li>E-mail: contato@pulse.com</li>
                    <li>Telefone: (81) 99999-9999</li>
                    <li>Recife - PE</li>
                </ul>
            </div>

            <div class="footer-coluna">
                <h3>Sobre nós</h3>
                <p>Somos um site dedicado a facilitar suas compras e organizar o seu dia a dia com praticidade e estilo.</p>
            </div>
        </div>
    </footer>
</body>

</html>