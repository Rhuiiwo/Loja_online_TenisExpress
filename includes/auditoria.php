<?php

function pulse_registrar_auditoria(
    PDO $pdo,
    string $acao,
    string $descricao,
    ?int $usuarioAfetadoId = null,
    ?int $responsavelId = null
): void {
    $consulta = $pdo->prepare(
        'INSERT INTO auditoria_sistema (
            responsavel_id,
            usuario_afetado_id,
            acao,
            entidade,
            registro_id,
            descricao
        ) VALUES (
            :responsavel_id,
            :usuario_afetado_id,
            :acao,
            :entidade,
            :registro_id,
            :descricao
        )'
    );

    $consulta->execute([
        'responsavel_id' => $responsavelId,
        'usuario_afetado_id' => $usuarioAfetadoId,
        'acao' => $acao,
        'entidade' => 'usuarios',
        'registro_id' => $usuarioAfetadoId,
        'descricao' => $descricao
    ]);
}
