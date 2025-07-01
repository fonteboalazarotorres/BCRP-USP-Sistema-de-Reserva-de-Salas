<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$conn = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $numero_usp = trim($_POST['numero_usp']);
    $vinculo = $_POST['vinculo'];
    $data_reserva = $_POST['data_reserva'];
    $sala = (int)$_POST['sala'];
    $quantidade_pessoas = (int)$_POST['quantidade_pessoas'];
    $hora_entrada = $_POST['hora_entrada'];
    $equipamentos = isset($_POST['equipamentos']) ? implode(', ', $_POST['equipamentos']) : '';

    // Validações básicas
    if (empty($nome) || empty($numero_usp) || empty($vinculo) || empty($data_reserva) || empty($sala) || empty($quantidade_pessoas) || empty($hora_entrada)) {
        die("Preencha todos os campos obrigatórios.");
    }

    // Verificar capacidade da sala
    if (in_array($sala, SALAS_ATE_4) && $quantidade_pessoas > 4) {
        die("Sala $sala suporta até 4 pessoas.");
    }
    if (in_array($sala, SALAS_ACIMA_4) && $quantidade_pessoas <= 4) {
        die("Sala $sala é para mais de 4 pessoas.");
    }

    // Verificar disponibilidade da sala
    if (!salaDisponivel($sala, $data_reserva, $hora_entrada, $conn)) {
        die("Sala $sala não está disponível neste horário.");
    }

    // Verificar se usuário já tem reserva no mesmo horário
    if (usuarioTemReserva($numero_usp, $data_reserva, $hora_entrada, $conn)) {
        die("Você já possui uma reserva neste horário.");
    }

    $hora_saida = date('H:i:s', strtotime($hora_entrada) + TEMPO_RESERVA_HORAS * 3600);

    $stmt = $conn->prepare("INSERT INTO reservas (nome, numero_usp, vinculo, data_reserva, sala, quantidade_pessoas, hora_entrada, hora_saida, equipamentos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssisiss', $nome, $numero_usp, $vinculo, $data_reserva, $sala, $quantidade_pessoas, $hora_entrada, $hora_saida, $equipamentos);

    if ($stmt->execute()) {
        echo "Reserva realizada com sucesso! Aguarde aprovação do administrador.";
    } else {
        echo "Erro ao realizar reserva: " . $stmt->error;
    }
} else {
    header('Location: ../index.php');
    exit;
}