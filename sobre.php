<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre - Pulse</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/site.js" defer></script>

</head>

<body>
    <nav class="menu-container">
        <div class="logo">
            <img src="img/logopulse.png" PULSE>
            <span>PULSE</span>
        </div>

        <ul class="menu-centro">
            <li><a href="index.php">Início</a></li>
            <li class="tem-submenu">
                <a href="produtos.php">Serviços ▾</a>
                <ul class="submenu">
                    <li><a href="produtos.php">Produtos</a></li>
                    <li><a href="carrinho.php">Carrinho</a></li>
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

    <main class="sobre-container">

        <section class="sobre-hero">
            <span class="sobre-tag">QUEM SOMOS</span>

            <h1>
                MAIS QUE TÊNIS.<br>
                <span>UM ESTILO EM MOVIMENTO.</span>
            </h1>

            <p class="sobre-subtitulo">
                Seu estilo. Sua identidade. Seu movimento.
            </p>
        </section>

        <section class="sobre-conteudo">

            <div class="sobre-texto">
                <h2>A NOSSA <span>HISTÓRIA</span></h2>

                <p>
                    A PULSE nasceu de uma paixão: o universo dos sneakers
                    e tudo o que ele representa. Nossa história começou
                    durante a pandemia, a partir do desejo de tornar mais
                    fácil o acesso a tênis de qualidade, aproximando
                    apaixonados por calçados, entusiastas e colecionadores
                    de modelos que fazem a diferença no visual.
                </p>

                <p>
                    O que começou como uma ideia se transformou em um
                    propósito: oferecer uma experiência de compra simples,
                    acessível e conectada à cultura sneaker.
                </p>

                <p>
                    Acreditamos que estilo não precisa ser complicado,
                    muito menos inacessível. Por isso, buscamos reunir
                    variedade, qualidade e preços justos, trazendo opções
                    para diferentes estilos, ocasiões e personalidades.
                </p>

                <p>
                    Na PULSE, cada tênis representa mais do que um produto.
                    Representa identidade, atitude e liberdade para
                    expressar quem você é.
                </p>
            </div>

        </section>

        <section class="sobre-destaque">
            <h2>FEEL THE <span>MOVEMENT.</span></h2>
            <p>Nosso movimento é acompanhar o seu.</p>
        </section>

    </main>

    <footer class="footer-site">
        <p>© <?= date("Y") ?> PULSE </p>
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
                <p>Somos um site dedicado a facilitar suas compras e organizar o seu dia a dia com praticidade e estilo.
                </p>
            </div>
        </div>
    </footer>

</body>

</html>