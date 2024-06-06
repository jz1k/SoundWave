<?php
session_start();
include_once 'config/main.php';
include_once ('vendor/autoload.php');

comprobarUsuario();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_archivo'])) {
    // Verifica si se subieron archivos
    if (!empty($_FILES['archivo']['name'][0])) {
        $nombre_usuario = $_SESSION['usuario']; // Nombre de usuario
        $carpeta_usuario = 'users/' . $nombre_usuario . '/'; // Carpeta del usuario

        // Verifica si la carpeta del usuario existe, si no, créala
        if (!file_exists($carpeta_usuario)) {
            mkdir($carpeta_usuario, 0777, true);
        }

        // Conexión a la base de datos
        $conexion = conectar();
        if ($conexion->connect_error) {
            die("Error de conexión a la base de datos: " . $conexion->connect_error);
        }

        // Procesa cada archivo subido
        foreach ($_FILES['archivo']['name'] as $key => $nombre_archivo) {
            $archivo_temporal = $_FILES['archivo']['tmp_name'][$key];
            $archivo_destino = $carpeta_usuario . $nombre_archivo;

            // Verifica si el archivo es un archivo de audio
            if (isset($_FILES['archivo'])) {
                $allowedTypes = array('mpeg', 'mp3', 'wav', 'ogg', 'flac', 'm4a', 'wma', 'aac', 'aiff', 'alac', 'ape', 'dsd', 'pcm', 'mp2', 'mp1', 'm4b', 'm4p', 'm4r', '3gp', '3g2', 'mka', 'webm', 'amr', 'au', 'aif', 'aifc', 'snd', 'oga', 'spx', 'opus', 'mid', 'midi', 'kar', 'rmi', 'miz', 'mod', 'mo3', 'it', 's3m', 'xm', 'mtm', 'umx', 'mdz', 's3z', 'xmz', 'itz');
                $fileType = pathinfo($nombre_archivo, PATHINFO_EXTENSION);

                if (!in_array($fileType, $allowedTypes)) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            El archivo ' . $nombre_archivo . ' no es un archivo válido
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
                    break; // Salta a la siguiente iteración
                }

                // El archivo es un archivo de audio válido, continúa con el procesamiento del archivo
            }

            // Verifica si ya existe un archivo con el mismo nombre en la base de datos
            $usuario_id = $_SESSION['usuario_id'];
            $sql = "SELECT COUNT(*) as count FROM archivos WHERE usuario_id = ? AND titulo = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("is", $usuario_id, $nombre_archivo);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            El archivo ' . $nombre_archivo . ' ya existe
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
                continue; // Salta a la siguiente iteración
            }

            // Mueve el archivo a la carpeta del usuario
            if (move_uploaded_file($archivo_temporal, $archivo_destino)) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Archivo ' . $nombre_archivo . ' subido correctamente
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';

                // Obtiene la duración del archivo de audio
                $getID3 = new getID3;
                $file = $getID3->analyze($archivo_destino);
                $duracion = $file['playtime_string'];

                // Inserta la información del archivo en la base de datos
                $sql = "INSERT INTO archivos (usuario_id, titulo, ruta_archivo, duracion) VALUES (?, ?, ?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("isss", $usuario_id, $nombre_archivo, $archivo_destino, $duracion);
                if ($stmt->execute()) {
                    echo "";
                } else {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Error al registrar el archivo ' . $nombre_archivo . ' en la base de datos
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
                }
                $stmt->close();
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Error al subir el archivo ' . $nombre_archivo . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
        }

        // Cierra la conexión a la base de datos
        $conexion->close();
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            No se ha seleccionado ningún archivo
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
}

?>




<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundWave / Subir Archivos</title>
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Dancing+Script&family=Tenali+Ramakrishna&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/grupos.css">
    <link rel="stylesheet" href="assets/css/perfil.css">
    <link rel="stylesheet" href="assets/css/upload.css">
    <link rel="shortcut icon" href="assets/img/icon.jpg" type="image/x-icon">

</head>

<body>
    <button class="menu-button" aria-label="Boton Menu">
        <i class="fas fa-arrow-right" id="arrow-icon"></i>
    </button>
    <div class="col-2 columna-izquierda">
        <div class="logo">
            <h2>Sound<strong>Wave</strong></h2>
        </div>
        <hr>
        <div class="m-auto">
            <div class="items-container">
                <div class="item">
                    <a href="index.php" aria-label="Boton de inicio">
                        <i class="fas fa-home"></i> <!-- Cambié el ícono por uno de inicio -->
                        Inicio
                    </a>
                </div>
                <div class="item">
                    <a href="perfil.php" aria-label="Boton de Perfil">
                        <i class="fas fa-user"></i>
                        Perfil
                    </a>
                </div>
                <div class="item">
                    <a href="admin-grupos.php" aria-label="Boton de administrar grupos y archivos">
                        <i class="fas fa-music"></i>
                        Administrar Grupos
                    </a>
                </div>
                <div class="item">
                    <a href="upload.php" aria-label="Boton de Subir Archivos">
                        <i class="fas fa-upload"></i>
                        Subir Archivos
                    </a>
                </div>
                <hr>
                <div class="item">
                    <a href="config/logout.php" aria-label="Boton de cerrar sesion">
                        <i class="fas fa-sign-out-alt"></i>
                        Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
        <a href="info.php" class="linknodecoration" aria-label="Boton de informacion y terminos">
            <i class="fas fa-info-circle"></i>
        </a>
    </div>
    <canvas id="particles"></canvas>

    <div class="upload-container">
        <h2>Subir archivos</h2>
        <div id="dropzone">
            <form id="uploadForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"
                enctype="multipart/form-data">
                <input type="file" name="archivo[]" id="fileInput" style="display: none;" accept="audio/*" multiple />
                <label for="fileInput">
                    <i class="fas fa-upload fa-2x"></i>
                    <hr>
                    <p id="fileInputText">Arrastra y suelta archivos aquí o haz clic para seleccionar</p>
                    <div id="selectedFiles" style="display: none;">
                        <ul id="fileList"></ul>
                    </div>
                </label>
                <progress id="progressBar" value="0" max="100" style="display: none;"></progress>
                <button type="submit" aria-label="Boton Subir Archivos" name="agregar_archivo" id="uploadButton" class="btn btn-primary"
                    style="display: none;">Subir Archivos</button>
            </form>
        </div>
    </div>




    <footer class="footer">
        <p>&copy; 2024 SoundWave. Todos los derechos reservados.</p>
    </footer>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/gradient-backgound.js"></script>
    <script src="assets/js/menu.js"></script>
    <script src="assets/js/upload.js"></script>



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