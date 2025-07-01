CREATE DATABASE reserva_salas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE reserva_salas;

CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    numero_usp VARCHAR(20),
    vinculo ENUM('Graduação','Pós-graduação','Docente','Servidor','Externo') NOT NULL,
    data_reserva DATE NOT NULL,
    sala INT NOT NULL,
    quantidade_pessoas INT NOT NULL,
    hora_entrada TIME NOT NULL,
    hora_saida TIME NOT NULL,
    equipamentos VARCHAR(255),
    status ENUM('pendente','aprovado','rejeitado') DEFAULT 'pendente',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL
);

-- Insira um administrador padrão (senha: admin123)
INSERT INTO administradores (usuario, senha_hash) VALUES 
('admin', '$2y$10$e0NRbX1W1QkZ6xkF6vZz8u5N9Zs6jYkFvHn8z7Zb0v6cN9bY1lD6a');