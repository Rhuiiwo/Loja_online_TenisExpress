-- PULSE | Banco de dados completo
-- MySQL 8.0+ | InnoDB | UTF-8
-- Estrutura original + perfil ROOT, condição, sessões e auditoria.

CREATE DATABASE IF NOT EXISTS db_pulse
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_pulse;

CREATE TABLE IF NOT EXISTS usuarios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    cpf CHAR(11) UNIQUE,
    tipo ENUM('cliente','vendedor','administrador','root') NOT NULL DEFAULT 'cliente',
    condicao ENUM('ativo','inativo','suspenso','baixa') NOT NULL DEFAULT 'ativo',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_usuario_tipo_condicao (tipo, condicao)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS perfis_vendedor (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT UNSIGNED NOT NULL UNIQUE,
    nome_loja VARCHAR(150) NOT NULL,
    cnpj CHAR(14) UNIQUE,
    status ENUM('pendente','ativo','suspenso') NOT NULL DEFAULT 'pendente',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_perfil_vendedor_usuario FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categorias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS fornecedores (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cnpj CHAR(14) UNIQUE,
    telefone VARCHAR(20)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS produtos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vendedor_id BIGINT UNSIGNED,
    categoria_id BIGINT UNSIGNED,
    fornecedor_id BIGINT UNSIGNED,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    status ENUM('rascunho','publicado','inativo') NOT NULL DEFAULT 'rascunho',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_produto_preco CHECK (preco >= 0),
    CONSTRAINT fk_produto_vendedor FOREIGN KEY (vendedor_id)
        REFERENCES perfis_vendedor(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_produto_categoria FOREIGN KEY (categoria_id)
        REFERENCES categorias(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_produto_fornecedor FOREIGN KEY (fornecedor_id)
        REFERENCES fornecedores(id) ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_produto_status_nome (status,nome)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS estoque (
    produto_id BIGINT UNSIGNED PRIMARY KEY,
    quantidade INT UNSIGNED NOT NULL DEFAULT 0,
    localizacao VARCHAR(100),
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_estoque_produto FOREIGN KEY (produto_id)
        REFERENCES produtos(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS carrinhos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id BIGINT UNSIGNED NOT NULL,
    status ENUM('aberto','convertido','abandonado') NOT NULL DEFAULT 'aberto',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_carrinho_cliente FOREIGN KEY (cliente_id)
        REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX idx_carrinho_cliente_status (cliente_id,status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS itens_carrinho (
    carrinho_id BIGINT UNSIGNED NOT NULL,
    produto_id BIGINT UNSIGNED NOT NULL,
    quantidade INT UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (carrinho_id,produto_id),
    CONSTRAINT fk_item_carrinho_carrinho FOREIGN KEY (carrinho_id)
        REFERENCES carrinhos(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_item_carrinho_produto FOREIGN KEY (produto_id)
        REFERENCES produtos(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pedidos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id BIGINT UNSIGNED,
    vendedor_responsavel_id BIGINT UNSIGNED,
    canal ENUM('online','pdv') NOT NULL DEFAULT 'online',
    status ENUM('aberto','aguardando_pagamento','pago','cancelado','concluido')
        NOT NULL DEFAULT 'aberto',
    valor_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_pedido_valor CHECK (valor_total >= 0),
    CONSTRAINT fk_pedido_cliente FOREIGN KEY (cliente_id)
        REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_pedido_vendedor FOREIGN KEY (vendedor_responsavel_id)
        REFERENCES perfis_vendedor(id) ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_pedido_cliente_data (cliente_id,criado_em),
    INDEX idx_pedido_canal_status (canal,status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS itens_pedido (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id BIGINT UNSIGNED NOT NULL,
    produto_id BIGINT UNSIGNED,
    nome_produto VARCHAR(150) NOT NULL,
    quantidade INT UNSIGNED NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    CONSTRAINT chk_item_pedido_preco CHECK (preco_unitario >= 0),
    CONSTRAINT fk_item_pedido_pedido FOREIGN KEY (pedido_id)
        REFERENCES pedidos(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_item_pedido_produto FOREIGN KEY (produto_id)
        REFERENCES produtos(id) ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_item_pedido_produto (produto_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pagamentos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id BIGINT UNSIGNED NOT NULL,
    metodo ENUM('debito','credito','pix','dinheiro','outro') NOT NULL,
    status ENUM('pendente','aprovado','recusado','estornado') NOT NULL DEFAULT 'pendente',
    valor DECIMAL(10,2) NOT NULL,
    pago_em TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT chk_pagamento_valor CHECK (valor >= 0),
    CONSTRAINT fk_pagamento_pedido FOREIGN KEY (pedido_id)
        REFERENCES pedidos(id) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX idx_pagamento_pedido_status (pedido_id,status)
) ENGINE=InnoDB;

-- Uma linha por login. token_hash guarda apenas o hash do token.
CREATE TABLE IF NOT EXISTS sessoes_usuarios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id BIGINT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL UNIQUE,
    inicio_sessao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fim_sessao DATETIME NULL,
    ultimo_acesso DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ip_origem VARCHAR(45),
    navegador_dispositivo VARCHAR(255),
    status ENUM('ativa','encerrada','expirada','revogada') NOT NULL DEFAULT 'ativa',
    encerrada_por BIGINT UNSIGNED NULL,
    motivo_encerramento VARCHAR(255),
    CONSTRAINT fk_sessao_usuario FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_sessao_responsavel FOREIGN KEY (encerrada_por)
        REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_sessao_usuario (usuario_id),
    INDEX idx_sessao_status (status),
    INDEX idx_sessao_inicio (inicio_sessao),
    INDEX idx_sessao_ultimo_acesso (ultimo_acesso)
) ENGINE=InnoDB;

-- Registrar alterações relevantes realizadas pela aplicação.
CREATE TABLE IF NOT EXISTS auditoria_sistema (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    responsavel_id BIGINT UNSIGNED NULL,
    usuario_afetado_id BIGINT UNSIGNED NULL,
    acao VARCHAR(100) NOT NULL,
    entidade VARCHAR(100) NOT NULL,
    registro_id BIGINT UNSIGNED NULL,
    valor_anterior JSON NULL,
    valor_novo JSON NULL,
    descricao TEXT,
    ip_origem VARCHAR(45),
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_auditoria_responsavel FOREIGN KEY (responsavel_id)
        REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_auditoria_usuario_afetado FOREIGN KEY (usuario_afetado_id)
        REFERENCES usuarios(id) ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_auditoria_responsavel (responsavel_id),
    INDEX idx_auditoria_afetado (usuario_afetado_id),
    INDEX idx_auditoria_data (criado_em),
    INDEX idx_auditoria_entidade (entidade,registro_id),
    INDEX idx_auditoria_acao (acao)
) ENGINE=InnoDB;
