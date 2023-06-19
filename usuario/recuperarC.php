<?php
if (isset($_REQUEST['recuperar'])) {
    $email = $_REQUEST['email'] ?? '';
?>
    <?php
    echo '<div class="alert alert-primary" role="alert">
        se realizo el envio de recuperacion de contraseña a ' . $email . '
    </div>';

    ?>
<?php
}

?>
<!doctype html>
<html lang="en">

<head>
    <link rel="icon" href="images/logo.jpg">
    <link rel="stylesheet" href="css/recuperarC.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña</title>
</head>

<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form acrion="">
                    <h2>Recuperar Contraseña</h2>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" required>
                        <label for="">Email</label>
                    </div>
                    <div class="button">
                        <button type="submit" name="recuperar">Enviar</button>
                        <!-- <button href="loginC.php">Regresar</button> -->
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>

</html>