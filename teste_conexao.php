
<?php

require_once __DIR__ . '/database/conexao.php';

header('Content-Type: text/plain; charset=UTF-8');

echo "PULSE - SPRINT 01 - PONTO 03\n";
echo "TESTE DE CONEXAO PDO\n";
echo "========================================\n\n";

try {
    $pdo = pulse_db();

    echo "Conexao PDO: SUCESSO\n";
    echo "Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";

    $banco = $pdo->query("SELECT DATABASE()")->fetchColumn();

    echo "Banco conectado: " . $banco . "\n\n";

    $sql = "SELECT
                COUNT(*) AS total_usuarios,
                SUM(CASE WHEN status = 'Ativo' THEN 1 ELSE 0 END) AS ativos,
                SUM(CASE WHEN status = 'Baixa' THEN 1 ELSE 0 END) AS baixados
            FROM usuarios";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $resultado = $stmt->fetch();

    echo "CONSULTA REAL AO BANCO\n";
    echo "----------------------------------------\n";
    echo "Total de usuarios: " . $resultado['total_usuarios'] . "\n";
    echo "Usuarios ativos: " . $resultado['ativos'] . "\n";
    echo "Usuarios baixados: " . $resultado['baixados'] . "\n\n";

    echo "RESULTADO: TESTE APROVADO\n";

} catch (Throwable $e) {
    http_response_code(503);

    echo "RESULTADO: FALHA CONTROLADA\n";
    echo "Servico temporariamente indisponivel.\n";
    echo "Tente novamente mais tarde.\n";
}
