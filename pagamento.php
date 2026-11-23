<!DOCTYPE html>
<html lang="pt-BR">
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
    </header>

    <main>
        <h2>Pagamento</h2>
        <p class="content-lead">Selecione uma forma de pagamento e informe os dados solicitados para concluir seu pedido com praticidade.</p>
        <form id="payment-form" action="" method="post">
            <h3>Forma de pagamento</h3>
            <fieldset class="payment-methods">
                <legend>Selecione uma opção</legend>
                <label><input type="radio" name="metodo" value="debito" required> Débito</label>
                <label><input type="radio" name="metodo" value="credito"> Crédito</label>
                <label><input type="radio" name="metodo" value="pix"> Pix</label>
            </fieldset>
            <fieldset class="payment-fields" id="card-details" hidden disabled>
                <legend>Dados do cartão</legend>
                <p>
                    <label for="card-holder">Nome impresso no cartão</label>
                    <input type="text" id="card-holder" name="titular_cartao" autocomplete="cc-name" required>
                </p>
                <p>
                    <label for="card-number">Número do cartão</label>
                    <input type="text" id="card-number" name="numero_cartao" inputmode="numeric" autocomplete="cc-number" pattern="[0-9 ]{13,19}" maxlength="19" placeholder="0000 0000 0000 0000" required>
                </p>
                <div class="payment-fields-row">
                    <p>
                        <label for="card-expiration">Validade</label>
                        <input type="text" id="card-expiration" name="validade_cartao" inputmode="numeric" autocomplete="cc-exp" pattern="[0-9]{2}/[0-9]{2}" placeholder="MM/AA" maxlength="5" required>
                    </p>
                    <p>
                        <label for="card-security-code">Código de segurança</label>
                        <input type="password" id="card-security-code" name="codigo_seguranca" inputmode="numeric" autocomplete="cc-csc" pattern="[0-9]{3,4}" maxlength="4" required>
                    </p>
                </div>
            </fieldset>
            <fieldset class="payment-fields" id="pix-details" hidden disabled>
                <legend>Dados do Pix</legend>
                <p>
                    <label for="pix-key">Chave Pix</label>
                    <input type="text" id="pix-key" name="chave_pix" autocomplete="off" required>
                </p>
            </fieldset>
            <button class="pay-button" type="button" id="review-payment">Revisar pagamento</button>
            <p class="form-status" id="payment-status" role="status" aria-live="polite"></p>
        </form>
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