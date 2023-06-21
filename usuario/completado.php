<?php
require 'servicios/config.php';
require 'servicios/conexion.php';
$db = new Database();
$con = $db->conectar();

$id_transaccion = isset($_GET['key']) ? $_GET['key'] : '0';

$error = '';

if ($id_transaccion == '') {
  $error = 'Error al procesar la petición';
} else {
  $sql = $con->prepare("SELECT count(id) FROM compra WHERE id_transaccion=? AND status=?");
  $sql->execute([$id_transaccion, 'COMPLETED']);
  if ($sql->fetchColumn() > 0) {
    $sql = $con->prepare("SELECT id, fecha, email, total FROM compra WHERE id_transaccion=? AND status=? LIMIT 1");
    $sql->execute([$id_transaccion, 'COMPLETED']);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    $idCompra = $row['id'];
    $total = $row['total'];
    $fecha = $row['fecha'];

    $sqlDet = $con->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
    $sqlDet->execute([$idCompra]); // Corregido: Se debe usar $sqlDet en lugar de $sql
  } else {
    $error = 'Error al comprobar la compra';
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
  <link rel="stylesheet" href="css/completado.css">
  <link rel="stylesheet" href="css/pago.css">

</head>

<body>
  <?php include_once 'menu.php'; ?>
  <main>
    <div class="container">
      <?php
      $dolares = 800;
      $CLP = $total * $dolares; ?>
      <?php if (strlen($error) > 0) { ?>
        <div class="row">
          <div class="col">
            <h3>
              <?php echo $error; ?>
            </h3>
          </div>
        </div>
      <?php } else { ?>
        <div class="row">
          <div class="col">
            <b>Folio de la compra:</b>
            <?php echo $id_transaccion; ?><br>
            <b>Fecha de la compra:</b>
            <?php echo $fecha; ?><br>
            <b>Precio total en USD:</b>
            <?php echo MONEDA2 . number_format($total, 2, '.', ','); ?><br>
            <b>Precio total en CLP:</b>
            <?php echo MONEDA . number_format($CLP, 0, ',', '.'); ?><br>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <table class="table">
              <thead>
                <tr>
                  <th>Cantidad</th>
                  <th>Producto</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)) {
                  $importe = $row_det['precio'] * $row_det['cantidad'];
                  ?>
                  <tr>
                    <td>
                      <?php echo $row_det['cantidad']; ?>
                    </td>
                    <td>
                      <?php echo $row_det['nombre']; ?>
                    </td>
                    <td>
                      <?php echo MONEDA . $importe; ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php } ?>
    </div>
  </main>
</body>
<div class="conce">
  <footer class="bg-dark text-center text-white">
    <!-- Grid container -->
    <div class="container p-4 pb-0">
      <!-- Section: Social media -->
      <section class="mb-4">
        <!-- Facebook -->
        <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-facebook-f"></i></a>

        <!-- Twitter -->
        <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-twitter"></i></a>

        <!-- Google -->
        <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-google"></i></a>

        <!-- Instagram -->
        <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-instagram"></i></a>

        <!-- Linkedin -->
        <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-linkedin-in"></i></a>

        <!-- Github -->
        <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-github"></i></a>
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