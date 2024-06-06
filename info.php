<!DOCTYPE html>
<html lang="es">

<head>
    <title>SoundWave / Informacion y Terminos</title> <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Dancing+Script&family=Tenali+Ramakrishna&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="assets/css/info.css">
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

    <canvas id="particles"></canvas>

    <div class="container">
        <div class="info">
            <div class="logoinfo">
                <h2>Sound<strong>Wave</strong></h2>
            </div>

            <h1>Información y ayuda</h1>
            <h3>¿Qué es SoundWave?</h3>
            <p>SoundWave es una plataforma web que actúa como almacenamiento y reproductor en línea. Permite a los
                usuarios cargar archivos de audio y reproducirlos en cualquier momento y dispositivo con total seguridad
                y privacidad. Además, ofrece la capacidad de crear y gestionar grupos para organizar los archivos por
                temas, categorías o preferencias individuales.</p>

            <hr>
            <h2>Navegación</h2>
            <p>A la izquierda en la página de inicio se puede ver este menú, donde cada usuario podrá navegar según sus
                necesidades. Este menú está disponible desde todas las páginas de SoundWave de forma desplegable.</p>
            <div class="menu">
                <ul>
                    <li><i class="fas fa-home"></i> Inicio</li>
                    <li><i class="fas fa-user"></i> Perfil</li>
                    <li><i class="fas fa-music"></i> Administrar Grupos</li>
                    <li><i class="fas fa-upload"></i> Subir Archivos</li>
                    <li><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</li>
                </ul>
            </div>

            <hr>
            <h2>Archivos</h2>
            <h3>¿Cómo subir un archivo?</h3>
            <p>Para subir un archivo de audio, simplemente haga clic en el apartado <strong>"Subir Archivos"</strong> en
                el menú lateral.
                A continuación, seleccione o arrastre el archivo que desea cargar y haga clic en el botón
                <strong>"Subir"</strong>.
            </p>
            <p>Cuando el usuario pulse en <strong>“Subir Archivos”</strong>, deberá esperar unos segundos o minutos
                dependiendo del tamaño del archivo o de su conexión. Tras eso, ya estará su archivo disponible para ser
                reproducido.</p>

            <hr>
            <h2>Grupos</h2>
            <h3>¿Cómo crear un grupo?</h3>
            <p>Para crear un grupo, haga clic en el apartado <strong>"Administrar Grupos"</strong> en el menú lateral. A
                continuación,
                haga clic en el botón <strong>"Crear Grupo"</strong> e introduzca el nombre del grupo. Por último, haga
                clic en el botón <strong>"Crear"</strong> para finalizar.</p>

            <h3>¿Cómo añadir archivos a un grupo?</h3>
            <p>Para añadir archivos a un grupo, haga clic en el apartado <strong>"Administrar Grupos"</strong> en el
                menú lateral. A
                continuación, seleccione el grupo al que desea añadir archivos y haga clic en <i
                    class="fas fa-plus"></i> sobre la canción que
                deseas añadir al grupo previamente seleccionado.</p>

            <h3>¿Cómo eliminar un archivo de un grupo?</h3>
            <p>Para eliminar un archivo de un grupo, haga clic en el apartado <strong>"Administrar Grupos"</strong> en
                el menú lateral. A
                continuación, haga clic en <i class="fas fa-trash-alt"></i> sobre la canción que deseas eliminar.</p>
            <p>Si deseas eliminar el archivo permanentemente, deberás hacerlo en la ventana <strong>“Añadir archivos a
                    la lista / Eliminar archivo”</strong>.</p>

            <hr>
            <h2>Página Principal y Reproductor de Audio</h2>
            <p>En la página principal se encuentra la función principal de SoundWave. Cuando se pulse en un grupo, la
                cola se actualizará y aparecerán los archivos vinculados a ese grupo. También se podrá pulsar en
                <strong>“Ver todos los archivos”</strong> y la cola se actualizará para mostrar todos los archivos
                subidos por el usuario.
            </p>
            <h3>Reproductor de Audio</h3>
            <p>En el reproductor se pueden encontrar los botones de:</p>
            <ul>
                <li><i class="fas fa-play"></i> Play</li>
                <li><i class="fas fa-pause"></i> Pausa</li>
                <li><i class="fas fa-forward"></i> Siguiente</li>
                <li><i class="fas fa-backward"></i> Anterior</li>
                <li><i class="fas fa-random"></i> Aleatorio</li>
                <li><i class="fas fa-sync"></i> En bucle</li>
                <li><i class="fas fa-volume-up"></i> Volumen</li>
            </ul>
            <p>El usuario también podrá pulsar en la barra de progreso para mover el archivo a una posición determinada.
            </p>

            <hr>
            <h2>Perfil</h2>
            <h3>¿Qué información se puede encontrar en la página de perfil?</h3>
            <p>En la página <strong>“Perfil”</strong> el usuario puede encontrar información sobre su cuenta, como:</p>
            <ul>
                <li><i class="fas fa-clock"></i> Horas escuchando archivos de audio</li>
                <li><i class="fas fa-file-audio"></i> Archivos subidos</li>
                <li><i class="fas fa-users"></i> Grupos creados</li>
            </ul>

            <hr>
            <div class="terms">
                <h2>Términos de Uso y Privacidad</h2>
                <h3>Términos de Uso</h3>
                <p>SoundWave es una plataforma diseñada para que los usuarios puedan cargar y reproducir archivos de
                    audio
                    de forma segura y privada. Sin embargo, SoundWave no se hace responsable del uso que los usuarios
                    puedan
                    darle a la plataforma ni de los archivos que puedan subir. Está estrictamente prohibido compartir
                    archivos subidos en SoundWave.</p>

                <h3>Política de Privacidad</h3>
                <p>SoundWave se compromete a proteger la privacidad de sus usuarios. Toda la información personal y
                    archivos
                    subidos se mantienen confidenciales y se utilizan únicamente para proporcionar los servicios de la
                    plataforma. No compartimos información personal con terceros sin el consentimiento explícito del
                    usuario.</p>
            </div>
        </div>
    </div>




    <footer class="footer">
        <p>&copy; 2024 SoundWave. Todos los derechos reservados.</p>
    </footer>

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