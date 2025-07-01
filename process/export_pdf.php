<?php
require_once '../includes/config.php';
require_once '../lib/tcpdf/tcpdf.php';

// Recebe os dados JSON do POST
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['imgMes']) || !isset($data['imgHorarios'])) {
    http_response_code(400);
    echo "Dados inválidos";
    exit;
}

$pdf = new TCPDF();
$pdf->SetCreator('Sistema Reserva USP');
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Relatório de Reservas');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

// Título
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Relatório de Reservas - USP', 0, 1, 'C');

// Espaço
$pdf->Ln(10);

// Texto explicativo
$pdf->SetFont('helvetica', '', 12);
$pdf->MultiCell(0, 0, "Gráfico: Reservas por mês", 0, 'L', 0, 1);
$pdf->Ln(5);

// Imagem do gráfico reservas por mês
$imgMes = $data['imgMes'];
// Remove o prefixo base64
$imgMes = preg_replace('#^data:image/\w+;base64,#i', '', $imgMes);
$imgMesData = base64_decode($imgMes);
$pdf->Image('@' . $imgMesData, '', '', 180, 90, 'PNG');

// Nova página para o próximo gráfico
$pdf->AddPage();

$pdf->MultiCell(0, 0, "Gráfico: Horários mais reservados", 0, 'L', 0, 1);
$pdf->Ln(5);

$imgHorarios = $data['imgHorarios'];
$imgHorarios = preg_replace('#^data:image/\w+;base64,#i', '', $imgHorarios);
$imgHorariosData = base64_decode($imgHorarios);
$pdf->Image('@' . $imgHorariosData, '', '', 180, 90, 'PNG');

// Saída do PDF para download
$pdf->Output('relatorio_reservas.pdf', 'D');
