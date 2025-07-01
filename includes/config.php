<?php
// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'reserva_salas');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');

// Salas e capacidade
define('SALAS_ATE_4', range(1, 9));
define('SALAS_ACIMA_4', range(10, 15));
define('TEMPO_RESERVA_HORAS', 2);

// Função para conexão com o banco
function getDbConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Erro na conexão com o banco: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}