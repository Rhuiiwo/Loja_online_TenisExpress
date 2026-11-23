<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Gera e retorna o token CSRF associado à sessão atual e retorna um token seguro para formulários.
function pulse_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

// Valida um token CSRF recebido e ver se coresponde com o valor da sessão.
function pulse_valid_csrf($token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function pulse_h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
