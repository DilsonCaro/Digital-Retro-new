<?php
require 'servicios/config.php';
require 'servicios/conexion.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {

    $email = trim($_POST['email']);


    if (esNulo([$email])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!esEmail($email)) {

        $errors[] = "El email no es valido";
    }

    if(count($errors) == 0){
    //     if(emailExiste($email, $con)){

    //         $sql = $con->prepare("SELECT cuentas.id, clientes.nombres FROM cuentas
    //         INNER JOIN clientes ON cuentas.id_cliente = clientes.id WHERE clientes.email LIKE ? LIMIT 1");
    //         $sql->execute([$email]);
    //         $row = $sql->fetch(PDO::FETCH_ASSOC);
    //         $user_id = $row['id'];
    //         $nombres = $row['nombres'];

    //         $token = solicitaPassword($user_id, $con);

    //         if($token !== null){
    //             require 'clases/mailer.php';
    //             $mailer = new Mailer();
    //             $url = SITE_URL . 'reset_password.php?id='.$user_id .'&token='.$token;

    //             $asunto ="Recuperar password - Digital Retro";
    //             $cuerpo = "Estimado $nombres: <br> Si haz solicitado el cambio de contraseña da click en el siguiente enlace 
    //             <a href ='$url'>$url</a>";
    //             $cuerpo.= "<br>Si no hiciste esta solicitud puedes ignorar este correo.";

    //             if($mailer->enviarEmail($email,$asunto,$cuerpo)){

    //                 echo "<p><b>Correo enviado</b></p>";
    //                 echo "<p>Hemos enviado un correo electronico a la direccion $email para restablecer la contraseña</p>";
    //                 echo "<p>Si no encuentras el correo, puedes revisar en la seccion de spam</p>";
    //                 exit;
    //             }
    //         }
    //     } else{
    //         $errors[] = "No existe una cuenta asociada a este correo";
    //     }
    // }

    if (emailExiste($email, $con)) {
        $sql = $con->prepare("SELECT cuentas.id, clientes.nombres FROM cuentas
                INNER JOIN clientes ON cuentas.id_cliente = clientes.id WHERE clientes.email LIKE ? LIMIT 1");
        $sql->execute([$email]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);
    
        if ($row) {
            $user_id = $row['id'];
            $nombres = $row['nombres'];
    
            $token = solicitaPassword($user_id, $con);
    
            if ($token !== null) {
                require 'clases/mailer.php';
                $mailer = new Mailer();
                $url = SITE_URL . 'reset_password.php?id=' . $user_id . '&token=' . $token;
    
                $asunto = "Recuperar password - Digital Retro";
                $cuerpo = "Estimado $nombres: <br> Si has solicitado el cambio de contraseña da click en el siguiente enlace 
                <a href ='$url'>$url</a>";
                $cuerpo .= "<br>Si no hiciste esta solicitud puedes ignorar este correo.";
    
                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    echo "<p><b>Correo enviado</b></p>";
                    echo "<p>Hemos enviado un correo electrónico a la dirección $email para restablecer la contraseña</p>";
                    echo "<p>Si no encuentras el correo, puedes revisar en la sección de spam</p>";
                    exit;
                }
            }
        } else {
            $errors[] = "No existe una cuenta asociada a este correo";
        }
    }
    
    }

}
?>
<!doctype html>
<html lang="en">

<head>
    <link rel="icon" href="images/logo.jpg">
    <link rel="stylesheet" href="css/recuperarC.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña</title>
</head>

<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="recuperarC.php" method="POST" autocomplete="off">
                    <?php mostrarMensajes($errors); ?>
                    <h2>Recuperar Contraseña</h2>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" id="email" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="button">
                        <button type="submit" name="recuperar">continuar</button>
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