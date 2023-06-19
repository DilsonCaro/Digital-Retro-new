<?php
if (isset($_REQUEST['registro'])) {
    include_once "../admin/bdecommerce.php";
    $con = mysqli_connect($host, $user, $pass, $db);

    $nombre = mysqli_real_escape_string($con, $_REQUEST['nombre'] ?? '');
    $email = mysqli_real_escape_string($con, $_REQUEST['email'] ?? '');
    $usuario = mysqli_real_escape_string($con, $_REQUEST['usuario'] ?? '');
    $pass = md5(mysqli_real_escape_string($con, $_REQUEST['pass'] ?? ''));
    $telefono = mysqli_real_escape_string($con, $_REQUEST['phone'] ?? '');

    $query = "INSERT INTO cuenta_cliente
        (nombre, email, usuario, pass, telefono) VALUES
        ('" . $nombre . "','" . $email . "','" . $usuario . "','" . $pass . "','" . $telefono . "')
        ";
    $res = mysqli_query($con, $query);
    if ($res) {
?>
        <div class="alert alert-primary" role="alert">
            Usuario registrado con exito <?php echo mysqli_error($con); ?>
        </div>
    <?php
    } else {
    ?>
        <div class="alert alert-danger" role="alert">
            Error al registrar usuario <?php echo mysqli_error($con); ?>
        </div>
<?php

    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="images/logo.jpg">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/register.css">
    <title>Registro Cliente</title>
</head>

<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="" method="POST" class="formulario">
                    <h2 class="titulo">Registro Digital Retro</h2>


                    <div class="inputbox">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <input type="text" name="nombre" required>
                        <label for="">Nombre</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" required>
                        <label for="">Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="usuario" required>
                        <label for="">Nombre usuario</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="pass" required>
                        <label for="">Contraseña</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="call-outline"></ion-icon>
                        <input type="number" name="phone" required>
                        <label for="">telefono</label>
                    </div>
                    <div class="forget">
                        <button type="submit" value="Registrar" name="registro">Registrar</button>
                        <!-- <button href="index.php">Volver</button> -->
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>