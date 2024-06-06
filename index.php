<?php
session_start();
include_once 'config/main.php';
include_once 'config/cola.php';

comprobarUsuario();
$conexion = conectar();
$usuario_id = $_SESSION['usuario_id'];
$grupos = obtenerGrupos($conexion, $usuario_id);

?>

<!doctype html>
<html lang="es">

<head>
    <title>SoundWave</title> <!-- Required meta tags -->
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
    <link rel="shortcut icon" href="assets/img/icon.jpg" type="image/x-icon">
</head>

<body>

    <!-- Creamos el contenedor principal de la página -->
    <canvas id="particles"></canvas>
    <main class="container-main row">
        <!-- COLUMNA DE LA IZQUIERA -->
        <div class="col-2 columna-izquierda">
            <div class="logo">
                <h2>Sound<strong>Wave</strong></h2>
            </div>

            <div class="m-auto">
                <div class="items-container">
                    <div class="item">
                        <a href="index.php" aria-label="Boton de inicio">
                            <i class="fas fa-home"></i>
                            Inicio
                        </a>
                    </div>
                    <div class="item">
                        <a href="perfil.php" aria-label="Boton de perfil">
                            <i class="fas fa-user"></i>
                            Perfil
                        </a>
                    </div>
                    <div class="item">
                        <a href="admin-grupos.php" aria-label="Boton de Administrar Grupos y archivos">
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

                    <div class="item">
                        <a href="config/logout.php" aria-label="Boton de cerrar sesión">
                            <i class="fas fa-sign-out-alt"></i>
                            Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
            <!-- icono de color main de información que lleve a info.html -->
            <a href="info.php" class="linknodecoration" aria-label="Boton de informacion y terminos">
                <i class="fas fa-info-circle"></i>
            </a>

        </div>


        <!-- COLUMNA DEL CENTRO -->
        <div class="col-7 columna-centro">
            <h1 class="bienvenida">
                Bienvenido
                <?php echo isset($_SESSION['usuario']) ? ucwords($_SESSION['usuario']) : 'Invitado'; ?>
            </h1>
            <h2>Tus grupos:</h2>
            <div class="texto-empty" style="display: <?= count($grupos) > 0 ? 'none' : 'flex' ?>;">
                <p class="col-12">No tienes ningun grupo creado</p>
            </div>

            <div class="playlists-container">


                <!-- Tarjeta de grupo, ejemplo: -->
                <!-- <div class="playlist-card">
                    <img src="assets/img/grupo.jpg" alt="Imagen de la lista de reproducción">
                    <p>Lista de reproducción 1</p>
                    <div class="options">
                        <i class="fas fa-ellipsis-h"></i>
                        <div class="dropdown-menu">
                            <ul class="list-menu">
                                <li><a href="#" class="delete">Eliminar Playlist</a></li>
                                <li><a href="#" class="edit">Editar Playlist</a></li>
                            </ul>
                        </div>
                    </div>
                </div>  -->

                <!-- Tarjetas de grupos -->
                <?php
                foreach ($grupos as $grupo) {
                    echo "<div value='" . $grupo['grupo_id'] . "' class='playlist-card'>";
                    echo "<img src='assets/img/grupo.jpg' alt='Imagen del Grupo 1'>";
                    echo "<p>" . $grupo['nombre'] . "</p>";
                    echo "<div class='options'>";
                    echo "<i class='fas fa-ellipsis-h'></i>";
                    echo "<div class='dropdown-menu'>";
                    echo "<ul class='list-menu'>";
                    echo "<li><a href='admin-grupos.php' class='edit'>Editar Grupo</a></li>";
                    echo "</ul>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>

            </div>
        </div>

        <!-- COLUMNA DE LA DERECHA -->
        <div class="col-2 columna-derecha ">
            <div class="lista-canciones mx-auto">
                <h2>Cola</h2>
                <!-- <div class="texto-empty" style="display: flex;">
                    <p class="col-12">No tienes ningun archivo</p>
                </div> -->
                <?php
                $archivos = obtenerArchivos($conexion, $usuario_id);
                if (count($archivos) == 0) {
                    echo "<div class='texto-empty' style='display: flex;'>";
                    echo "<p class='col-12'>No tienes ningun archivo</p>";
                    echo "</div>";
                }
                ?>
                <ul class="canciones">
                    <!-- <li>Strobe - Deadmau5</li> -->


                </ul>
            </div>
            <!-- Div abajo amarilla para pulsarle y que aparezcan todas las canciones -->
            <button id="verTodosArchivosUser" class="ver-todas-canciones">Ver todos los archivos
            </button>
        </div>

        <!-- Reproductor de música -->
        <div class="music-player">
            <audio style="display: none;" id="reproductor" controls>
                <source id="rutaReproduciendo" src="" type="audio/*">
                Tu navegador no soporta el elemento de audio.
            </audio>

            <!-- Nombre del archivo reproduciendose -->
            <p id="currentFile" class="song-name">--</p>
            <p id="durationCurrent" class="durationCurrent">00:00 - 00:00</p>
            <div class="progress-bar">
                <progress id="progress" value="0" max="100"></progress>
            </div>

            <div class="player-controls">
                <button class="control-button loop" aria-label="Loop"><i class="fas fa-sync"></i></button>
                <button class="control-button previous" aria-label="Anterior"><i class="fas fa-backward"></i></button>
                <button class="control-button play-pause" aria-label="Play/Pause"><i id="playPauseIcon" class="fas fa-play"></i></button>
                <button class="control-button next" aria-label="Siguiente"><i class="fas fa-forward"></i></button>
                <button class="control-button shuffle" aria-label="Aleatorio"><i class="fas fa-random"></i></button>
            </div>
            <!-- Control de volumen -->
            <div class="panel-volumen col-12">
                <i class="fas fa-volume-up"></i>
                <input type="range" aria-label="Volumen" id="volume" name="volume" min="0" max="1" step="0.01" value="1">
            </div>
        </div>


        <footer class="footer">
            <p>&copy; 2024 SoundWave. Todos los derechos reservados.</p>
        </footer>
    </main>

    <script src="assets/js/gradient-backgound.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/cola.js"></script>


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