
<?php

/**
 * PROJETO: PULSE
 * SPRINT 01 - PONTO 03
 * Conexao centralizada com MySQL utilizando PDO.
 *
 * @return PDO
 * @throws RuntimeException
 */

function pulse_db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('PULSE_DB_HOST') ?: 'localhost';
    $database = getenv('PULSE_DB_NAME') ?: 'db_pulse_instalacao_teste';
    $username = getenv('PULSE_DB_USER') ?: 'root';

    $password = getenv('PULSE_DB_PASSWORD');

    if ($password === false) {
        $password = '';
    }

    $dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return $pdo;

    } catch (PDOException $e) {
        error_log('[PULSE] Falha na conexao com o banco de dados.');

        throw new RuntimeException(
            'Servico temporariamente indisponivel. Tente novamente mais tarde.'
        );
    }
}
