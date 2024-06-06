<?php
session_start();
include_once 'config/main.php';
include_once 'config/admin-conf.php';

// Comprobamos si el usuario tiene el rol adecuado
comprobarAdmin();
$usuarios = obtenerUsuarios();

if (isset($_POST['registerAdmin'])) {
    $nombre_usuario = $_POST['usuario-reg'];
    $contrasena = $_POST['contrasena-reg'];
    $contrasenaConf = $_POST['contrasena-conf'];
    $rol = $_POST['rol'];
    registrarUsuarioDesdeAdmin($nombre_usuario, $contrasena, $contrasenaConf, $rol);
}

// Código para crear un nuevo usuario
function registrarUsuarioDesdeAdmin($nombre_usuario, $contrasena, $contrasenaConf, $rol)
{
    $conexion = conectar();
    if ($contrasena != $contrasenaConf) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Las contraseñas no coinciden
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
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
            Usuario registrado correctamente
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Error al registrar el usuario ' . $stmt->error . ' 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    $stmt->close();
    $conexion->close();
}

?>

<!doctype html>
<html lang="es">

<head>
    <title>SoundWave / Perfil</title> <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Dancing+Script&family=Tenali+Ramakrishna&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="shortcut icon" href="assets/img/icon.jpg" type="image/x-icon">

</head>

<body>


    <!-- Menú -->
    <canvas id="particles"></canvas>

    <div class="logo titulo">
        <h1>Sound<strong>Wave</strong></h1>
        <h2>Panel de Administrador -
            <?php echo isset($_SESSION['usuario']) ? ucwords($_SESSION['usuario']) : 'Invitado'; ?>
        </h2>
        <a href="config/logout.php" class="no-decoration">
            <i class="fas fa-sign-out-alt"></i>
            Cerrar Sesión
        </a>
    </div>

    <div class="container-main">
        <div class="columna-derecha col">
            <br>
            <h2>Gestión de Usuarios</h2>
            <button id="nuevoUsuario" class="btn btn-success add-user-btn"><i class="fas fa-user-plus"></i> Crear Nuevo
                Usuario</button>
            <div class="add-user-form" style="display: none">

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="mb-3">
                        <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="usuario-reg" name="usuario-reg" required>
                    </div>
                    <div class="mb-3">
                        <label for="contrasena" class="form-label
                            ">Contraseña</label>
                        <input type="password" class="form-control" id="contrasena-reg" name="contrasena-reg" required>
                    </div>
                    <div class="mb-3">
                        <label for="contrasena" class="form-label
                            ">Confirmar contraseña</label>
                        <input type="password" class="form-control" id="contrasena-conf" name="contrasena-conf"
                            required>
                    </div>
                    <!-- Tipo de usuario (usuario o admin) -->
                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <select class="form-select" id="rol" name="rol">
                            <option value="usuario">Usuario</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                    <input class="btn-login" type="submit" name="registerAdmin" value="Registrar usuario">
                </form>

            </div>
            <br>
            <div class="user-list">
                <!-- <div class="user-item">
                    <span>Usuario 1</span>
                    <div class="user-actions">
                        <button class="btn btn-danger"><i class="fas fa-user-times"></i></button>
                        <button class="btn btn-info"><i class="fas fa-folder-open"></i> Ver Archivos</button>
                        <button class="btn btn-info"><i class="fas fa-users"></i> Ver Grupos</button>

                    </div>1
                </div> -->
                <!-- Agrega más usuarios según sea necesario -->

                <?php
                // Obtener la lista de usuarios desde la base de datos
                

                // Mostrar la lista de usuarios
                foreach ($usuarios as $usuario) {
                    // Verifica si el usuario actual no es el usuario logueado
                    if ($usuario['nombre_usuario'] != $_SESSION['usuario']) {
                        echo '<div class="user-item">';
                        echo '<span>' . htmlspecialchars($usuario['nombre_usuario']) . '</span>';
                        echo '<div class="user-actions" value="' . $usuario['usuario_id'] . '">';
                        echo '<a href="administrador.php?eliminar_usuario=' . urlencode($usuario['nombre_usuario']) . '" class="btn btn-danger delete-btn"><i class="fas fa-user-times"></i> Eliminar</a>'; // Enlace para eliminar
                        echo '<button id="verArchivos" class="btn btn-info btn-archivo"><i class="fas fa-folder-open"></i> Ver Archivos</button>';
                        echo '<button id="verGrupos" class="btn btn-info btn-grupo"><i class="fas fa-users"></i> Ver Grupos</button>';
                        echo '</div>';
                        echo '</div>';
                    }
                }

                ?>

            </div>
        </div>

        <div class="columna-derecha col">
            <br>
            <h2>Grupos y Archivos por Usuario</h2>
            <div class="file-group-list col-6">
                <!-- <div class="file-group-item">
                    <span>Archivo</span>
                    <div class="file-group-actions">
                        <button class="btn btn-danger"><i class="fas fa-trash"></i></button>
                    </div>
                </div> -->
                <!-- Agrega más usuarios según sea necesario -->


            </div>
        </div>
    </div>

    <script src="assets/js/cola.js"></script>
    <script src="assets/js/login.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/gradient-backgound.js"></script>
    <script src="assets/js/menu.js"></script>
    <script src="assets/js/administrador.js"></script>

    <script>
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const confirmacion = confirm('¿Estás seguro de que quieres eliminar este usuario?');
                if (confirmacion) {
                    window.location.href = btn.getAttribute('href');
                }
            });
        });
    </script>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
        </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
        </script>
</body>

</html>