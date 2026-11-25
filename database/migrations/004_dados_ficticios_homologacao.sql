-- =====================================================
-- PROJETO: PULSE
-- SPRINT 01 - PONTO 02
-- MIGRATION 004
-- Dados ficticios de homologacao
-- =====================================================

USE `db_pulse`;

SET @senha_hash = '$2y$12$QPZMjtXDwJyGUIUTZYY3MOIZGZIoBiQz/X1XL4tHvblOuSa2nHcZO';

START TRANSACTION;

INSERT INTO `usuarios` (
    `nome`,
    `email`,
    `senha_hash`,
    `tipo`,
    `setor_id`,
    `perfil_id`,
    `status`,
    `condicao`,
    `baixado_em`
) VALUES
(
    'Administrador Teste',
    'admin@pulse.example',
    @senha_hash,
    'administrador',
    1, 1,
    'Ativo',
    'ativo',
    NULL
),
(
    'Vendedor Teste',
    'vendedor@pulse.example',
    @senha_hash,
    'vendedor',
    2, 2,
    'Ativo',
    'ativo',
    NULL
),
(
    'Atendente Teste',
    'atendente@pulse.example',
    @senha_hash,
    'cliente',
    3, 3,
    'Ativo',
    'ativo',
    NULL
),
(
    'Estoquista Teste',
    'estoque@pulse.example',
    @senha_hash,
    'cliente',
    4, 4,
    'Ativo',
    'ativo',
    NULL
),
(
    'Cliente Teste',
    'cliente@pulse.example',
    @senha_hash,
    'cliente',
    5, 5,
    'Ativo',
    'ativo',
    NULL
),
(
    'Cliente Baixado Teste',
    'baixado@pulse.example',
    @senha_hash,
    'cliente',
    5, 5,
    'Baixa',
    'baixa',
    CURRENT_TIMESTAMP
);

COMMIT;

-- VALIDACAO DA QUANTIDADE DE USUARIOS

SELECT COUNT(*) AS total_usuarios
FROM `usuarios`;

-- VALIDACAO DOS STATUS

SELECT
    `status`,
    COUNT(*) AS quantidade
FROM `usuarios`
GROUP BY `status`;

-- VALIDACAO DOS RELACIONAMENTOS

SELECT
    u.id,
    u.nome,
    u.email,
    u.tipo,
    s.nome AS setor,
    p.nome AS perfil,
    u.status,
    u.condicao,
    u.criado_em,
    u.baixado_em
FROM `usuarios` AS u
INNER JOIN `setores` AS s
    ON u.setor_id = s.id
INNER JOIN `perfis` AS p
    ON u.perfil_id = p.id
ORDER BY u.id;