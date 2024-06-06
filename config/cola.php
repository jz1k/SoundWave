<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once 'main.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conexion = conectar();

    if ($_POST['action'] == 'get_archivos_grupo') {
        $grupo_id = $_POST['grupo_id'];

        $stmt = $conexion->prepare("SELECT archivos.archivo_id, archivos.titulo, archivos.duracion, archivos.ruta_archivo FROM archivos
                JOIN grupo_archivo ON archivos.archivo_id = grupo_archivo.archivo_id
                WHERE grupo_archivo.grupo_id = ?");
        $stmt->bind_param("i", $grupo_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($archivo = $result->fetch_assoc()) {
            echo "<li value='" . $archivo['archivo_id'] . "' ruta='" . $archivo['ruta_archivo'] . "'>";
            echo $archivo['titulo'];
            echo "</li>";
        }

        $stmt->close();
        exit;
    }

    if ($_POST['action'] == 'get_archivos_usuario_activo') {
        $usuario_id = $_SESSION['usuario_id'];

        $stmt = $conexion->prepare("SELECT archivos.archivo_id, archivos.titulo, archivos.duracion, archivos.ruta_archivo FROM archivos
                WHERE archivos.usuario_id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($archivo = $result->fetch_assoc()) {
            echo "<li value='" . $archivo['archivo_id'] . "' ruta='" . $archivo['ruta_archivo'] . "'>";
            echo $archivo['titulo'];
            echo "</li>";
        }

        $stmt->close();
        exit;
    }

    if ($_POST['action'] == 'reproducir_archivo') {
        $archivo_id = $_POST['archivo_id'];

        $stmt = $conexion->prepare("SELECT ruta_archivo FROM archivos WHERE archivo_id = ?");
        $stmt->bind_param("i", $archivo_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $archivo = $result->fetch_assoc();

        echo $archivo['ruta_archivo'];

        $stmt->close();
        exit;
    }

    if ($_POST['action'] == 'update_horas_escuchadas') {
        $usuario_id = $_SESSION['usuario_id'];
        $horas_escuchadas = $_POST['horas_escuchadas'];
        //Obtenemos las horas escuchadas actuales
        $stmt = $conexion->prepare("SELECT horas_escuchadas FROM usuarios WHERE usuario_id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $horas_escuchadas_actuales = $result->fetch_assoc()['horas_escuchadas'];
        $stmt->close();

        //Actualizamos las horas escuchadas
        $stmt = $conexion->prepare("UPDATE usuarios SET horas_escuchadas = ? WHERE usuario_id = ?");
        $horas_escuchadas += $horas_escuchadas_actuales;
        $stmt->bind_param("di", $horas_escuchadas, $usuario_id);
        $stmt->execute();
        $stmt->close();
        exit;
    }

    





}





?>