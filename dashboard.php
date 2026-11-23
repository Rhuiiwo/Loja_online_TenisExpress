<?php
require_once __DIR__ . '/includes/auth.php';
$usuarioNome = $_SESSION['usuario_nome'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pulse</title>
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

    <div class="dashboard-container">

        <div class="titulo-secao">
            <h1>Dashboard</h1>
            <p>Painel administrativo para acompanhar pedidos, métricas e destaques.</p>
        </div>

        <div class="indicadores-grid">
            <div class="kpi-card">
                <div class="kpi-titulo">Pedidos Hoje</div>
                <div class="kpi-valor">48</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-titulo">Produtos</div>
                <div class="kpi-valor">32</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-titulo">Clientes</div>
                <div class="kpi-valor">127</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-titulo">Faturamento</div>
                <div class="kpi-valor">R$ 2.480</div>
            </div>
        </div>

        <div class="tabela-secao">
            <div class="titulo-secao cabecalho-tabela">
                <h2>Pedidos Recentes & Principais Clientes</h2>
                <p>Acompanhe as últimas transações, o volume gasto pelos clientes e o status atual de entrega.</p>
            </div>

            <div class="tabela-responsiva">
                <table class="tabela-dashboard">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Total Gasto</th>
                            <th>Data</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pedido-id">#1048</td>
                            <td>Mariana Souza</td>
                            <td class="pedido-valor">R$ 450,00</td>
                            <td class="pedido-data">Hoje, 14:22</td>
                            <td><span class="status-badge status-concluido">Concluído</span></td>
                        </tr>
                        <tr>
                            <td class="pedido-id">#1047</td>
                            <td>Carlos Eduardo</td>
                            <td class="pedido-valor">R$ 890,00</td>
                            <td class="pedido-data">Hoje, 11:05</td>
                            <td><span class="status-badge status-processamento">Em Processamento</span></td>
                        </tr>
                        <tr>
                            <td class="pedido-id">#1046</td>
                            <td>Ana Beatriz Lima</td>
                            <td class="pedido-valor">R$ 320,00</td>
                            <td class="pedido-data">Ontem</td>
                            <td><span class="status-badge status-enviado">Enviado</span></td>
                        </tr>
                        <tr>
                            <td class="pedido-id">#1045</td>
                            <td>Lucas Gabriel</td>
                            <td class="pedido-valor">R$ 1.250,00</td>
                            <td class="pedido-data">21/09/2026</td>
                            <td><span class="status-badge status-concluido">Concluído</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="galeria-secao">
            <div class="titulo-secao">
                <h2>Destaques da Semana</h2>
                <p>Principais lançamentos em exibição.</p>
            </div>
            <div class="galeria-grid">
                <figure>
                    <img src="img/tenis_01.webp" alt="Tênis Adidas Branco e Preto">
                    <figcaption>Tênis Adidas Ownthegame 3.0 - Branco+Preto</figcaption>
                </figure>
                <figure>
                    <img src="img/Tenis_02.jpg" alt="Tênis Adidas Vermelho e Preto">
                    <figcaption>Tênis Adidas Ownthegame 3.0 - Vermelho+Preto</figcaption>
                </figure>
            </div>
        </div>

    </div>

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