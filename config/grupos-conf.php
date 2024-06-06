<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once 'main.php'; // Aquí incluye tu archivo de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['crear_grupo'])) {
        $conexion = conectar();
        $nombre_grupo = $_POST['nombre_grupo'];
        $usuario_id = $_SESSION['usuario_id']; // Suponiendo que has almacenado el ID del usuario en $_SESSION

        // validar datos
        if (empty($nombre_grupo)) {
            echo "El nombre del grupo es obligatorio";
            header("Location: ../admin-grupos.php");
            exit;
        }

        // Insertar el nuevo grupo en la base de datos
        $query = "INSERT INTO grupos (usuario_id, nombre) VALUES ('$usuario_id', '$nombre_grupo')";
        $resultado = mysqli_query($conexion, $query);

        if ($resultado) {
            // El grupo se añadió correctamente
            header("Location: ../admin-grupos.php"); // Redirigir a la página de administración de grupos
        }
        $conexion->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conexion = conectar();

    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add_archivo_grupo') {
            $grupo_id = $_POST['grupo_id'];
            $archivo_id = $_POST['archivo_id'];

            $stmt = $conexion->prepare("INSERT INTO grupo_archivo (grupo_id, archivo_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $grupo_id, $archivo_id);

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "Error al añadir el archivo al grupo: " . $stmt->error;
            }

            $stmt->close();
            exit;
        }

        if ($_POST['action'] == 'get_archivos_grupo') {
            $grupo_id = $_POST['grupo_id'];

            $stmt = $conexion->prepare("SELECT archivos.archivo_id, archivos.titulo, archivos.duracion FROM archivos
                JOIN grupo_archivo ON archivos.archivo_id = grupo_archivo.archivo_id
                WHERE grupo_archivo.grupo_id = ?");
            $stmt->bind_param("i", $grupo_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($archivo = $result->fetch_assoc()) {
                echo "<li value='" . $archivo['archivo_id'] . "'>";
                echo "<span class='file-name'>" . $archivo['titulo'] . "</span>";
                echo "<span class='duracion'>" . $archivo['duracion'] . "</span>";
                echo "<i class='fas fa-trash-alt remove-from-group iconAd' value='" . $archivo['archivo_id'] . "'></i>";
                echo "</li>";
            }

            $stmt->close();
            exit;
        }

        if (isset($_POST['grupo_id']) && $_POST['action'] == 'eliminar_grupo') {
            $grupoId = $_POST['grupo_id'];

            // Realiza la eliminación del grupo
            if (eliminarGrupo($grupoId)) {
                echo "success"; // Envía una respuesta de éxito
            } else {
                echo "error"; // Envía una respuesta de error
            }
        }

        if (isset($_POST['archivo_id']) && $_POST['action'] == 'eliminar_archivo') {
            $archivoId = $_POST['archivo_id'];

            // Realiza la eliminación del archivo
            if (eliminarArchivo($archivoId)) {
                echo "success"; // Envía una respuesta de éxito
            } else {
                echo "error"; // Envía una respuesta de error
            }
        }

        // Elimina archivo del grupo
        if (isset($_POST['grupo_id']) && isset($_POST['archivo_id']) && $_POST['action'] == 'remove_archivo_grupo') {
            $grupoId = $_POST['grupo_id'];
            $archivoId = $_POST['archivo_id'];

            // Realiza la eliminación del archivo del grupo
            eliminarArchivoGrupo($grupoId, $archivoId);
        }
    }
}






?>

