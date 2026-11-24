<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/database/conexao.php';
require_once __DIR__ . '/includes/auditoria.php';
$mensagem = '';
$erros = [];
$mensagem = '';
$nome = '';
$email = '';
$setor_id = null;
$perfil_id = null;
$setores = [];
$perfis = [];
$referenciasDisponiveis = false;
$pdo = null;
try {
    $pdo = pulse_db();
    // Carregar somente setores ativos.
    $consultaSetores = $pdo->query(
        'SELECT id, nome
         FROM setores
         WHERE ativo = 1
         ORDER BY nome ASC'
    );
    $setores = $consultaSetores->fetchAll();
    // Cadastro publico: somente perfil Cliente.
    $consultaPerfis = $pdo->prepare(
        'SELECT id, nome
         FROM perfis
         WHERE ativo = 1
         AND nome = :nome
         ORDER BY nome ASC'
    );
    $consultaPerfis->execute([
        'nome' => 'Cliente'
    ]);
    $perfis = $consultaPerfis->fetchAll();
    $referenciasDisponiveis =
        !empty($setores) && !empty($perfis);
    if (!$referenciasDisponiveis) {
        $mensagem = 'Cadastro temporariamente indisponível. Não existem setores ou perfis disponíveis.';
    }
} catch (PDOException $exception) {
    error_log('PULSE: falha ao consultar referências.');
    $mensagem = 'Serviço temporariamente indisponível. Tente novamente mais tarde.';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultadoAuditoria = 'Falha: validação de entrada.';
    $responsavelId = isset($_SESSION['usuario_id']) ? (int) $_SESSION['usuario_id'] : null;
    $nome = is_string($_POST['nome'] ?? null) ? trim($_POST['nome']) : '';
    $email = is_string($_POST['email'] ?? null) ? strtolower(trim($_POST['email'])) : '';
    $senha = is_string($_POST['senha'] ?? null) ? $_POST['senha'] : '';
    $setor_id = filter_var($_POST['setor_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $perfil_id = filter_var($_POST['perfil_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $token = $_POST['csrf_token'] ?? null;
    if (!is_string($token) || !pulse_valid_csrf($token)) {
        $resultadoAuditoria = 'Falha: token CSRF inválido.';
        $mensagem = 'Sua sessão expirou. Atualize a página e tente novamente.';
    } elseif (!$referenciasDisponiveis) {
        $resultadoAuditoria = 'Falha: referências indisponíveis.';
        $mensagem = 'Cadastro temporariamente indisponível.';
    } else {
        if ($nome === '' || mb_strlen($nome) > 150) {
            $erros['nome'] = 'Informe um nome válido com até 150 caracteres.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
            $erros['email'] = 'Informe um e-mail válido com até 190 caracteres.';
        }
        if (strlen($senha) < 8 || strlen($senha) > 72 || strpos($senha, "\0") !== false) {
            $erros['senha'] = 'Informe uma senha válida de 8 a 72 bytes.';
        }
        try {
            $validarSetor = $pdo->prepare('SELECT id FROM setores WHERE id = :id AND ativo = 1');
            $validarSetor->execute(['id' => $setor_id ?: 0]);
            if (!$validarSetor->fetchColumn()) {
                $erros['setor_id'] = 'Selecione um setor válido e ativo.';
            }
            $validarPerfil = $pdo->prepare('SELECT id FROM perfis WHERE id = :id AND ativo = 1 AND nome = :nome');
            $validarPerfil->execute(['id' => $perfil_id ?: 0, 'nome' => 'Cliente']);
            if (!$validarPerfil->fetchColumn()) {
                $erros['perfil_id'] = 'Selecione um perfil permitido para cadastro.';
            }
            if (!$erros) {
                $pdo->beginTransaction();
                $consulta = $pdo->prepare(
                    'INSERT INTO usuarios
                    (
                        nome,
                        email,
                        senha_hash,
                        tipo,
                        setor_id,
                        perfil_id,
                        status
                    )
                    VALUES
                    (
                        :nome,
                        :email,
                        :senha_hash,
                        :tipo,
                        :setor_id,
                        :perfil_id,
                        :status
                    )'
                );
                $consulta->execute([
                    'nome' => $nome,
                    'email' => $email,
                    'senha_hash' => password_hash(
                        $senha,
                        PASSWORD_DEFAULT
                    ),
                    'tipo' => 'cliente',
                    'setor_id' => $setor_id,
                    'perfil_id' => $perfil_id,
                    'status' => 'Ativo'
                ]);
                $usuario_id = (int) $pdo->lastInsertId();
                pulse_registrar_auditoria($pdo, 'CADASTRO', 'Sucesso: usuário cadastrado.', $usuario_id, $responsavelId);
                $pdo->commit();
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuario_id;
                $_SESSION['usuario_nome'] = $nome;
                $_SESSION['usuario_tipo'] = 'cliente';
                $_SESSION['pulse_cadastro_sucesso'] = 'Cadastro realizado com sucesso!';
                header('Location: dashboard.php', true, 303);
                exit;
            }
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $resultadoAuditoria = 'Falha: erro na operação de cadastro.';
            $codigoMySQL = (int) ($exception->errorInfo[1] ?? 0);
            if ($codigoMySQL === 1062) {
                $resultadoAuditoria = 'Falha: duplicidade de registro.';
                $erros['email'] = 'Este e-mail já está cadastrado.';
            } elseif ($codigoMySQL === 1452) {
                $resultadoAuditoria = 'Falha: relacionamento inválido.';
                $mensagem = 'O setor ou perfil selecionado não está disponível.';
            } else {
                error_log('PULSE: falha ao cadastrar usuário.');
                $mensagem = 'Não foi possível concluir o cadastro. Tente novamente mais tarde.';
            }
        }
    }
    // Registra recusas fora da transação revertida, sem dados sensíveis.
    if ($pdo instanceof PDO) {
        try {
            pulse_registrar_auditoria($pdo, 'CADASTRO', $resultadoAuditoria, null, $responsavelId);
        } catch (Throwable $exception) {
            error_log('PULSE: falha ao registrar auditoria de cadastro.');
        }
    } else {
        error_log('PULSE: cadastro recusado; banco indisponível.');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Pulse</title>
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
            <li>
                <a href="index.php">Início</a>
            </li>
            <li class="tem-submenu">
                <a href="produtos.php">Serviços ▾</a>
                <ul class="submenu">
                    <li>
                        <a href="produtos.php">Produtos</a>
                    </li>
                    <li>
                        <a href="carrinho.php">Carrinho</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="dashboard.php">Dashboard</a>
            </li>
            <li>
                <a href="sobre.php">Sobre</a>
            </li>
            <li>
                <a href="contato.php">Contato</a>
            </li>
        </ul>
        <div class="menu-direito">
            <a href="login.php" class="link-login">
                Entrar / Login
            </a>
            <button class="theme-toggle"
                    type="button"
                    id="theme-toggle"
                    aria-label="Ativar tema escuro"
                    aria-pressed="false">
                🌙
            </button>
        </div>
    </nav>
</header>
<main>
    <h2>Criar uma conta</h2>
    <p class="content-lead">
        Crie sua conta para acessar todos os benefícios na PULSE.
    </p>
    <form action="cadastro.php" method="post">
        <input type="hidden"
               name="csrf_token"
               value="<?= pulse_h(pulse_csrf_token()) ?>">
        <p>
            <label for="nome">Nome completo:</label>
            <input type="text"
                   id="nome"
                   name="nome"
                    <?= isset($erros['nome']) ? 'aria-invalid="true" aria-describedby="erro-nome"' : '' ?>
                   value="<?= pulse_h($nome) ?>"
                   autocomplete="name"
                   maxlength="150"
                   required>
        <?php if (isset($erros['nome'])): ?>
                <span id="erro-nome" role="alert" style="display:block;color:#e05252;">
                    <?= pulse_h($erros['nome']) ?>
                </span>
            <?php endif; ?>
        </p>
        <p>
            <label for="email">E-mail:</label>
            <input type="email"
                   id="email"
                   name="email"
                    <?= isset($erros['email']) ? 'aria-invalid="true" aria-describedby="erro-email"' : '' ?>
                   value="<?= pulse_h($email) ?>"
                   placeholder="aluno@email.com"
                   autocomplete="email"
                   maxlength="190"
                   required>
        <?php if (isset($erros['email'])): ?>
                <span id="erro-email" role="alert" style="display:block;color:#e05252;">
                    <?= pulse_h($erros['email']) ?>
                </span>
            <?php endif; ?>
        </p>
        <p>
            <label for="setor_id">Setor:</label>
            <select id="setor_id"
                    name="setor_id"
                    <?= isset($erros['setor_id']) ? 'aria-invalid="true" aria-describedby="erro-setor_id"' : '' ?>
                    required
                    <?= empty($setores) ? 'disabled' : '' ?>>
                <option value="">
                    <?= empty($setores)
                        ? 'Nenhum setor disponível'
                        : 'Selecione um setor' ?>
                </option>
                <?php foreach ($setores as $setor): ?>
                    <option
                        value="<?= (int) $setor['id'] ?>"
                        <?= $setor_id === (int) $setor['id']
                            ? 'selected' : '' ?>>
                        <?= pulse_h($setor['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php if (isset($erros['setor_id'])): ?>
                <span id="erro-setor_id" role="alert" style="display:block;color:#e05252;">
                    <?= pulse_h($erros['setor_id']) ?>
                </span>
            <?php endif; ?>
        </p>
        <p>
            <label for="perfil_id">Perfil:</label>
            <select id="perfil_id"
                    name="perfil_id"
                    <?= isset($erros['perfil_id']) ? 'aria-invalid="true" aria-describedby="erro-perfil_id"' : '' ?>
                    required
                    <?= empty($perfis) ? 'disabled' : '' ?>>
                <option value="">
                    <?= empty($perfis)
                        ? 'Nenhum perfil disponível'
                        : 'Selecione um perfil' ?>
                </option>
                <?php foreach ($perfis as $perfil): ?>
                    <option
                        value="<?= (int) $perfil['id'] ?>"
                        <?= $perfil_id === (int) $perfil['id']
                            ? 'selected' : '' ?>>
                        <?= pulse_h($perfil['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php if (isset($erros['perfil_id'])): ?>
                <span id="erro-perfil_id" role="alert" style="display:block;color:#e05252;">
                    <?= pulse_h($erros['perfil_id']) ?>
                </span>
            <?php endif; ?>
        </p>
        <p>
            <label for="senha">Senha:</label>
            <input type="password"
                   id="senha"
                   name="senha"
                    <?= isset($erros['senha']) ? 'aria-invalid="true" aria-describedby="erro-senha"' : '' ?>
                   autocomplete="new-password"
                   minlength="8"
                   maxlength="72"
                   required>
        <?php if (isset($erros['senha'])): ?>
                <span id="erro-senha" role="alert" style="display:block;color:#e05252;">
                    <?= pulse_h($erros['senha']) ?>
                </span>
            <?php endif; ?>
        </p>
        <button type="submit"
                <?= !$referenciasDisponiveis ? 'disabled' : '' ?>>
            Cadastrar
        </button>
        <button type="reset">
            Limpar
        </button>
        <p class="form-status"
           role="status"
           aria-live="polite">
            <?= pulse_h($mensagem ?? '') ?>
        </p>
    </form>
    <p class="text-center">
        <a class="button-link" href="login.php">
            Já tenho uma conta
        </a>
    </p>
</main>
<footer>
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
            <p>
                Somos um site dedicado a facilitar suas compras
                e organizar o seu dia a dia com praticidade e estilo.
            </p>
        </div>
    </div>
</footer>
</body>
</html>
