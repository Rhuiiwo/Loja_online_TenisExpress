<?php
require_once __DIR__ . '/includes/auth.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!pulse_valid_csrf($_POST['csrf_token'] ?? null)) {
        $mensagem = 'Sua sessão expirou. Atualize a página e tente novamente.';
    } else {
        $email = is_string($_POST['email'] ?? null) ? strtolower(trim($_POST['email'])) : '';
        $senha = is_string($_POST['senha'] ?? null) ? $_POST['senha'] : '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $senha === '') {
            $mensagem = 'Informe um e-mail válido e sua senha.';
        } else {
            try {
                require_once __DIR__ . '/database/conexao.php';
                $pdo = pulse_db();
                $consulta = $pdo->prepare(
                    'SELECT id, nome, senha_hash, tipo FROM usuarios WHERE email = :email AND status = :status LIMIT 1'
                );
                $consulta->execute(['email' => $email, 'status' => 'Ativo']);
                $usuario = $consulta->fetch();

                if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
                    session_regenerate_id(true);
                    $_SESSION['usuario_id'] = (int) $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    $_SESSION['usuario_tipo'] = $usuario['tipo'];

                    header('Location: dashboard.php');
                    exit;
                }

                $mensagem = 'E-mail ou senha inválidos.';
            } catch (PDOException | RuntimeException $exception) {
                error_log('PULSE: falha no login.');
                $mensagem = 'Não foi possível entrar. Confira a configuração do banco de dados.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pulse</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/site.js" defer></script>
</head>
<body>
    <header>
    <nav class="menu-container">
        <div class="logo">
            <img src="img/logopulse.png" alt="PULSE">
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
            <li><button class="theme-toggle" type="button" id="theme-toggle" aria-label="Ativar tema escuro" aria-pressed="false">🌙</button></li>
        </ul>
    </nav>
</header>    
<main>
    <div class="login-container-central">
        <div class="login-formulario">
            <form action="login.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= pulse_h(pulse_csrf_token()) ?>">
                <h2 class="login-titulo">Área de Login</h2>

                <div class="form-grupo">
                    <label for="email">Digite seu e-mail:</label>
                    <input type="email" id="email" name="email" placeholder="seuemail@email.com" required>
                </div>

                <div class="form-grupo">
                    <label for="senha">Digite sua senha:</label>
                    <input type="password" id="senha" name="senha" placeholder="digite sua senha" required>
                </div>

                <button type="submit" class="btn-primario">Entrar</button>
            </form>
            <p class="form-status" role="status"><?= pulse_h($mensagem) ?></p>

            <p class="text-center">
                <a class="button-link" href="cadastro.php">Criar uma conta</a>
            </p>
        </div>
    </div>
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