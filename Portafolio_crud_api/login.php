<?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['usuario'] = $user['usuario'];
        header("Location: crud/index.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login - Portafolio</title>
<link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
<div class="login-box modern-login">
    <a href="public/index.php" class="btn btn-volver">← Volver al Portafolio</a>

    <h2>Iniciar Sesión</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <input type="text" name="usuario" placeholder="Usuario" required autocomplete="username" />
        <input type="password" name="password" placeholder="Contraseña" required autocomplete="current-password" />
        <button type="submit" class="btn-login">Entrar</button>
    </form>
</div>
</body>
</html>
