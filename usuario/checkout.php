<?php
require 'servicios/config.php';
require 'servicios/conexion.php';
$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

// print_r($_SESSION);

$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {
        $sql = $con->prepare("SELECT id, nombre_videojuego, precio, $cantidad AS cantidad FROM productos WHERE id=? AND estado=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
}


// session_destroy();

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
    <link rel="stylesheet" href="css/checkout.css">
</head>

<body>
    <?php include_once 'menu.php';?>
    <main>
        <div class="container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($lista_carrito == null) {
                            echo '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr';
                        } else {
                            $total = 0;
                            foreach ($lista_carrito as $producto) {
                                $_id = $producto['id'];
                                $nombre = $producto['nombre_videojuego'];
                                $precio = $producto['precio'];
                                $cantidad = $producto['cantidad'];
                                $subtotal = $cantidad * $precio;
                                $total += $subtotal;
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $nombre ?>
                                    </td>
                                    <td>
                                        <?php echo MONEDA . number_format($precio, 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <input type="number" min="1" max="10" value="<?php echo $cantidad ?>" size="5"
                                            id="cantidad_<?php echo $_id; ?>"
                                            onchange="actualizaCantidad(this.value, <?php echo $_id; ?> )">
                                    </td>
                                    <td>
                                        <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 0, ',', '.'); ?></div>
                                    </td>
                                    <td><a href="#" id="eliminar" class="btn btn-warning btn-sm"
                                            data-bs-id="<?php echo
                                                $_id; ?>" data-bs-toggle="modal"
                                            data-bs-target="#eliminaModal">Eliminar</a>
                                    </td>
                                </tr>
                            <?php } ?>

                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2">
                                    <p class="h3" id="total">
                                    <?php echo MONEDA . number_format($total, 0, ',', '.'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                    <?php } ?>
                </table>
            </div>
            <?php if ($lista_carrito != null) { ?>
                <div class="row">
                    <div class="col-md-5 offset-md-6 d-grid gap-2">
                        <?php if(isset($_SESSION['user_id'])){?>
                            <a href="pago.php" class="btn btn-primary btn-lg">Realizar pago</a>
                        <?php } else {?>
                            <a href="login.php?pago" class="btn btn-primary btn-lg">Realizar pago</a>
                        <?php }?>
                    </div>
                </div>
            <?php } ?>
        </div>

    </main>
    <!-- Modal -->
    <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminaModalLabel">Alerta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Â¿Desea elminar el producto de la lista?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

    <script>
        let eliminaModal = document.getElementById('eliminaModal')
        eliminaModal.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')
            let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
            buttonElimina.value = id
        })

        function actualizaCantidad(cantidad, id) {
            var url = 'clases/actualiza_carrito.php'
            var formData = new FormData();
            formData.append('action', 'agregar')
            formData.append('id', id)
            formData.append('cantidad', cantidad)

            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
                .then(data => {
                    if (data.ok) {

                        let divsubtotal = document.getElementById('subtotal_' + id)
                        divsubtotal.innerHTML = data.sub

                        let total = 0
                        let list = document.getElementsByName('subtotal[]')

                        for (let i = 0; i < list.length; i++) {
                            total += parseFloat(list[i].innerHTML.replace(/[CLP$.]/g, ''))
                        }

                        total = new Intl.NumberFormat('es-CL', {
                            minimumFractionDigits: 0
                        }).format(total)
                        document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total
                    }
                })
        }

        function eliminar() {

            let botonElimina = document.getElementById('btn-elimina')
            let id = botonElimina.value
            var url = 'clases/actualiza_carrito.php'
            var formData = new FormData();
            formData.append('action', 'eliminar')
            formData.append('id', id)

            fetch(url, {
                method: 'POST',
                body: formData,
                mode: 'cors'
            }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        location.reload()

                    }
                })
        }
    </script>
    <!-- <script>
        function addProducto(id, token) {
            var url = 'clases/carrito.php'
            var formData = new FormData();
            formData.append('id', id)
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
            })  }
    </script> -->
</body>
<?php if ($lista_carrito != null) { ?>
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
<?php } ?>

</html>