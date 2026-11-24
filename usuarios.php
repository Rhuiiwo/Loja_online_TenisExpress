<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/database/conexao.php';
header('Cache-Control: no-store');
if (empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
$busca = is_string($_GET['busca'] ?? null) ? trim($_GET['busca']) : '';
$usuarios = [];
$mensagem = '';
$aviso = $_SESSION['pulse_baixa_aviso'] ?? '';
unset($_SESSION['pulse_baixa_aviso']);
try {
    $pdo = pulse_db();
    if (!pulse_is_admin($pdo)) {
        http_response_code(403);
        exit('Acesso restrito a administradores.');
    }
    if (mb_strlen($busca) > 190) {
        $mensagem = 'Informe até 190 caracteres na pesquisa.';
    } else {
        $sql = 'SELECT u.id, u.nome, u.email, s.nome AS setor, p.nome AS perfil, u.status
                FROM usuarios AS u
                INNER JOIN setores AS s ON u.setor_id = s.id
                INNER JOIN perfis AS p ON u.perfil_id = p.id
                WHERE u.status = :status';
        $parametros = ['status' => 'Ativo'];
        if ($busca !== '') {
            $sql .= " AND (u.nome LIKE :nome ESCAPE '!' OR u.email LIKE :email ESCAPE '!')";
            $termo = '%' . strtr($busca, ['!' => '!!', '%' => '!%', '_' => '!_']) . '%';
            $parametros['nome'] = $termo;
            $parametros['email'] = $termo;
        }
        $consulta = $pdo->prepare($sql . ' ORDER BY u.nome ASC, u.id ASC');
        $consulta->execute($parametros);
        $usuarios = $consulta->fetchAll();
    }
} catch (PDOException $exception) {
    error_log('PULSE: falha na consulta de usuários.');
    http_response_code(503);
    $mensagem = 'Não foi possível consultar os usuários. Tente novamente mais tarde.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestão de usuários - PULSE</title>
<link rel="stylesheet" href="css/style.css">
<script src="js/site.js" defer></script>
<style>
#confirmar-exclusao { background:#191919; color:#f2f2ed; border:1px solid #555; border-radius:12px; padding:24px; max-width:min(440px,85vw); }
#confirmar-exclusao::backdrop { background:rgba(0,0,0,.75); }
#confirmar-exclusao .btn-primario { background:#C7FF39; color:#090909; }
#confirmar-exclusao .btn-secundario { color:#f2f2ed; }
.btn-excluir { background:transparent; color:#ef4444; border:1px solid #ef4444; border-radius:6px; padding:8px 12px; cursor:pointer; }
</style>
</head>
<body>
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
        </ul>

        <div class="menu-direito">
            <a href="login.php" class="link-login">Entrar / Login</a>
            <button class="theme-toggle" type="button" id="theme-toggle" aria-label="Ativar tema escuro" aria-pressed="false">🌙</button>
        </div>
    </nav>
<main class="dashboard-container">
    <h1>Gestão de usuários</h1>
    <?php if ($aviso !== ''): ?><p role="status"><?= pulse_h($aviso) ?></p><?php endif; ?>
    <p>Consulte os usuários ativos cadastrados na PULSE.</p>
    <section class="tabela-container">
        <form method="get" action="usuarios.php">
            <div class="form-grupo">
                <label for="busca">Pesquisar por nome ou e-mail</label>
                <input type="search" id="busca" name="busca" maxlength="190" value="<?= pulse_h($busca) ?>" placeholder="Digite um nome ou e-mail">
            </div>
            <div class="banner-botoes">
                <button class="btn-primario" type="submit">Pesquisar</button>
                <a class="btn-secundario" href="usuarios.php">Limpar</a>
                <a class="btn-secundario" href="dashboard.php">Voltar</a>
            </div>
        </form>
        <?php if ($mensagem !== ''): ?>
            <p role="alert"><?= pulse_h($mensagem) ?></p>
        <?php elseif (!$usuarios): ?>
            <p role="status">Nenhum usuário encontrado.</p>
        <?php else: ?>
            <p role="status"><?= count($usuarios) ?> usuário(s) encontrado(s).</p>
            <div class="tabela-responsiva">
                <table class="tabela-dashboard">
                    <thead><tr><th scope="col">ID</th><th scope="col">Nome</th><th scope="col">E-mail</th><th scope="col">Setor</th><th scope="col">Perfil</th><th scope="col">Status</th><th scope="col">Ações</th></tr></thead>
                    <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= pulse_h((string) $usuario['id']) ?></td>
                            <td><?= pulse_h($usuario['nome']) ?></td>
                            <td><?= pulse_h($usuario['email']) ?></td>
                            <td><?= pulse_h($usuario['setor']) ?></td>
                            <td><?= pulse_h($usuario['perfil']) ?></td>
                            <td><span class="status-badge status-concluido"><?= pulse_h($usuario['status']) ?></span></td>
                            <td><button type="button" class="btn-excluir" data-id="<?= pulse_h((string) $usuario['id']) ?>" data-nome="<?= pulse_h($usuario['nome']) ?>" data-email="<?= pulse_h($usuario['email']) ?>">Excluir</button></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
    <dialog id="confirmar-exclusao" aria-labelledby="titulo-exclusao">
        <h2 id="titulo-exclusao">Deseja realmente excluir este usuário?</h2>
        <p id="usuario-selecionado"></p>
        <form method="post" action="excluir_usuario.php" class="banner-botoes">
            <input type="hidden" name="csrf_token" value="<?= pulse_h(pulse_csrf_token()) ?>">
            <input type="hidden" name="usuario_id" id="usuario-exclusao-id">
            <button class="btn-secundario" type="button" id="cancelar-exclusao" autofocus>Cancelar</button>
            <button class="btn-primario" type="submit">Confirmar</button>
        </form>
    </dialog>
    <p id="resultado-confirmacao" role="status" aria-live="polite"></p>
</main>
<script>
const modalExclusao = document.getElementById('confirmar-exclusao');
const resultadoConfirmacao = document.getElementById('resultado-confirmacao');
document.querySelectorAll('.btn-excluir').forEach(botao => {
    botao.addEventListener('click', () => {
        document.getElementById('usuario-exclusao-id').value = botao.dataset.id;
        document.getElementById('usuario-selecionado').textContent =
            'ID: ' + botao.dataset.id + ' — ' + botao.dataset.nome + ' (' + botao.dataset.email + ')';
        resultadoConfirmacao.textContent = '';
        modalExclusao.showModal();
    });
});
document.getElementById('cancelar-exclusao').addEventListener('click', () => modalExclusao.close());
modalExclusao.addEventListener('close', () => {
    document.getElementById('usuario-exclusao-id').value = '';
    resultadoConfirmacao.textContent = 'Operação cancelada.';
});
</script>
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
