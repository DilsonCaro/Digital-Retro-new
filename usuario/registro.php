<?php
require 'servicios/config.php';
require 'servicios/conexion.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {

    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $dni = trim($_POST['dni']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$nombres, $apellidos, $email, $telefono, $dni, $usuario, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!esEmail($email)) {

        $errors[] = "El email no es valido";
    }

    if (!validarPassword($password, $repassword)) {

        $errors[] = "Las contraseñas ingresadas no coinciden";
    }

    if (usuarioExiste($usuario, $con)) {
        $errors[] = "El nombre de usuario $usuario ya existe";
    }

    if (emailExiste($email, $con)) {
        $errors[] = "El correo electronico $email ya esta en uso";
    }

    if (count($errors) == 0) {

        $id = registraCliente([$nombres, $apellidos, $email, $telefono, $dni], $con);

        if ($id > 0) {

            require 'clases/mailer.php';
            $mailer = new Mailer();
            $token = generarToken();

            $pass_hash = password_hash($password, PASSWORD_DEFAULT);

            $idUsuario = registraUsuario([$usuario, $pass_hash, $token, $id], $con);

            if ($idUsuario > 0) {

                $url = SITE_URL . 'activa_cliente.php?id='.$idUsuario .'&token='.$token;
                $asunto ="Activar cuenta - Digital Retro";
                $cuerpo = "Estimado $nombres: <br> Para continuar con el proceso de registro es necesario que haga click en el
                siguiente url <a href ='$url'>Activar cuenta</a>";
               
                if($mailer->enviarEmail($email,$asunto,$cuerpo)){

                    echo "Para terminar el proceso de registro siga las instrucciones que le hemos enviado al correo electronico
                    $email";

                    exit;
                }

            }else{ 

                $errors[] = "Error al registrar usuario";
            }
        } else {
            $errors[] = "Error al registrar cliente";
        }

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
                <form action="registro.php" method="POST" class="formulario" autocomplete="off">
                    <h2 class="titulo">Registro Digital Retro</h2>
                    <div class="inputbox">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <input type="text" name="nombres" id="nombres" required>
                        <label for="nombres">Nombres</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="person-circle-outline"></ion-icon>
                        <input type="text" name="apellidos" id="apellidos" required>
                        <label for="apellidos">Apellidos</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" id="email" required>
                        <span id="validaEmail" class="texto-span"></span>
                        <label for="email">Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="usuario" id="usuario" required>
                        <span id="validaUsuario" class="texto-span"></span>
                        <label for="usuario">Nombre usuario</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="dni" id="dni" required>
                        <label for="dni">DNI</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" id="password" required>
                        <label for="password">Contraseña</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="repassword" id="repassword" required>
                        <label for="repassword">Repetir contraseña</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="call-outline"></ion-icon>
                        <input type="number" name="telefono" id="telefono" required>
                        <label for="tel">telefono</label>
                    </div>
                    <div class="forget">
                        <button type="submit" value="Registrar" name="registro">Registrar</button>
                        <!-- <button href="index.php">Volver</button> -->
                         <!-- <button href="index.php">Volver</button> -->
                    </div>

                    <div class="alert">
                        <?php mostrarMensajes($errors); ?>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script>
        let txtUsuario = document.getElementById('usuario');
        txtUsuario.addEventListener("blur", function () {
            existeUsuario(txtUsuario.value);
        }, false)

        let txtEmail = document.getElementById('email');
        txtEmail.addEventListener("blur", function () {
            existeEmail(txtEmail.value);
        }, false);
        
        function existeEmail(email) {
            let url = "clases/clienteAjax.php";
            let formData = new FormData(); 
            formData.append("action", "existeEmail");
            formData.append("email", email);

            fetch(url, { 
                method: 'POST',
                body: formData
            }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        document.getElementById('email').value = '';
                        document.getElementById('validaEmail').innerHTML = 'Este correo electronico ya esta en uso';
                    } else {
                        document.getElementById('validaEmail').innerHTML = '';
                    }
                });
        }

        function existeUsuario(usuario) {
            let url = "clases/clienteAjax.php";
            let formData = new FormData(); 
            formData.append("action", "existeUsuario");
            formData.append("usuario", usuario);

            fetch(url, { 
                method: 'POST',
                body: formData
            }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        document.getElementById('usuario').value = '';
                        document.getElementById('validaUsuario').innerHTML = 'Este nombre de usuario ya esta en uso';
                    } else {
                        document.getElementById('validaUsuario').innerHTML = '';
                    }
                });
        }

    </script>
</body>

</html>