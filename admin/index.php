<?php
session_start();
require_once '../includes/config.php';

if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
    header('Location: dashboard.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT id, senha_hash FROM administradores WHERE usuario = ?");
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($senha, $admin['senha_hash'])) {
            $_SESSION['admin_logged'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_usuario'] = $usuario;
            header('Location: dashboard.php');
            exit;
        }
    }
    $erro = 'Usuário ou senha inválidos.';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Login Admin - Reserva de Salas</title>
    <link rel="stylesheet" href="../assets/css/admin.css" />
</head>
<body>
    <h2>Login do Administrador</h2>
    <?php if ($erro): ?>
        <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="usuario">Usuário:</label>
        <input type="text" name="usuario" id="usuario" required />

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required />

        <button type="submit">Entrar</button>
    </form>
</body>
</html>