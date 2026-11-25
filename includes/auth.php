<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

// Gera o token CSRF.
function pulse_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// Valida o token CSRF.
function pulse_valid_csrf($token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

// Protege a saída de dados no HTML.
function pulse_h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Verifica a autorização administrativa no banco.
function pulse_is_admin(?PDO $pdo = null): bool
{
    $usuarioId = filter_var(
        $_SESSION['usuario_id'] ?? null,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    if (!$usuarioId) {
        return false;
    }

    try {
        require_once __DIR__ . '/../database/conexao.php';

        $pdo = $pdo ?? pulse_db();

        $consulta = $pdo->prepare(
            "SELECT id FROM usuarios
             WHERE id = :id
             AND tipo = 'administrador'
             AND status = 'Ativo'
             LIMIT 1"
        );

        $consulta->execute([
            'id' => $usuarioId
        ]);

        return $consulta->fetchColumn() !== false;

    } catch (PDOException $exception) {
        error_log('PULSE: falha na autorização administrativa.');

        return false;
    }
}
