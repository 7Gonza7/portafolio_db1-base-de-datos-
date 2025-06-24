<?php
require_once '../auth.php';
require_once '../db.php';

$stmt = $conn->query("SELECT * FROM proyectos ORDER BY id DESC");
$proyectos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Panel CRUD - Proyectos</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
    <div class="container">

        <div class="top-bar">
            <h1>Panel de Proyectos</h1>
            <div class="btns">
                <a href="add.php" class="btn btn-add">+ Agregar</a>
                <a href="../logout.php" class="btn btn-logout">Cerrar sesión</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Imagen</th>
                    <th>Descripción</th>
                    <th>GitHub</th>
                    <th>Producción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($proyectos as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                    <td>
                        <?php
                        $imagenRuta = '../uploads/' . htmlspecialchars($p['imagen']);
                        if (!empty($p['imagen']) && file_exists($imagenRuta)):
                        ?>
                            <img src="<?= $imagenRuta ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
                        <?php else: ?>
                            <span style="color:#999;">Sin imagen</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars(substr($p['descripcion'], 0, 60)) ?>...</td>
                    <td>
                        <?php if (!empty($p['url_github'])): ?>
                            <a href="<?= htmlspecialchars($p['url_github']) ?>" target="_blank" class="btn btn-github">GitHub</a>
                        <?php else: ?>
                            <span style="color:#999;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($p['url_produccion'])): ?>
                            <a href="<?= htmlspecialchars($p['url_produccion']) ?>" target="_blank" class="btn btn-live">Ver</a>
                        <?php else: ?>
                            <span style="color:#999;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-edit">✏️</a>
                        <a href="delete.php?id=<?= $p['id'] ?>" onclick="return confirm('¿Eliminar este proyecto?');" class="btn btn-delete">🗑️</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
