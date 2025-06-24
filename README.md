# portafolio_db1-base-de-datos-
portafolio de proyecto conectado con base datos.

https://teclab.uct.cl/~gonzalo.carvajal/trabajos/Portafolio_crud_api/public/index.php

# API Portafolio DB Gonzalo Carvajal

Este repositorio contiene el código del proyecto de la API de mi portafolio y una descripción general del desarrollo del proyecto.
El proyecto es una API, que se administra con una interfaz sencilla. Para proteger la API, se implementó verificación de sesión para los métodos
POST, DELETE y PATCH, permitiendo a la API solo entregar información por el método GET de forma libre.

# tecnologias utilizadas

se ocupo servidor institucional phpmyadmin para la base datos, html y
php, MYSQL para el boostrap, con ajustes minimos con css

# uso de IA

la IA que ocupe fue chatGPT y fue la que me ayudo a entender mejor la conexion para la base de datos.
arreglar errores de la API.

ayuda de IA: 

--------------------------------------------------------------------------------------------------------

<?php
// ⚙️ Datos de conexión a la base de datos
$host = 'localhost';                        // 🖥️ Host del servidor MySQL (local)
$db   = 'gonzalo_carvajal_db1';             // 📂 Nombre de la base de datos
$user = 'gonzalo_carvajal';                 // 👤 Usuario de la base
$pass = 'gonzalo_carvajal2025';             // 🔑 Contraseña del usuario
$charset = 'utf8mb4';                       // 🔤 Codificación para soportar acentos y emojis

// 🧪 Creo el DSN (cadena de conexión)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// 🛠️ Opciones para configurar PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // ⚠️ Lanza excepciones si hay errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // 📥 Los resultados serán arrays asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // 🔒 Desactiva emulación para usar consultas reales
];

try {
    // 🔌 Creo la conexión PDO con los datos y opciones
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // 🛑 Si algo falla, muestro un mensaje genérico y corto la ejecución
    echo "Error al conectar con la base de datos.";

    // 🐞 (En desarrollo puedes descomentar esta línea para ver el error real)
    // echo "Error: " . $e->getMessage();
    exit;
}

--------------------------------------------------------------------------------------------------------------------------------------------------

<?php
// 🚀 Inicio la sesión para poder guardar datos del usuario logueado
session_start();

// 🔌 Incluyo la conexión a la base de datos
require_once 'db.php';

// 🛑 Variable para almacenar mensajes de error y mostrarlos al usuario
$error = '';

// 📨 Verifico si el formulario fue enviado vía POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 📥 Obtengo los datos enviados por el formulario, con fallback a cadena vacía
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    // 🔎 Preparo la consulta para buscar el usuario en la base de datos
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]); // 🚦 Ejecuto la consulta con el usuario recibido
    $user = $stmt->fetch();     // 📄 Traigo los datos del usuario (si existe)

    // ✅ Verifico que exista el usuario y que la contraseña coincida usando password_verify
    if ($user && password_verify($password, $user['password'])) {
        // 🔐 Si es correcto, guardo datos en sesión para mantener al usuario logueado
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['usuario'] = $user['usuario'];

        // ↪️ Redirijo al panel CRUD después del login exitoso
        header("Location: crud/index.php");
        exit; // 🛑 Termino ejecución después de redirigir
    } else {
        // ❌ Si usuario o contraseña no coinciden, guardo mensaje de error para mostrar
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" /> <!-- 📱 Hace la página responsive -->
<title>Login - Portafolio</title> <!-- 🧾 Título visible en pestaña -->
<link rel="stylesheet" href="assets/css/style.css" /> <!-- 🎨 Enlace a estilos CSS -->
</head>
<body>
<div class="login-box modern-login">
    <!-- 🔙 Botón para volver al portafolio público -->
    <a href="public/index.php" class="btn btn-volver">← Volver al Portafolio</a>

    <h2>Iniciar Sesión</h2>

    <!-- ❗ Si hay error, lo muestro aquí -->
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- 📝 Formulario de login -->
    <form method="post" action="">
        <!-- 🧑‍💻 Input usuario con autocomplete para mejor UX -->
        <input type="text" name="usuario" placeholder="Usuario" required autocomplete="username" />
        <!-- 🔒 Input contraseña con autocomplete para seguridad -->
        <input type="password" name="password" placeholder="Contraseña" required autocomplete="current-password" />
        <!-- 🔘 Botón para enviar datos -->
        <button type="submit" class="btn-login">Entrar</button>
    </form>
</div>
</body>
</html>
