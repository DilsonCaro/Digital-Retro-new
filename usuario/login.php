<?php
require 'servicios/config.php';
require 'servicios/conexion.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$proceso = isset($_GET['pago']) ? 'pago' : 'login';

$errors = [];

if (!empty($_POST)) {

    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $proceso = $_POST['proceso'] ?? 'login';

    if (esNulo([$usuario, $password])) {
        $errors[] = "Debe llenar todos los campos";
    }
    if (count($errors) == 0) {
        $errors[] = login($usuario, $password, $con, $proceso);
    }


}


?>

<!doctype html>
<html lang="en">

<head>
    <link rel="icon" href="images/logo.jpg">
    <link rel="stylesheet" href="css/loginC.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Cliente</title>
</head>

<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="login.php" method="POST" autocomplete="off">
                    <h2>Login Digital Retro</h2>
                    <?php mostrarMensajes($errors); ?>
                <input type="hidden" name="proceso" value="<?php echo $proceso;?>">
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="usuario" name="usuario" id="usuario" required>
                        <label for="usuario">Usuario</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" id="password" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="forget">
                        <label for=""><input type="checkbox" value="">Recordarme <a href="recuperarC.php">¿Olvido su
                                contraseña? </a></label>
                    </div>
                    <button type="submit" name="login">Iniciar Sesión</button>
                    <div class="register">
                        <p>No tengo una cuenta <a href="registro.php">Registrarse</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>