<?php
require_once '../auth.php';
require_once '../db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $url_github = $_POST['url_github'] ?? '';
    $url_produccion = $_POST['url_produccion'] ?? '';

    $imagen = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = $_FILES['imagen']['name'];
        $fileSize = $_FILES['imagen']['size'];
        $fileType = $_FILES['imagen']['type'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB

        if (in_array($fileExt, $allowedExts)) {
            if ($fileSize <= $maxFileSize) {
                $newFileName = time() . '_' . preg_replace("/[^A-Za-z0-9\._-]/", '_', $fileName);
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $imagen = $newFileName;
                } else {
                    $error = "Error al mover la imagen al servidor.";
                }
            } else {
                $error = "La imagen excede el tamaño máximo permitido (5MB).";
            }
        } else {
            $error = "Tipo de archivo no permitido. Solo JPG, PNG, GIF y WEBP.";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO proyectos (nombre, descripcion, url_github, url_produccion, imagen) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$nombre, $descripcion, $url_github, $url_produccion, $imagen])) {
            header('Location: index.php');
            exit;
        } else {
            $error = "Error al guardar el proyecto.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Agregar Proyecto</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f4f8;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 95%;
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 12px;
        }
        h2 {
            color: #0a3d62;
            text-align: center;
        }
        label {
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 4px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn-volver {
            background: #ddd;
            color: #333;
        }
        .btn-volver:hover {
            background: #bbb;
        }
        .btn-add {
            background: #1e90ff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn-add:hover {
            background: #0c7cd5;
        }
        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn btn-volver">← Volver al Panel</a>
        <h2>Agregar Proyecto</h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required />

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" rows="4" required></textarea>

            <label for="url_github">URL GitHub:</label>
            <input type="url" name="url_github" />

            <label for="url_produccion">URL Producción:</label>
            <input type="url" name="url_produccion" />

            <label for="imagen">Imagen:</label>
            <input type="file" name="imagen" accept="image/*" />

            <button type="submit" class="btn btn-add">Guardar Proyecto</button>
        </form>
    </div>
</body>
</html>
