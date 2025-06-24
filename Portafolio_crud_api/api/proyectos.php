<?php
header('Content-Type: application/json');
require_once '../db.php';

// Permitir solicitudes desde cualquier origen (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

switch ($method) {
    case 'GET':
        if ($id) {
            $stmt = $conn->prepare("SELECT * FROM proyectos WHERE id = ?");
            $stmt->execute([$id]);
            $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($proyecto ?: []);
        } else {
            $stmt = $conn->query("SELECT * FROM proyectos ORDER BY id DESC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['nombre']) || !isset($data['descripcion'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO proyectos (nombre, descripcion, url_github, url_produccion, imagen) VALUES (?, ?, ?, ?, ?)");
        $ok = $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['url_github'] ?? '',
            $data['url_produccion'] ?? '',
            $data['imagen'] ?? ''
        ]);

        echo json_encode(['success' => $ok]);
        break;

    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID requerido']);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        $stmt = $conn->prepare("UPDATE proyectos SET nombre=?, descripcion=?, url_github=?, url_produccion=?, imagen=? WHERE id=?");
        $ok = $stmt->execute([
            $data['nombre'] ?? '',
            $data['descripcion'] ?? '',
            $data['url_github'] ?? '',
            $data['url_produccion'] ?? '',
            $data['imagen'] ?? '',
            $id
        ]);

        echo json_encode(['success' => $ok]);
        break;

    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID requerido']);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM proyectos WHERE id = ?");
        $ok = $stmt->execute([$id]);

        echo json_encode(['success' => $ok]);
        break;

    case 'OPTIONS':
        // Respuesta rápida para solicitudes CORS
        http_response_code(200);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
