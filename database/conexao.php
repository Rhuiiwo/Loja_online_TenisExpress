<?php

/** Cria e reutiliza a conexão PDO com o banco da loja.
 * @return PDO Conexão configurada para MySQL.
 */
<?php

function pulse_db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $host = 'localhost';
        $database = 'db_pulse';
        $username = 'root';
        $password = '';

        $dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4";

        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    return $pdo;
}