<?php
include_once 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


function obtenerUsuarios()
{
    $conexion = conectar();
    $sql = "SELECT * FROM usuarios";
    $resultado = $conexion->query($sql);
    $usuarios = [];
    if ($resultado->num_rows > 0) {
        while ($usuario = $resultado->fetch_assoc()) {
            $usuarios[] = $usuario;
        }
    }
    return $usuarios;
}

function login($usuario, $contrasena)
{
    $usuario = strtolower($usuario);
    $conexion = conectar();
    $sql = "SELECT usuario_id, nombre_usuario, contrasena, horas_escuchadas FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $hash = $usuario['contrasena'];

        if (password_verify($contrasena, $hash)) {
            // La contraseña es correcta y ya está hasheada
            iniciarSesion($usuario);
        } else if ($hash === $contrasena) {
            // La contraseña está almacenada en texto plano
            $nuevoHash = password_hash($contrasena, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET contrasena = ? WHERE usuario_id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("si", $nuevoHash, $usuario['usuario_id']);
            $stmt->execute();

            iniciarSesion($usuario);
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Usuario o contraseña incorrectos
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Usuario o contraseña incorrectos
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }

    $stmt->close();
    $conexion->close();
}

function iniciarSesion($usuario)
{
    $_SESSION['usuario'] = $usuario['nombre_usuario'];
    $_SESSION['usuario_id'] = $usuario['usuario_id'];
    header("Location: ../~juancarlos/index.php");
    exit;
}



function registrarUsuario($nombre_usuario, $contrasena, $contrasenaConf, $rol = 'usuario')
{
    $conexion = conectar();
    if ($contrasena != $contrasenaConf) {
        echo 'Las contraseñas no coinciden';
        return;
    }

    // Verificar si el usuario ya existe
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows > 0) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            El usuario ya existe
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        return;
    }

    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $nombre_usuario = strtolower($nombre_usuario); // Convertir el nombre de usuario a minúsculas (opcional)
    $sql = "INSERT INTO usuarios (nombre_usuario, contrasena, rol) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $nombre_usuario, $hash, $rol);
    if ($stmt->execute()) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Usuario registrado exitosamente
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Error al registrar el usuario: ' . $stmt->error . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    $stmt->close();
    $conexion->close();
}


// Funcion para comprobar que el usuario es de rol usuario, si no lo es, lo redirige a la pagina de logout.php
// Si el usuario que entra es admin, redirigir a administrador.php
function comprobarUsuario()
{
    if (isset($_SESSION['usuario'])) {
        $usuario = $_SESSION['usuario'];
        $conexion = conectar();
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            if ($usuario['rol'] == 'admin') {
                header("Location: administrador.php");
                exit;
            } elseif ($usuario['rol'] != 'usuario') {
                header("Location: config/logout.php");
                exit;
            }
        } else {
            header("Location: config/logout.php");
            exit;
        }
        $stmt->close();
        $conexion->close();
    } else {
        header("Location: config/logout.php");
        exit;
    }
}

function comprobarAdmin()
{
    if (isset($_SESSION['usuario'])) {
        $usuario = $_SESSION['usuario'];
        $conexion = conectar();
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            if ($usuario['rol'] != 'admin') {
                header("Location: index.php");
                exit;
            }
        } else {
            header("Location: logout.php");
            exit;
        }
        $stmt->close();
        $conexion->close();
    } else {
        header("Location: logout.php");
        exit;
    }
}

function obtenerGrupos($conexion, $usuario_id)
{
    $query = "SELECT nombre, grupo_id FROM grupos WHERE usuario_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $grupos = [];
    while ($row = $result->fetch_assoc()) {
        $grupos[] = $row;
    }

    $stmt->close();
    return $grupos;
}

function obtenerArchivos($conexion, $usuario_id)
{
    $query = "SELECT archivo_id, titulo, ruta_archivo, duracion FROM archivos WHERE usuario_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $archivos = [];
    while ($row = $result->fetch_assoc()) {
        $archivos[] = $row;
    }

    $stmt->close();
    return $archivos;
}

function obtenerHorasEscuchadas($conexion, $usuario_id)
{
    $query = "SELECT horas_escuchadas FROM usuarios WHERE usuario_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $horas = $result->fetch_assoc();
    $stmt->close();
    return $horas['horas_escuchadas'];
}

function comprobarGrupoExistente()
{
    $conexion = conectar();
    $usuario_id = $_SESSION['usuario_id'];
    $grupos = obtenerGrupos($conexion, $usuario_id);
    if (count($grupos) > 0) {
        return;
    }
}

function comprobarArchivoExistente()
{
    $conexion = conectar();
    $usuario_id = $_SESSION['usuario_id'];
    $archivos = obtenerArchivos($conexion, $usuario_id);
    if (count($archivos) > 0) {
        return;
    }
}

function eliminarGrupo($grupo_id)
{
    $conexion = conectar();
    $stmt = $conexion->prepare("DELETE FROM grupos WHERE grupo_id = ?");
    $stmt->bind_param("i", $grupo_id);
    $stmt->execute();
    $stmt->close();
    $conexion->close();
}

function eliminarArchivo($archivo_id)
{
    $conexion = conectar();
    $stmt = $conexion->prepare("DELETE FROM archivos WHERE archivo_id = ?");
    $stmt->bind_param("i", $archivo_id);
    $stmt->execute();
    $stmt->close();
    $conexion->close();
}

function eliminarArchivoGrupo($grupo_id, $archivo_id)
{
    $conexion = conectar();
    $stmt = $conexion->prepare("DELETE FROM grupo_archivo WHERE grupo_id = ? AND archivo_id = ?");
    $stmt->bind_param("ii", $grupo_id, $archivo_id);
    $stmt->execute();
    $stmt->close();
    $conexion->close();
}

?>