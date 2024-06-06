<?php
session_start();
include_once 'config/main.php';

comprobarUsuario();




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
    <link rel="stylesheet" href="assets/css/grupos.css">
    <link rel="stylesheet" href="assets/css/perfil.css">
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

    <!-- Menú -->
    <canvas id="particles"></canvas>


    <main class="perfil-container row mx-auto">

        <div class="col-10 perfil-centro">
            <p>Perfil</p>
            <h1><?php echo isset($_SESSION['usuario']) ? ucwords($_SESSION['usuario']) : 'Invitado'; ?></h1>
            <div class="perfil-info">
                <div class="perfil-img">
                    <img src="assets/img/perfil.jpg" alt="Imagen de perfil">
                    <div class="perfil-options">
                        <a href="config/logout.php" class="close-profile">Cerrar sesión</a>
                    </div>
                </div>
                <br>
                <div class="perfil-data">
                    <div class="data-box">
                        <p>Has escuchado:</p>
                        <p class="data-number"><span>
                                <?php
                                $conexion = conectar();
                                $usuario_id = $_SESSION['usuario_id'];
                                $escuchados = obtenerHorasEscuchadas($conexion, $usuario_id);
                                //Pasamos a horas los segundos obtenidos
                                $escuchados = $escuchados / 3600;
                                $escuchados = round($escuchados, 2);
                                echo $escuchados;
                                ?>
                            </span> horas</p>
                    </div>
                    <div class="data-box">
                        <p>Has subido:</p>
                        <p class="data-number"><span>
                                <?php
                                $conexion = conectar();
                                $usuario_id = $_SESSION['usuario_id'];
                                $archivos = obtenerArchivos($conexion, $usuario_id);
                                echo count($archivos);
                                ?>
                            </span> archivos</p>
                    </div>
                    <div class="data-box">
                        <p>Has creado:</p>
                        <p class="data-number"><span>
                                <?php
                                $conexion = conectar();
                                $usuario_id = $_SESSION['usuario_id'];
                                $grupos = obtenerGrupos($conexion, $usuario_id);
                                echo count($grupos);
                                ?>
                            </span> grupos</p>
                    </div>
                </div>
            </div>
        </div>
        <h2>Tus Grupos:</h2>
        <div class="playlists-container tus-grupos">
            <!-- <div class="playlist-card">
                <img src="assets/img/grupo.jpg" alt="Imagen de la lista de reproducción">
                <p>Lista de reproducción 1</p>
            </div> -->

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
            </div> -->

            <?php
            $conexion = conectar();
            $usuario_id = $_SESSION['usuario_id'];
            $grupos = obtenerGrupos($conexion, $usuario_id);
            foreach ($grupos as $grupo) {
                echo "<div class='playlist-card'>";
                echo "<img src='assets/img/grupo.jpg' alt='Imagen del Grupo 1'>";
                echo "<p>" . $grupo['nombre'] . "</p>";
                echo "</div>";
            }
            ?>
            <!-- ... puedes agregar más tarjetas de listas de reproducción aquí ... -->
        </div>
    </main>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/gradient-backgound.js"></script>
    <script src="assets/js/menu.js"></script>

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