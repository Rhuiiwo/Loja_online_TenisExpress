<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Pulse</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/site.js" defer></script>
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

    <div class="produtos-container-geral">
        <div class="titulo-secao">
            <h1>Catálogo de Tênis</h1>
            <p>Cada produto traz sua especificação detalhada abaixo</p>
        </div>

        <!-- Resumo dinâmico dos itens selecionados e soma dos valores -->
        <div class="resumo-rapido-carrinho" id="resumo-carrinho" aria-live="polite">
            <span><strong>Itens no carrinho:</strong> <span id="qtd-itens-carrinho">0</span></span>
            <span><strong>Total:</strong> <span id="valor-total-carrinho">R$ 0,00</span></span>
            <a href="carrinho.php" class="btn-secundario">Ver Carrinho</a>
        </div>

        <div class="produtos-grid">

            <article class="produto-card">
                <div class="produto-imagem"><img src="img/tenis_03_campus.webp" alt="Tênis Adidas Campus"></div>
                <div class="produto-conteudo">
                    <span class="produto-categoria">Casual</span>
                    <h3 class="produto-titulo">Tênis Adidas Campus</h3>
                    <p class="produto-descricao">Estilo clássico em camurça com conforto superior para o dia a dia.</p>
                    <div class="produto-preco">R$ 599,90</div>
                    <button type="button" class="btn-primario btn-add-carrinho">Adicionar ao Carrinho</button>
                </div>
            </article>

            <article class="produto-card">
                <div class="produto-imagem"><img src="img/tenis_04_samba.webp" alt="Tênis Adidas Samba"></div>
                <div class="produto-conteudo">
                    <span class="produto-categoria">Originals</span>
                    <h3 class="produto-titulo">Tênis Adidas Samba</h3>
                    <p class="produto-descricao">O ícone das quadras adaptado para as ruas com muito estilo urbano.</p>
                    <div class="produto-preco">R$ 649,90</div>
                    <button type="button" class="btn-primario btn-add-carrinho">Adicionar ao Carrinho</button>
                </div>
            </article>

            <article class="produto-card">
                <div class="produto-imagem"><img src="img/tenis_05_superstar.webp" alt="Tênis Adidas SuperStar"></div>
                <div class="produto-conteudo">
                    <span class="produto-categoria">Urban</span>
                    <h3 class="produto-titulo">Tênis Adidas SuperStar</h3>
                    <p class="produto-descricao">A clássica biqueira concha que marcou gerações da cultura pop.</p>
                    <div class="produto-preco">R$ 549,90</div>
                    <button type="button" class="btn-primario btn-add-carrinho">Adicionar ao Carrinho</button>
                </div>
            </article>

            <article class="produto-card">
                <div class="produto-imagem"><img src="img/tenis_06_ultraboosts.webp" alt="Tênis Adidas Ultraboosts"></div>
                <div class="produto-conteudo">
                    <span class="produto-categoria">Performance</span>
                    <h3 class="produto-titulo">Tênis Adidas Ultraboosts</h3>
                    <p class="produto-descricao">Amortecimento máximo e retorno de energia incrível para sua corrida.</p>
                    <div class="produto-preco">R$ 899,90</div>
                    <button type="button" class="btn-primario btn-add-carrinho">Adicionar ao Carrinho</button>
                </div>
            </article>

            <article class="produto-card">
                <div class="produto-imagem"><img src="img/tenis_07_gazelle.webp" alt="Tênis Adidas Gazelle"></div>
                <div class="produto-conteudo">
                    <span class="produto-categoria">Retro</span>
                    <h3 class="produto-titulo">Tênis Adidas Gazelle</h3>
                    <p class="produto-descricao">Design minimalista inspirado nos anos 90 com toque moderno e leve.</p>
                    <div class="produto-preco">R$ 579,90</div>
                    <button type="button" class="btn-primario btn-add-carrinho">Adicionar ao Carrinho</button>
                </div>
            </article>

            <article class="produto-card">
                <div class="produto-imagem"><img src="img/kit_limpeza.webp" alt="Kit Limpeza"></div>
                <div class="produto-conteudo">
                    <span class="produto-categoria">Especial</span>
                    <h3 class="produto-titulo">Kit Limpeza</h3>
                    <p class="produto-descricao">Solução completa para manter seus calçados sempre limpos e novos.</p>
                    <div class="produto-preco">R$ 89,90</div>
                    <button type="button" class="btn-primario btn-add-carrinho">Adicionar ao Carrinho</button>
                </div>
            </article>

            <article class="produto-card">
                <div class="produto-imagem"><img src="img/tenis_08_adiopro.webp" alt="Tênis Adidas Adios PRO"></div>
                <div class="produto-conteudo">
                    <span class="produto-categoria">Performance</span>
                    <h3 class="produto-titulo">Tênis Adidas Adios PRO</h3>
                    <p class="produto-descricao">Criado para corredores velozes que buscam desempenho superior.</p>
                    <div class="produto-preco">R$ 799,90</div>
                    <button type="button" class="btn-primario btn-add-carrinho">Adicionar ao Carrinho</button>
                </div>
            </article>

            <article class="produto-card">
                <div class="produto-imagem"><img src="img/tenis_08_oos.webp" alt="Tênis Adidas OOs"></div>
                <div class="produto-conteudo">
                    <span class="produto-categoria">Originals</span>
                    <h3 class="produto-titulo">Tênis Adidas OOs</h3>
                    <p class="produto-descricao">Inspirado no skate e feito para celebrar materiais icônicos.</p>
                    <div class="produto-preco">R$ 379,90</div>
                    <button type="button" class="btn-primario btn-add-carrinho">Adicionar ao Carrinho</button>
                </div>
            </article>

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