<?php
session_start();
include_once 'config/main.php';
include_once 'config/grupos-conf.php';

comprobarUsuario();

$conexion = conectar();
$usuario_id = $_SESSION['usuario_id'];
$archivos = obtenerArchivos($conexion, $usuario_id);

?>

<!doctype html>
<html lang="es">

<head>
    <title>SoundWave / Administra tus archivos</title> <!-- Required meta tags -->
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



    <!-- Creamos el contenedor principal de la página -->
    <canvas id="particles"></canvas>

    <main class="container-main row">

        <!-- COLUMNA DE TODOS LOS GRUPOS -->
        <div class="columna-todos">
            <h2>Todos los grupos</h2>
            <hr>
            <button id="nuevo-grupo" aria-label="Nuevo grupo" class="col-5 mx-auto">Nuevo Grupo</button>
            <div id="nuevo-grupo-form" class="mx-auto" style="display: none;">
                <form action="config/grupos-conf.php" method="post">
                    <input type="text" name="nombre_grupo" placeholder="Nombre del grupo">
                    <button type="submit" aria-label="Crear grupo" name="crear_grupo">Crear Grupo</button>
                </form>

            </div>
            <div class="grupos-container">
                <br>
                <div>
                    <ul class="grupo-lista">
                        <!-- <li>
                            <img src="assets/img/grupo.jpg" alt="Imagen del Grupo 1">
                            Grupo 1
                            <i class="fas fa-trash-alt"></i>
                        </li> -->
                        <!-- Repite para cada grupo... -->

                        <?php


                        $grupos = obtenerGrupos($conexion, $usuario_id);
                        foreach ($grupos as $grupo) {
                            echo "<li value='" . $grupo['grupo_id'] . "'>";
                            echo "<img src='assets/img/grupo.jpg' alt='Imagen del Grupo 1'>";
                            echo $grupo['nombre'];
                            echo "<i class='fas fa-trash-alt iconAd delete-group'></i>";
                            echo "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- COLUMNA ADMINISTRAR GRUPOS -->
        <div class="col-6 derecha-grupos">
            <div class="col-12 columna-adm-grupos">
                <h2>Administrar grupos</h2>
                <div class="scroll-list">
                    <ul type="1" start="1" class="archivo-lista ">
                        <!-- <li>
                            <span class="file-name">Nombre del archivo de audio 1</span>
                            <span class="duracion">3:45</span>
                            <i class="fas fa-trash-alt"></i>
                        </li> -->
                        <!-- Cuando se pulsa en un grupo, que aparezca los archivos que tiene añadidos este -->






                    </ul>
                </div>
            </div>

            <!-- COLUMNA AÑADIR ARCHIVOS AL GRUPO -->
            <div class="col-12 columna-adm-grupos">
                <h2>Añadir archivos a lista / Eliminar archivo</h2>
                <div class="busqueda">
                    <input type="text" id="buscar-archivo" name="buscar-archivo" placeholder="Buscar archivo...">
                </div>
                <div class="lista-archivos scroll-list">
                    <ul class="archivos">
                        <?php
                        foreach ($archivos as $archivo) {
                            echo "<li value='" . $archivo['archivo_id'] . "'>";
                            echo "<span class='nombre-archivo'>" . $archivo['titulo'] . "</span>";
                            echo "<span class='duracion'>" . $archivo['duracion'] . "</span>";
                            echo "<i class='fas fa-plus add-to-group iconAd' value='" . $archivo['archivo_id'] . "'></i>";
                            echo "<i class='fas fa-trash-alt iconAd delete-file'></i>";
                            echo "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>



        </div>
    </main>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/gradient-backgound.js"></script>
    <script src="assets/js/menu.js"></script>
    <script src="assets/js/admin-files.js"></script>


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