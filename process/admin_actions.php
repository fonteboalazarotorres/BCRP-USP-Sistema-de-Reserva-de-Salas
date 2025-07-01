<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';

$conn = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $action = $_POST['action'];

    if (!in_array($action, ['aprovar', 'rejeitar', 'cancelar'])) {
        die("Ação inválida.");
    }

    if ($action === 'cancelar') {
        // Cancelar reserva (excluir)
        $stmt = $conn->prepare("DELETE FROM reservas WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: ../admin/dashboard.php');
        exit;
    } else {
        // Aprovar ou rejeitar reserva
        $stmt = $conn->prepare("UPDATE reservas SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $action, $id);
        $stmt->execute();
        header('Location: ../admin/dashboard.php');
        exit;
    }
} else {
    header('Location: ../admin/dashboard.php');
    exit;
}