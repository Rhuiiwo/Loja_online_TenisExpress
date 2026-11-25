
-- =====================================================
-- PROJETO: PULSE
-- SPRINT 01 - PONTO 02
-- MIGRATION 003
-- Relacionamentos e indices
-- =====================================================

USE `db_pulse`;
-- =====================================================
-- 1. CAMPOS OBRIGATORIOS, INDICES E CHAVES ESTRANGEIRAS
-- Executar somente apos verificar que usuarios esta vazia
-- =====================================================

ALTER TABLE `usuarios`

    MODIFY COLUMN `setor_id`
        BIGINT UNSIGNED NOT NULL,

    MODIFY COLUMN `perfil_id`
        BIGINT UNSIGNED NOT NULL,

    ADD INDEX `idx_usuarios_setor` (`setor_id`),

    ADD INDEX `idx_usuarios_perfil` (`perfil_id`),

    ADD INDEX `idx_usuarios_status` (`status`),

    ADD CONSTRAINT `fk_usuarios_setor`
        FOREIGN KEY (`setor_id`)
        REFERENCES `setores` (`id`)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT,

    ADD CONSTRAINT `fk_usuarios_perfil`
        FOREIGN KEY (`perfil_id`)
        REFERENCES `perfis` (`id`)
        ON DELETE RESTRICT
        ON UPDATE RESTRICT;

-- =====================================================
-- 2. VALIDACAO DA ESTRUTURA
-- =====================================================

DESCRIBE `usuarios`;

SHOW INDEX FROM `usuarios`;

SHOW CREATE TABLE `usuarios`;
