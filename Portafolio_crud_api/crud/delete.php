<?php
require_once '../auth.php';
require_once '../db.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    // 1. Buscar imagen del proyecto
    $stmt = $conn->prepare("SELECT imagen FROM proyectos WHERE id = ?");
    $stmt->execute([$id]);
    $proyecto = $stmt->fetch();

    if ($proyecto) {
        // 2. Eliminar proyecto de la base de datos
        $stmt = $conn->prepare("DELETE FROM proyectos WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);

        // 3. Eliminar imagen del servidor si existe
        if (!empty($proyecto['imagen'])) {
            $rutaImagen = '../uploads/' . $proyecto['imagen'];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }
    }
}

header("Location: index.php");
exit;
