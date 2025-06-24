<?php
require_once '../auth.php';
require_once '../db.php';

$id = intval($_GET['id'] ?? 0);
$error = '';

$stmt = $conn->prepare("SELECT * FROM proyectos WHERE id = ?");
$stmt->execute([$id]);
$proyecto = $stmt->fetch();

if (!$proyecto) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $url_github = $_POST['url_github'] ?? '';
    $url_produccion = $_POST['url_produccion'] ?? '';
    $imagen = $proyecto['imagen'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $fileTmp = $_FILES['imagen']['tmp_name'];
        $fileName = $_FILES['imagen']['name'];
        $fileSize = $_FILES['imagen']['size'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxSize = 5 * 1024 * 1024;

        if (in_array($fileExt, $allowedExts)) {
            if ($fileSize <= $maxSize) {
                $newFileName = time() . '_' . preg_replace("/[^A-Za-z0-9_\-\.]/", '_', $fileName);
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmp, $destPath)) {
                    $imagen = $newFileName;
                } else {
                    $error = "Error al guardar la nueva imagen.";
                }
            } else {
                $error = "La imagen supera el tamaño permitido (5MB).";
            }
        } else {
            $error = "Formato de imagen no permitido.";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("UPDATE proyectos SET nombre=?, descripcion=?, url_github=?, url_produccion=?, imagen=? WHERE id=?");
        if ($stmt->execute([$nombre, $descripcion, $url_github, $url_produccion, $imagen, $id])) {
            header('Location: index.php');
            exit;
        } else {
            $error = "Error al actualizar el proyecto.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Editar Proyecto</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
    <div class="container">
        <a href="index.php" class="btn btn-volver">← Volver al Panel</a>
        <h2>Editar Proyecto</h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($proyecto['nombre']) ?>" required />

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" rows="4" required><?= htmlspecialchars($proyecto['descripcion']) ?></textarea>

            <label for="url_github">URL GitHub:</label>
            <input type="url" name="url_github" value="<?= htmlspecialchars($proyecto['url_github']) ?>" />

            <label for="url_produccion">URL Producción:</label>
            <input type="url" name="url_produccion" value="<?= htmlspecialchars($proyecto['url_produccion']) ?>" />

            <label>Imagen actual:</label><br />
            <?php if ($proyecto['imagen']): ?>
                <img src="../uploads/<?= htmlspecialchars($proyecto['imagen']) ?>" alt="Imagen actual" style="max-width:220px; margin:15px 0; border-radius:12px;" />
            <?php else: ?>
                <p style="color:#777;">No hay imagen cargada.</p>
            <?php endif; ?>

            <label for="imagen">Cambiar imagen:</label>
            <input type="file" name="imagen" accept="image/*" />

            <button type="submit" class="btn btn-edit">Actualizar Proyecto</button>
        </form>
    </div>
</body>
</html>
