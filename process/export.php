<?php
require_once '../includes/config.php';
require_once '../lib/phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = getDbConnection();

$sql = "SELECT * FROM reservas WHERE status = 'aprovado' ORDER BY data_reserva, hora_entrada";
$result = $conn->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Nome');
$sheet->setCellValue('B1', 'Número USP');
$sheet->setCellValue('C1', 'Vínculo');
$sheet->setCellValue('D1', 'Data');
$sheet->setCellValue('E1', 'Sala');
$sheet->setCellValue('F1', 'Quantidade Pessoas');
$sheet->setCellValue('G1', 'Hora Entrada');
$sheet->setCellValue('H1', 'Hora Saída');
$sheet->setCellValue('I1', 'Equipamentos');

$row = 2;
while ($reserva = $result->fetch_assoc()) {
    $sheet->setCellValue("A$row", $reserva['nome']);
    $sheet->setCellValue("B$row", $reserva['numero_usp']);
    $sheet->setCellValue("C$row", $reserva['vinculo']);
    $sheet->setCellValue("D$row", $reserva['data_reserva']);
    $sheet->setCellValue("E$row", $reserva['sala']);
    $sheet->setCellValue("F$row", $reserva['quantidade_pessoas']);
    $sheet->setCellValue("G$row", substr($reserva['hora_entrada'], 0, 5));
    $sheet->setCellValue("H$row", substr($reserva['hora_saida'], 0, 5));
    $sheet->setCellValue("I$row", $reserva['equipamentos']);
    $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="reservas_usp.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
