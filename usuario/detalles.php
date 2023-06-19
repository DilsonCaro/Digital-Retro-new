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
    <header>
        <div class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a href="index.php" class="navbar-brand">
                    <strong>Digital Retro</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
                    aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarHeader">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link active">Catalogo</a>
                        </li>
                        <div class="container-fluid col-lg-12">
                            <form class="d-flex" role="search">
                                <input class="form-control col-lg-12 me-2" type="search" placeholder="Search"
                                    aria-label="Search">
                                <button class="btn btn-success" type="submit"> <svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16" fill="currentColor" class="bi bi-search"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                    </svg></button>
                            </form>
                        </div>
                    </ul>
                    <div>
                        <a href="checkout.php" class="btn btn-primary">Carrito <span id="num_cart"
                                class="badge bg-secondary">
                                <?php echo $num_cart; ?>
                            </span>
                        </a>
                        <a href="LoginC.php" class="btn btn-primary" name="login"> <i class="bi bi-person"></i> <svg
                                xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-person" viewBox="0 0 16 16">
                                <path
                                    d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                            </svg>Login</a>
                        <a href="register.php" class="btn btn-primary" name="registrarse"> <i
                                class="bi bi-person-add"></i> <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                height="16" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
                                <path
                                    d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0Zm-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" />
                                <path
                                    d="M8.256 14a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1h5.256Z" />
                            </svg>Registrate</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
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