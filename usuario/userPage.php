<?php
include_once "../admin/bdecommerce.php";
$con = mysqli_connect($host, $user, $pass, $db);
$id_cliente = mysqli_real_escape_string($con, $_REQUEST['id_cliente'] ?? '');
$query = "SELECT id_cliente, nombre, email, usuario, telefono, puntos_fidelidad from cuenta_cliente where id_cliente= '" . $id_cliente . "';";
$res = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($res);
?>

<!doctype html>
<html lang="en">

<head>
    <link rel="icon" href="images/logo.jpg">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="userPage.css">
    <title>Userpage</title>
</head>

<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="" method="POST" class="formulario">
                    <h2 class="titulo">Userpage</h2>
                    <div class="inputbox">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <input type="text" name="nombre" value="<?php echo $row['nombre'] ?>">
                        <label for="">Nombre</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" value="<?php echo $row['email'] ?>">
                        <label for="">Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="usuario" value="<?php echo $row['usuario'] ?>">
                        <label for="">Nombre usuario</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="call-outline"></ion-icon>
                        <input type="number" name="phone" value="<?php echo $row['telefono'] ?>">
                        <label for="">Telefono</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="clipboard-outline"></ion-icon>
                        <input type="number" name="puntosF" value="<?php echo $row['puntos_fidelidad'] ?>">
                        <label for="">Puntos de fidelidad</label>
                    </div>
                    <div class="forget">
                        <button href="index.php">Volver</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>