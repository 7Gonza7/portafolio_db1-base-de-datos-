<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Portafolio</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

  <div class="top-bar">
    <h1>Portafolio de Proyectos</h1>
    <div class="btns">
      <a href="../login.php">Iniciar sesión</a>
      <a href="../login.php">Agregar proyecto</a>
    </div>
  </div>

  <div class="proyectos-container" id="proyectos"></div>

  <script>
    fetch('../api/proyectos.php')
      .then(response => response.json())
      .then(data => {
        const contenedor = document.getElementById('proyectos');
        if (!Array.isArray(data) || data.length === 0) {
          contenedor.innerHTML = '<p>No hay proyectos para mostrar.</p>';
          return;
        }

        data.forEach(p => {
          const card = document.createElement('div');
          card.className = 'proyecto-card';

          const imagen = p.imagen ? p.imagen : 'default.jpg';
          const ruta = `../uploads/${imagen}`;

          card.innerHTML = `
            <img src="${ruta}" alt="${p.nombre}" onerror="this.src='../uploads/default.jpg'">
            <h2>${p.nombre}</h2>
            <p>${p.descripcion}</p>
            ${p.url_github ? `<a class="btn btn-github" href="${p.url_github}" target="_blank">GitHub</a>` : ''}
            ${p.url_produccion ? `<a class="btn btn-live" href="${p.url_produccion}" target="_blank">Ver online</a>` : ''}
          `;

          contenedor.appendChild(card);
        });
      })
      .catch(err => {
        console.error(err);
        document.getElementById('proyectos').innerHTML = '<p>Error al cargar los proyectos.</p>';
      });
  </script>

</body>
</html>
