-- Tabla usuarios para el sistema de login
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

-- Tabla proyectos para almacenar los proyectos del portafolio
CREATE TABLE IF NOT EXISTS proyectos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  imagen VARCHAR(255) DEFAULT '',
  descripcion TEXT NOT NULL,
  url_github VARCHAR(255) DEFAULT '',
  url_produccion VARCHAR(255) DEFAULT ''
);
