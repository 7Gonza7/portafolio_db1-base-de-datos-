<?php
require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (usuario, password) VALUES (?, ?)");
        if ($stmt->execute([$usuario, $hash])) {
            $message = "<p class='success'>Usuario registrado correctamente.</p>";
        } else {
            $message = "<p class='error'>Error al registrar usuario.</p>";
        }
    } else {
        $message = "<p class='error'>Complete todos los campos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
    <div class="container modern-login" style="max-width: 400px; margin: 50px auto;">
        <h2>Registrar nuevo usuario</h2>

        <?= $message ?>

        <form method="post" action="">
            <label for="usuario">Usuario:</label>
            <input id="usuario" type="text" name="usuario" required autocomplete="username" />

            <label for="password">Contraseña:</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" />

            <button type="submit" class="btn btn-add">Registrar</button>
        </form>
    </div>
</body>
</html>
