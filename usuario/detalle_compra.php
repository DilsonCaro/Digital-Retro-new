<?php
require 'servicios/config.php';
require 'servicios/conexion.php';
require 'clases/clienteFunciones.php';

$token_session = $_SESSION['token'];
$orden = $_GET['order'] ?? null;
$token = $_GET['token'] ?? null;

if ($orden == null || $token == null || $token != $token_session) {
    header("Location: compras.php");
    exit;
}
$db = new Database();
$con = $db->conectar();

$sqlCompra = $con->prepare("SELECT id, id_transaccion, fecha, total, medio_pago FROM compra WHERE id_transaccion = ? LIMIT 1");
$sqlCompra->execute([$orden]);
$rowCompra = $sqlCompra->fetch(PDO::FETCH_ASSOC);
$id_compra = $rowCompra['id'];

$fecha = new DateTime($rowCompra['fecha']);
$fecha = $fecha->format('d/m/Y H:i:s');

$sqlDetalle = $con->prepare("SELECT id, nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
$sqlDetalle->execute([$id_compra]);
$dolares = 800;

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
    <link rel="stylesheet" href="css/detalle_compra.css">
</head>

<body>
    <?php include_once 'menu.php'; ?>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="card mb-3 border-primary">
                        <div class="card-header">
                            <strong>Detalle de la compra</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Fecha: </strong>
                                <?php echo $fecha; ?>
                            </p>
                            <p><strong>Orden: </strong>
                                <?php echo $rowCompra['id_transaccion']; ?>
                            </p>
                            <p><strong>Total: </strong>
                                <?php echo MONEDA2 . ' ' . number_format($rowCompra['total'], 2, '.', ','); ?>
                            </p>
                            <p><strong>Total en CLP: </strong>
                                <?php echo MONEDA . ' ' . number_format($rowCompra['total']*$dolares, 0, ',', '.'); ?>
                            </p>
                            <p><strong>Medio de Pago:</strong>
                                <?php echo $rowCompra['medio_pago']; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8">
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
                                <?php while ($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)) {
                                    $precio = $row['precio'];
                                    $cantidad = $row['cantidad'];
                                    $subtotal = $precio * $cantidad;
                                    ?>
                                <tr>
                                    <td><?php echo $row['nombre'];?></td>
                                    <td><?php echo  MONEDA . ' ' . number_format($precio, 0, ',', '.');?></td>
                                    <td><?php echo $cantidad;?></td>
                                    <td><?php echo MONEDA . ' ' . number_format($subtotal, 0, ',', '.');?></td>
                                </tr>


                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
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