<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once 'main.php';
// Verificar si el usuario es administrador
comprobarAdmin();

// Verificar si se envió la solicitud para eliminar un usuario
if (isset($_GET['eliminar_usuario'])) {
    $nombreUsuario = $_GET['eliminar_usuario'];

    // Lógica para eliminar el usuario de la base de datos
    $conexion = conectar();
    $sql = "DELETE FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $nombreUsuario);
    if ($stmt->execute()) {
        // Si la eliminación es exitosa, puedes mostrar un mensaje de éxito
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        El usuario "' . htmlspecialchars($nombreUsuario) . '" se eliminó correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    } else {
        // Si hubo un error en la eliminación, muestra un mensaje de error
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Error al eliminar el usuario.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    $stmt->close();
    $conexion->close();
}

// Obtener la lista de usuarios desde la base de datos
$usuarios = obtenerUsuarios();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conexion = conectar();

    if (isset($_POST['action'])) {

        if ($_POST['action'] == 'ver_grupos') {
            $usuario_id = $_POST['usuario_id'];
            $sql = "SELECT grupo_id, nombre FROM grupos WHERE usuario_id = ?";
            $stmt = $conexion->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $usuario_id);
                $stmt->execute();
                $result = $stmt->get_result();

                // Si no hay grupos, mostrar un mensaje
                if ($result->num_rows == 0) {
                    echo '<div class="alert alert-info" role="alert">Este usuario no tiene grupos.</div>';
                }

                while ($grupo = $result->fetch_assoc()) {
                    echo '<div class="file-group-item">';
                    echo '<span>' . htmlspecialchars($grupo['nombre']) . '</span>';
                    echo '<div class="file-group-actions" value="' . $grupo['grupo_id'] . '">';
                    echo '<button class="btn btn-danger delete-group"><i class="fas fa-trash"></i></button>';
                    echo '</div>';
                    echo '</div>';
                }

                $stmt->close();
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error en la preparación de la consulta: ' . $conexion->error . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
            exit;
        }

        if ($_POST['action'] == 'ver_archivos') {
            $usuario_id = $_POST['usuario_id'];
            $sql = "SELECT archivo_id, titulo FROM archivos WHERE usuario_id = ?";
            $stmt = $conexion->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $usuario_id);
                $stmt->execute();
                $result = $stmt->get_result();

                // Si no hay archivos, mostrar un mensaje
                if ($result->num_rows == 0) {
                    echo '<div class="alert alert-info" role="alert">Este usuario no tiene archivos.</div>';
                }

                while ($archivo = $result->fetch_assoc()) {
                    echo '<div class="file-group-item">';
                    echo '<span>' . htmlspecialchars($archivo['titulo']) . '</span>';
                    echo '<div class="file-group-actions" value="' . $archivo['archivo_id'] . '">';
                    echo '<button class="btn btn-danger delete-file"><i class="fas fa-trash"></i></button>';
                    echo '</div>';
                    echo '</div>';
                }

                $stmt->close();
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error en la preparación de la consulta: ' . $conexion->error . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
            exit;
        }


        if ($_POST['action'] == 'eliminar_grupo') {
            $grupo_id = $_POST['grupo_id'];
            $sql = "DELETE FROM grupos WHERE grupo_id = ?";
            $stmt = $conexion->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $grupo_id);
                if ($stmt->execute()) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Grupo eliminado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error al eliminar el grupo.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
                }
                $stmt->close();
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error en la preparación de la consulta: ' . $conexion->error . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
            exit;
        }

        if ($_POST['action'] == 'eliminar_archivo') {
            $archivo_id = $_POST['archivo_id'];
            $sql = "DELETE FROM archivos WHERE archivo_id = ?";
            $stmt = $conexion->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $archivo_id);
                if ($stmt->execute()) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Archivo eliminado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Error al eliminar el archivo.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';                }
                $stmt->close();
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Error en la preparación de la consulta: ' . $conexion->error . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
            exit;
        }


    }
}
?>