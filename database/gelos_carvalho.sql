CREATE DATABASE IF NOT EXISTS gelos_carvalho CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gelos_carvalho;
CREATE TABLE IF NOT EXISTS administradores (
 id INT UNSIGNED NOT NULL AUTO_INCREMENT,
 nome VARCHAR(120) NOT NULL,
 usuario VARCHAR(50) NOT NULL,
 email VARCHAR(190) NOT NULL,
 senha_hash VARCHAR(255) NOT NULL,
 ativo TINYINT(1) NOT NULL DEFAULT 1,
 criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 ultimo_acesso DATETIME NULL,
 PRIMARY KEY(id), UNIQUE KEY uq_admin_usuario(usuario), UNIQUE KEY uq_admin_email(email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS pedidos (
 id INT UNSIGNED NOT NULL AUTO_INCREMENT,
 nome VARCHAR(120) NOT NULL,
 telefone VARCHAR(30) NOT NULL,
 email VARCHAR(190) NULL,
 tipo_pedido ENUM('pacote-5','pacote-7','ambos','atacado','duvida') NULL,
 quantidade INT UNSIGNED NULL,
 mensagem TEXT NOT NULL,
 status ENUM('Pendente','Confirmado','Em preparação','Entregue','Cancelado') NOT NULL DEFAULT 'Pendente',
 criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 PRIMARY KEY(id), KEY idx_pedidos_status_data(status,criado_em), KEY idx_pedidos_nome(nome), KEY idx_pedidos_telefone(telefone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS pedido_historico (
 id INT UNSIGNED NOT NULL AUTO_INCREMENT,
 pedido_id INT UNSIGNED NOT NULL,
 administrador_id INT UNSIGNED NULL,
 status_anterior ENUM('Pendente','Confirmado','Em preparação','Entregue','Cancelado') NOT NULL,
 status_novo ENUM('Pendente','Confirmado','Em preparação','Entregue','Cancelado') NOT NULL,
 alterado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY(id), KEY idx_historico_pedido(pedido_id,alterado_em),
 CONSTRAINT fk_historico_pedido FOREIGN KEY(pedido_id) REFERENCES pedidos(id) ON UPDATE CASCADE ON DELETE CASCADE,
 CONSTRAINT fk_historico_admin FOREIGN KEY(administrador_id) REFERENCES administradores(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
