<?php
require_once __DIR__ . '/includes/auth.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!pulse_valid_csrf($_POST['csrf_token'] ?? null)) {
        $mensagem = 'Sua sessão expirou. Atualize a página e tente novamente.';
    } else {
        $nome = trim((string) ($_POST['nome'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $senha = (string) ($_POST['senha'] ?? '');

        if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8) {
            $mensagem = 'Informe um nome, um e-mail válido e uma senha com pelo menos 8 caracteres.';
        } else {
            try {
                require_once __DIR__ . '/database/conexao.php';
                $pdo = pulse_db();
                $consulta = $pdo->prepare(
                    'INSERT INTO usuarios (nome, email, senha_hash) VALUES (:nome, :email, :senha_hash)'
                );
                $consulta->execute([
                    'nome' => $nome,
                    'email' => $email,
                    'senha_hash' => password_hash($senha, PASSWORD_DEFAULT),
                ]);

                session_regenerate_id(true);
                $_SESSION['usuario_id'] = (int) $pdo->lastInsertId();
                $_SESSION['usuario_nome'] = $nome;
                $_SESSION['usuario_tipo'] = 'cliente';

                header('Location: dashboard.php');
                exit;
            } catch (PDOException $exception) {
                if ($exception->getCode() === '23000') {
                    $mensagem = 'Este e-mail já está cadastrado.';
                } else {
                    error_log($exception->getMessage());
                    $mensagem = 'Não foi possível concluir o cadastro. Confira a configuração do banco de dados.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Pulse</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/site.js" defer></script>

</head>
<body>
    <header>
    <nav class="menu-container">
        <div class="logo">
            <img src="img/logopulse.png" PULSE">
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
        <h2>Criar uma conta</h2>
        <p class="content-lead">Crie sua conta para reunir seus dados de acesso e agilizar sua experiência na PC Store.</p>

        <form action="cadastro.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= pulse_h(pulse_csrf_token()) ?>">
            <p>
                <label for="nome">Nome completo:</label>
                <input type="text" id="nome" name="nome" autocomplete="name" required>
            </p>
            <p>
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" placeholder="aluno@email.com" autocomplete="email" required>
            </p>
            <p>
                <label for="senha">:</label>
                <input type="password" id="senha" name="senha" autocomplete="new-password" minlength="8" required>
            </p>
            <button type="submit">Cadastrar</button>
            <button type="reset">Limpar</button>
            <p class="form-status" role="status" aria-live="polite"><?= pulse_h($mensagem) ?></p>
        </form>
        <p class="text-center"><a class="button-link" href="login.php">Já tenho uma conta</a></p>
    </main>

    <footer>
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
</html>Senha