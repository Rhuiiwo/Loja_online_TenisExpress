
-- =====================================================
-- PROJETO: PULSE
-- SPRINT 01 - PONTO 02
-- MIGRATION 002
-- Adequacao da tabela usuarios
-- =====================================================

USE `db_pulse_homologacao`;

-- =====================================================
-- 1. ADICIONAR OS NOVOS CAMPOS
-- =====================================================

ALTER TABLE `usuarios`

    ADD COLUMN `setor_id`
        BIGINT UNSIGNED NULL
        AFTER `tipo`,

    ADD COLUMN `perfil_id`
        BIGINT UNSIGNED NULL
        AFTER `setor_id`,

    ADD COLUMN `status`
        ENUM('Ativo', 'Baixa')
        NOT NULL DEFAULT 'Ativo'
        AFTER `perfil_id`,

    ADD COLUMN `atualizado_em`
        TIMESTAMP NOT NULL
        DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
        AFTER `criado_em`,

    ADD COLUMN `baixado_em`
        TIMESTAMP NULL DEFAULT NULL
        AFTER `atualizado_em`;


-- =====================================================
-- 2. VALIDAR A ESTRUTURA
-- =====================================================

DESCRIBE `usuarios`;
