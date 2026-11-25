
-- =====================================================
-- PROJETO: PULSE
-- SPRINT 01 - PONTO 02
-- MIGRATION 001
-- Criacao das tabelas setores e perfis
-- =====================================================

USE `db_pulse`;

-- =====================================================
-- 1. TABELA SETORES
-- =====================================================

CREATE TABLE `setores` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(100) NOT NULL,
    `ativo` BOOLEAN NOT NULL DEFAULT TRUE,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_setores_nome` (`nome`),

    CONSTRAINT `chk_setores_ativo`
        CHECK (`ativo` IN (0, 1))

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- =====================================================
-- 2. TABELA PERFIS
-- =====================================================

CREATE TABLE `perfis` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(100) NOT NULL,
    `ativo` BOOLEAN NOT NULL DEFAULT TRUE,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_perfis_nome` (`nome`),

    CONSTRAINT `chk_perfis_ativo`
        CHECK (`ativo` IN (0, 1))

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- =====================================================
-- 3. REGISTROS INICIAIS - SETORES
-- =====================================================

INSERT INTO `setores` (`nome`, `ativo`) VALUES
    ('Administração', TRUE),
    ('Comercial', TRUE),
    ('Atendimento', TRUE),
    ('Estoque', TRUE),
    ('Clientes', TRUE);


-- =====================================================
-- 4. REGISTROS INICIAIS - PERFIS
-- =====================================================

INSERT INTO `perfis` (`nome`, `ativo`) VALUES
    ('Administrador', TRUE),
    ('Vendedor', TRUE),
    ('Atendente', TRUE),
    ('Operador de estoque', TRUE),
    ('Cliente', TRUE);
