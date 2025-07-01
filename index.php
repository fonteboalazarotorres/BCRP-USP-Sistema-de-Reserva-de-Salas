<?php
require_once 'includes/config.php';
$conn = getDbConnection();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Reserva de Salas - USP</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="assets/js/script.js" defer></script>
</head>
<body>
    <h1>Reserva de Salas USP</h1>
    <form action="process/reserve.php" method="POST" id="formReserva">
        <label for="nome">Nome completo:</label>
        <input type="text" name="nome" id="nome" required />

        <label for="numero_usp">Número USP:</label>
        <input type="text" name="numero_usp" id="numero_usp" required />

        <label for="vinculo">Vínculo:</label>
        <select name="vinculo" id="vinculo" required>
            <option value="">Selecione</option>
            <option value="Graduação">Graduação</option>
            <option value="Pós-graduação">Pós-graduação</option>
            <option value="Docente">Docente</option>
            <option value="Servidor">Servidor</option>
            <option value="Externo">Externo</option>
        </select>

        <label for="data_reserva">Data da reserva:</label>
        <input type="date" name="data_reserva" id="data_reserva" required min="<?= date('Y-m-d') ?>" />

        <label for="sala">Sala:</label>
        <select name="sala" id="sala" required>
            <option value="">Selecione</option>
            <?php
            // Listar salas
            for ($i = 1; $i <= 15; $i++) {
                $capacidade = ($i <= 9) ? 'até 4 pessoas' : 'acima de 4 pessoas';
                echo "<option value=\"$i\">Sala $i ($capacidade)</option>";
            }
            ?>
        </select>

        <label for="quantidade_pessoas">Quantidade de pessoas:</label>
        <input type="number" name="quantidade_pessoas" id="quantidade_pessoas" min="1" required />

        <label for="hora_entrada">Hora de entrada:</label>
        <input type="time" name="hora_entrada" id="hora_entrada" required />

        <fieldset>
            <legend>Equipamentos necessários:</legend>
            <label><input type="checkbox" name="equipamentos[]" value="Cabo HDMI" /> Cabo HDMI</label>
            <label><input type="checkbox" name="equipamentos[]" value="Caneta/Apagador" /> Caneta/Apagador</label>
            <label><input type="checkbox" name="equipamentos[]" value="Controle remoto" /> Controle remoto</label>
        </fieldset>

        <button type="submit">Reservar</button>
    </form>
</body>
</html>