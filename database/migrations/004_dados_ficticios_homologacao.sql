
-- ============================================================
-- PROJETO: PULSE
-- SPRINT 01 - PONTO 02
-- MIGRATION 004 - Dados ficticios de homologacao
-- ============================================================

USE `db_pulse_homologacao`;

-- ============================================================
-- 1. CONFIGURACAO DA SENHA FICTICIA
-- Substituir pelo hash gerado com password_hash() do PHP.
-- ============================================================

SET @senha_hash = '$2y$12$OC9vFqJfwKKYS7momCgTpuTh13hE3MWNDxHUs4CQksfQh3RIA/gFW';

-- ============================================================
-- 2. INSERCAO DOS USUARIOS FICTICIOS
-- ============================================================

START TRANSACTION;

INSERT INTO `usuarios`
(
    `nome`,
    `email`,
    `senha_hash`,
    `tipo`,
    `setor_id`,
    `perfil_id`,
    `status`,
    `baixado_em`
)
VALUES
(
    'Administrador Teste',
    'admin@pulse.example',
    @senha_hash,
    'administrador',
    1,
    1,
    'Ativo',
    NULL
),
(
    'Vendedor Teste',
    'vendedor@pulse.example',
    @senha_hash,
    'vendedor',
    2,
    2,
    'Ativo',
    NULL
),
(
    'Atendente Teste',
    'atendente@pulse.example',
    @senha_hash,
    'cliente',
    3,
    3,
    'Ativo',
    NULL
),
(
    'Estoquista Teste',
    'estoque@pulse.example',
    @senha_hash,
    'cliente',
    4,
    4,
    'Ativo',
    NULL
),
(
    'Cliente Teste',
    'cliente@pulse.example',
    @senha_hash,
    'cliente',
    5,
    5,
    'Ativo',
    NULL
),
(
    'Cliente Baixado Teste',
    'baixado@pulse.example',
    @senha_hash,
    'cliente',
    5,
    5,
    'Baixa',
    CURRENT_TIMESTAMP
);

COMMIT;

-- ============================================================
-- 3. VALIDACAO DA QUANTIDADE DE REGISTROS
-- ============================================================

SELECT COUNT(*) AS total_usuarios
FROM `usuarios`;

-- ============================================================
-- 4. VALIDACAO DOS STATUS
-- ============================================================

SELECT
    `status`,
    COUNT(*) AS quantidade
FROM `usuarios`
GROUP BY `status`;

-- ============================================================
-- 5. VALIDACAO DOS RELACIONAMENTOS
-- ============================================================

SELECT
    u.id,
    u.nome,
    u.email,
    u.tipo,
    s.nome AS setor,
    p.nome AS perfil,
    u.status,
    u.criado_em,
    u.baixado_em
FROM `usuarios` AS u
INNER JOIN `setores` AS s
    ON u.setor_id = s.id
INNER JOIN `perfis` AS p
    ON u.perfil_id = p.id
ORDER BY u.id;
