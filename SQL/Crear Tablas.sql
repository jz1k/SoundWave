-- Tabla de usuarios
CREATE TABLE usuarios (
    usuario_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol ENUM('usuario', 'admin') NOT NULL DEFAULT 'usuario',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    horas_escuchadas INT DEFAULT 0
);

-- Tabla de archivos de audio
CREATE TABLE archivos (
    archivo_id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    titulo VARCHAR(100) NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    duracion VARCHAR(255),
    subido_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(usuario_id) ON DELETE CASCADE
);

-- Tabla de grupos
CREATE TABLE grupos (
    grupo_id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    nombre VARCHAR(100) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(usuario_id) ON DELETE CASCADE
);

-- Tabla intermedia para la relación muchos a muchos entre grupos y archivos de audio
CREATE TABLE grupo_archivo (
    grupo_id INT,
    archivo_id INT,
    agregado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (grupo_id, archivo_id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(grupo_id) ON DELETE CASCADE,
    FOREIGN KEY (archivo_id) REFERENCES archivos(archivo_id) ON DELETE CASCADE
);