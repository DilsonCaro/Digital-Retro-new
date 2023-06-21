<?php
require 'servicios/config.php';
require 'servicios/conexion.php';
require 'vendor/autoload.php';

MercadoPago\SDK::setAccessToken(TOKEN_MP);

$preference = new MercadoPago\Preference();
$productos_mp = array();

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
} else {
    header("Location: index.php");
    exit;
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
    <link rel="stylesheet" href="css/pago.css">
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY; ?>">
        // Replace YOUR_CLIENT_ID with your sandbox client ID
    </script>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>

<body>
    <?php include_once 'menu.php';?>
    <main>
        <div class="container">

            <div class="row">
                <div class="col-6 detalle">
                    <h4>Detalles de pago</h4>
                    <div class="row">
                        <div class="col-12">
                            <div id="paypal-button-container"></div>
                        </div>
                    </div>

                    <div class="row">
                        <img src="images/icon-256x256.png">
                        <div class="col-12">
                            <div class="checkout-btn btn-lg"></div>
                        </div>
                    </div>
                </div>
                <div class="col-6 estadistica">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
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
                                        $dolares = 800;
                                        $dolaresU = round($total / $dolares, 0);

                                        $item = new MercadoPago\Item();
                                        $item->id = $_id;
                                        $item->title = $nombre;
                                        $item->quantity = $cantidad;
                                        $item->unit_price = $precio;
                                        $item->currency_id = "CLP";

                                        array_push($productos_mp, $item);
                                        unset($item);
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $nombre ?>
                                            </td>
                                            <td>
                                                <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 0, '.', ','); ?></div>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <tr>
                                        <td colspan="2">
                                            <p class="h3 text-end" id="total">
                                            <?php echo MONEDA . number_format($total, 0, ',', '.'); ?>
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php
    $preference->items = $productos_mp;
    $preference->back_urls = array(
        "success" => "http://localhost/DigitalRetroNew/usuario/captura.php",
        "failure" => "http://localhost/DigitalRetroNew/usuario/fallo.php"
    );
    $preference->auto_return = "approved";
    $preference->binary_mode = true;

    $preference->save();

    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

    <script>
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
                label: 'pay'
            },
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: <?php echo $dolaresU; ?>
                        }
                    }]
                });
            },

            onApprove: function (data, actions) {
                let URL = 'clases/captura.php';

                return actions.order.capture().then(function (detalles) {
                    console.log(detalles);
                    let url = 'clases/captura.php';
                    return fetch(url, {
                        method: 'POST',
                        headers: {
                            'content-type': 'application/json'
                        },
                        body: JSON.stringify({
                            detalles: detalles
                        })
                    }).then(function (response) {
                        window.location.href = "completado.php?key=" + detalles['id']; //$datos['detalles']['id']
                    });
                });
            },
            onCancel: function (data) {
                alert("pago cancelado");
                console.log(data);
            }
        }).render('#paypal-button-container');

        const mp = new MercadoPago('<?php echo TOKEN_TEST; ?>', {
            locale: 'es-CL'
        });

        mp.checkout({
            preference: {
                id: '<?php echo $preference->id; ?>'
            },
            render: {
                container: '.checkout-btn',
                label: 'Pagar con Mercado Pago',
                size: 'large'
            }
        })
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