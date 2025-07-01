<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';

$conn = getDbConnection();

// Estatísticas básicas
$totalReservas = $conn->query("SELECT COUNT(*) as total FROM reservas")->fetch_assoc()['total'];
$totalAprovadas = $conn->query("SELECT COUNT(*) as total FROM reservas WHERE status = 'aprovado'")->fetch_assoc()['total'];
$totalPendentes = $conn->query("SELECT COUNT(*) as total FROM reservas WHERE status = 'pendente'")->fetch_assoc()['total'];

// Reservas futuras
$hoje = date('Y-m-d');
$reservasFuturas = $conn->query("SELECT * FROM reservas WHERE data_reserva >= '$hoje' ORDER BY data_reserva, hora_entrada");

?>

<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';

$conn = getDbConnection();

// --- Reservas por mês (aprovadas) ---
$reservasPorMes = array_fill(1, 12, 0);
$sqlMes = "SELECT MONTH(data_reserva) as mes, COUNT(*) as total 
           FROM reservas 
           WHERE status = 'aprovado' 
           GROUP BY mes";
$resultMes = $conn->query($sqlMes);
while ($row = $resultMes->fetch_assoc()) {
    $reservasPorMes[(int)$row['mes']] = (int)$row['total'];
}
// Ajusta para labels em português
$labelsMes = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

// --- Horários mais reservados (aprovadas) ---
// Considerando horários fixos de entrada (exemplo: 08:00, 10:00, 12:00, 14:00, 16:00, 18:00)
$horariosFixos = ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00'];
$horariosContagem = array_fill_keys($horariosFixos, 0);

$sqlHora = "SELECT hora_entrada, COUNT(*) as total 
            FROM reservas 
            WHERE status = 'aprovado' 
            GROUP BY hora_entrada";
$resultHora = $conn->query($sqlHora);
while ($row = $resultHora->fetch_assoc()) {
    $hora = substr($row['hora_entrada'], 0, 5); // HH:MM
    if (in_array($hora, $horariosFixos)) {
        $horariosContagem[$hora] = (int)$row['total'];
    }
}

// Dados para o JS
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Reserva de Salas</title>
    <link rel="stylesheet" href="../assets/css/admin.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- CDN Chart.js -->
</head>
<body>

    <h3>Reservas por mês</h3>
    <canvas id="graficoReservasMes" width="600" height="300"></canvas>

    <h3>Horários mais reservados</h3>
    <canvas id="graficoHorarios" width="600" height="300"></canvas>

    <h1>Dashboard Administrativo</h1>
    <p><a href="logout.php">Sair</a></p>

    <h2>Estatísticas</h2>
    <ul>
        <li>Total de reservas: <?= $totalReservas ?></li>
        <li>Reservas aprovadas: <?= $totalAprovadas ?></li>
        <li>Reservas pendentes: <?= $totalPendentes ?></li>
    </ul>

    <h2>Reservas Futuras</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Vínculo</th>
                <th>Data</th>
                <th>Sala</th>
                <th>Entrada</th>
                <th>Saída</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($reserva = $reservasFuturas->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($reserva['nome']) ?></td>
                    <td><?= htmlspecialchars($reserva['vinculo']) ?></td>
                    <td><?= $reserva['data_reserva'] ?></td>
                    <td><?= $reserva['sala'] ?></td>
                    <td><?= substr($reserva['hora_entrada'], 0, 5) ?></td>
                    <td><?= substr($reserva['hora_saida'], 0, 5) ?></td>
                    <td><?= $reserva['status'] ?></td>
                    <td>
                        <?php if ($reserva['status'] === 'pendente'): ?>
                            <form method="POST" action="../process/admin_actions.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $reserva['id'] ?>" />
                                <button type="submit" name="action" value="aprovar">Aprovar</button>
                                <button type="submit" name="action" value="rejeitar">Rejeitar</button>
                            </form>
                        <?php else: ?>
                            <form method="POST" action="../process/admin_actions.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $reserva['id'] ?>" />
                                <button type="submit" name="action" value="cancelar">Cancelar</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<!--gerador do PDF-->

    <button id="btnGerarPdf">Gerar PDF</button>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="../assets/js/admin.js"></script>
        <script>
        document.getElementById('btnGerarPdf').addEventListener('click', function() {
            // Captura os canvas dos gráficos
            const graficoMes = document.getElementById('graficoReservasMes');
            const graficoHorarios = document.getElementById('graficoHorarios');

            // Converte para base64 PNG
            const imgMes = graficoMes.toDataURL('image/png');
            const imgHorarios = graficoHorarios.toDataURL('image/png');

            // Envia via POST para o PHP que gera o PDF
            fetch('process/export_pdf.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ imgMes, imgHorarios })
            })
            .then(res => res.blob())
            .then(blob => {
                // Cria link para download do PDF gerado
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'relatorio_reservas.pdf';
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            })
            .catch(console.error);
        });
        </script>


            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const reservasPorMes = {
                    labels: <?= json_encode($labelsMes) ?>,
                    data: <?= json_encode(array_values($reservasPorMes)) ?>
                };

                const horariosMaisReservados = {
                    labels: <?= json_encode(array_keys($horariosContagem)) ?>,
                    data: <?= json_encode(array_values($horariosContagem)) ?>
                };
            </script>
            <script src="../assets/js/admin.js"></script>


</body>
</html>