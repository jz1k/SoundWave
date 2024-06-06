<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'juancarlos');
define('DB_PASS', 'Gbz7LY4nNsmQQkl');
define('DB_NAME', 'juancarlos');


function conectar() {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conexion->connect_error) {
        echo "<p>Conexion fallida</p>";
        die("Error de conexión: " . $conexion->connect_error);
    }
    return $conexion;
}
?>
