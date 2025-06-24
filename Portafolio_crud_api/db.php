<?php
$host = 'localhost';
$db   = 'gonzalo_carvajal_db1';
$user = 'gonzalo_carvajal';
$pass = 'gonzalo_carvajal2025';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Manejo de errores con excepciones
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch como array asociativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Preparar consultas nativas
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // En producción, no mostrar el error completo, solo mensaje genérico
    echo "Error al conectar con la base de datos.";
    // Para desarrollo puedes usar:
    // echo "Error: " . $e->getMessage();
    exit;
}
