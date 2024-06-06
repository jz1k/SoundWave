<?php

include_once 'config/main.php';

// Comprueba si el usuario está logueado, si lo está, lo redirige a la página de logout
if (isset($_SESSION['usuario']) or isset($_SESSION['admin'])) {
    header("Location: config/logout.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $usuario = $_POST['usuario'];
        $password = $_POST['contrasena'];
        login($usuario, $password);
    } elseif (isset($_POST['register'])) {
        $nombre_usuario = $_POST['usuario-reg'];
        $contrasena = $_POST['contrasena-reg'];
        $contrasenaConf = $_POST['contrasena-conf'];
        $rol = 'usuario';  // Puedes cambiar esto a 'admin' si es necesario
        registrarUsuario($nombre_usuario, $contrasena, $contrasenaConf, $rol);
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundWave - Login</title>
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Dancing+Script&family=Tenali+Ramakrishna&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="shortcut icon" href="assets/img/icon.jpg" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>



</head>

<body>
    <canvas id="particles"></canvas>
    <div class="login-container">

        <div class="logo">
            <h2>Sound<strong>Wave</strong></h2>
            <p id="greeting">¡Bienvenido!
            <p id="dynamic-text">

            </p>
            </p>

        </div>

        <form class="login-form" id="login-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <input class="btn-login" type="submit" name="login" value="Iniciar sesión">
        </form>

        <div class="register-link">
            <a href="#" id="show-register-form">Registrarse</a>
        </div>

        <form class="register-form" id="register-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="usuario-reg">Usuario</label>
                <input type="text" id="usuario-reg" name="usuario-reg" required>
            </div>
            <div class="form-group">
                <label for="contrasena-reg">Contraseña</label>
                <span style="font-size: small;" class="password-requirements">8 caracteres, mayúsculas, minúsculas,
                    dígitos y caracteres especiales</span>
                <input type="password" id="contrasena-reg" name="contrasena-reg" required>
            </div>
            <div class="form-group">
                <label for="contrasena-conf">Repite la contraseña</label>
                <input type="password" id="contrasena-conf" name="contrasena-conf" required>
            </div>
            <input class="btn-login" type="submit" name="register" value="Registrarse">
            <div id="error-messages" style="color: red; margin-top: 10px;"></div>
        </form>


    </div>

    <footer class="footer">
        <p>&copy; 2024 SoundWave. Todos los derechos reservados.</p>
    </footer>



    <script src="assets/js/login.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/gradient-backgound.js"></script>



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