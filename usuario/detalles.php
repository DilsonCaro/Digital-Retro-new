<?php
require 'servicios/config.php';
require 'servicios/conexion.php';

$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'Error al procesar la petición';
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
    if ($token == $token_tmp) {

        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND estado=1");
        $sql->execute([$id]);
        if ($sql->fetchColumn() > 0) {
            $sql = $con->prepare("SELECT nombre_videojuego, descripcion, precio, plataforma, clasificacion, fecha_lanzamiento, imagen FROM productos WHERE id=? AND estado=1 
      LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre_videojuego'];
            $descripcion = $row['descripcion'];
            $precio = $row['precio'];
            $plataforma = $row['plataforma'];
            $clasificacion = $row['clasificacion'];
            $fecha_lanzamiento = $row['fecha_lanzamiento'];
            $imagen = $row['imagen'];
        }
    } else {
        echo 'Error al procesar la petición';
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="images/logo.jpg">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Retro</title>
    <script src="https://kit.fontawesome.com/827cf0b5dd.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/detalle.css">

</head>

<body>
   <?php include_once 'menu.php';?>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-md-1">
                    <img class="image" src="data:image/jpg;base64,<?php echo base64_encode($row['imagen']) ?>">
                </div>
                <div class="col-md-6 order-md-3">
                    <h2>
                        <?php echo $nombre; ?>
                    </h2>
                    <h2>
                        <?php echo MONEDA . number_format($precio, 0, ',', '.');
                        ; ?>
                    </h2>
                    <p class="lead">
                        DESCRIPCION:
                        <?php echo $descripcion; ?>
                    </p>
                    <p class="detail-pla">
                        PLATAFORMA:
                        <?php echo $plataforma; ?>
                    </p>
                    <p class="detail-cla">
                        CLASIFICACION:
                        <?php echo $clasificacion; ?>
                    </p>
                    <p class="detail-cla">
                        FECHA LANZAMIENTO:
                        <?php echo $fecha_lanzamiento; ?>
                    </p>
                    <div class="col-3 my-3">
                        CANTIDAD<input class="form-control" id="cantidad" name="cantidad" type="number" min="1" max="10"
                            value="1">
                    </div>
                    <div class="d-grid gap-3 col-10 mx-auto">
                        <button class="btn btn-primary" type="button" onclick="comprarAhora(<?php echo $id; ?>, cantidad.value, '<?php echo $token_tmp ?>')">Comprar Ahora</button>

                        <button class="btn btn-outline" type="button"
                            onclick="addProducto(<?php echo $id; ?>, cantidad.value, '<?php echo $token_tmp ?>')">Agregar
                            al carrito</button>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <script>

        function addProducto(id, cantidad, token) {
            var url = 'clases/carrito.php'
            var formData = new FormData();
            formData.append('id', id)
            formData.append('cantidad', cantidad)
            formData.append('token', token)

            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let elemento = document.getElementById("num_cart")
                        elemento.innerHTML = data.numero
                    }
                })
        }
        
        function comprarAhora() {
        var cantidad = document.getElementById('cantidad').value;   
        if (cantidad > 0) {          
            var urlPago = 'pago.php';           
            window.location.href = urlPago + '?cantidad=' + cantidad;
            
        } else {
           
            alert('Error en la peticion');
        }
    }
    </script>
</body>
<div class="conce">
    <footer class="bg-dark text-center text-white">
        <!-- Grid container -->
        <div class="container p-4 pb-0">
            <!-- Section: Social media -->
            <section class="mb-4">
                <!-- Facebook -->
                <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                        class="fab fa-facebook-f"></i></a>

                <!-- Twitter -->
                <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                        class="fab fa-twitter"></i></a>

                <!-- Google -->
                <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                        class="fab fa-google"></i></a>

                <!-- Instagram -->
                <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                        class="fab fa-instagram"></i></a>

                <!-- Linkedin -->
                <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                        class="fab fa-linkedin-in"></i></a>

                <!-- Github -->
                <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i
                        class="fab fa-github"></i></a>
            </section>
            <!-- Section: Social media -->
        </div>
        <!-- Grid container -->

        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: black;">
            © 2023 Copyright:
            <a class="text-white" href="#">Digital Retro</a>
        </div>
        <!-- Copyright -->
    </footer>
</div>

</html>