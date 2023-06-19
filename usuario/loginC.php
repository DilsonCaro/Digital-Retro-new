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
    <?php
    if (isset($_REQUEST['loginC'])) {
        session_start();
        $email = $_REQUEST['email'] ?? '';
        $password = $_REQUEST['pass'] ?? '';
        $password = md5($password);
        include_once "../admin/bdecommerce.php";
        $con = mysqli_connect($host, $user, $pass, $db);
        $query = "SELECT id_cliente, nombre, email, usuario, telefono from cuenta_cliente where email='" . $email . "' and pass='" . $password . "'; ";
        $res = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($res);
        if ($row) {
            $_SESSION['id_cliente'] = $row['id_cliente'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['telefono'] = $row['telefono'];
            header("location: index.php");
        } else {
    ?>
            <div class="alert alert-danger" role="alert">
                Error al inciar sesion <!--<img src="images/error.jpg" width="200"> -->
            </div>
    <?php
        }
    }
    ?>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form acrion="">
                    <h2>Login Digital Retro</h2>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" required>
                        <label for="">Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="pass" required>
                        <label for="">Password</label>
                    </div>
                    <div class="forget">
                        <label for=""><input type="checkbox" value="">Recordarme <a href="recuperarC.php">¿Olvido su contraseña? </a></label>
                    </div>
                    <button type="submit" name="loginC">Iniciar Sesión</button>
                    <div class="register">
                        <p>No tengo una cuenta <a href="register.php">Registrarse</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>