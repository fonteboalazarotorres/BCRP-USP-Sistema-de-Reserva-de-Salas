<?php
require_once 'config.php';

// Verifica se uma sala está disponível para o horário e data
function salaDisponivel($sala, $data, $horaEntrada, $conn) {
    $horaSaida = date('H:i:s', strtotime($horaEntrada) + TEMPO_RESERVA_HORAS * 3600);

    $sql = "SELECT COUNT(*) as total FROM reservas 
            WHERE sala = ? AND data_reserva = ? AND status = 'aprovado' 
            AND ((hora_entrada <= ? AND hora_saida > ?) OR (hora_entrada < ? AND hora_saida >= ?))";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isssss', $sala, $data, $horaEntrada, $horaEntrada, $horaSaida, $horaSaida);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result['total'] == 0;
}

// Verifica se usuário já tem reserva no mesmo horário
function usuarioTemReserva($numeroUsp, $data, $horaEntrada, $conn) {
    $horaSaida = date('H:i:s', strtotime($horaEntrada) + TEMPO_RESERVA_HORAS * 3600);

    $sql = "SELECT COUNT(*) as total FROM reservas 
            WHERE numero_usp = ? AND data_reserva = ? AND status = 'aprovado' 
            AND ((hora_entrada <= ? AND hora_saida > ?) OR (hora_entrada < ? AND hora_saida >= ?))";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $numeroUsp, $data, $horaEntrada, $horaEntrada, $horaSaida, $horaSaida);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result['total'] > 0;
}