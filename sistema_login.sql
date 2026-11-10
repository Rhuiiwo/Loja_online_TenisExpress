CREATE DATABASE IF NOT EXISTS sistema_login CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;
USE sistema_login;

-- Criando a tabela apenas com os campos que você usa no cadastro
CREATE TABLE IF NOT EXISTS usuarios (
  id INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  senha VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
