<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/database/conexao.php';
require_once __DIR__ . '/includes/auditoria.php';
header('Cache-Control: no-store');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Método não permitido.');
}
if (empty($_SESSION['usuario_id'])) {
    header('Location: login.php', true, 303);
    exit;
}
function pulse_baixa_resposta(string $mensagem): void {
    $_SESSION['pulse_baixa_aviso'] = $mensagem;
    header('Location: usuarios.php', true, 303);
    exit;
}
try {
    $pdo = pulse_db();
    if (!pulse_is_admin($pdo)) {
        http_response_code(403);
        exit('Acesso restrito a administradores.');
    }
    $responsavel = (int) $_SESSION['usuario_id'];
    if (!pulse_valid_csrf($_POST['csrf_token'] ?? null)) {
        pulse_registrar_auditoria($pdo, 'BAIXA', 'Falha: token CSRF inválido.', null, $responsavel);
        http_response_code(403);
        exit('Token CSRF inválido.');
    }
    $id = filter_var($_POST['usuario_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if (!$id || $id === $responsavel) {
        pulse_registrar_auditoria($pdo, 'BAIXA', 'Falha: identificador inválido ou baixa da própria conta.', null, $responsavel);
        http_response_code(400);
        exit('Identificador inválido ou baixa da própria conta não permitida.');
    }
    $pdo->beginTransaction();
    $consulta = $pdo->prepare('SELECT status FROM usuarios WHERE id = :id FOR UPDATE');
    $consulta->execute(['id' => $id]);
    $status = $consulta->fetchColumn();
    if ($status !== 'Ativo') {
        pulse_registrar_auditoria($pdo, 'BAIXA', 'Falha: usuário inexistente ou já baixado.', $status === false ? null : $id, $responsavel);
        $pdo->commit();
        pulse_baixa_resposta('Usuário inexistente ou já baixado. Nenhuma alteração realizada.');
    }
    $consulta = $pdo->prepare("UPDATE usuarios SET status = 'Baixa', condicao = 'baixa', baixado_em = CURRENT_TIMESTAMP WHERE id = :id AND status = 'Ativo'");
    $consulta->execute(['id' => $id]);
    if ($consulta->rowCount() !== 1) {
        throw new RuntimeException('Baixa não efetuada.');
    }
    pulse_registrar_auditoria($pdo, 'BAIXA', 'Sucesso: usuário recebeu baixa lógica.', $id, $responsavel);
    $pdo->commit();
    pulse_baixa_resposta('Usuário excluído com sucesso (baixa lógica).');
} catch (Throwable $exception) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('PULSE: falha na operação de baixa.');
    if (isset($pdo, $responsavel)) {
        try {
            pulse_registrar_auditoria($pdo, 'BAIXA', 'Falha: operação não concluída.', null, $responsavel);
        } catch (Throwable $auditException) {
            error_log('PULSE: falha no registro de auditoria da baixa.');
        }
    }
    http_response_code(503);
    exit('Não foi possível concluir a operação. Tente novamente mais tarde.');
}
