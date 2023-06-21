<?php
require 'servicios/config.php';
require 'servicios/conexion.php';


$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre_videojuego, precio, imagen FROM productos WHERE estado=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

// session_destroy();
// print_r($_SESSION);
?>

<?php

if (isset($_POST['enviar'])) {
  $busqueda = $_POST['campo'];
  $consulta = $con->prepare("SELECT * FROM productos WHERE nombre_videojuego LIKE ?");
  $termino = "%$busqueda%";
  $consulta->execute(array($termino));
  $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
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
  <link rel="stylesheet" href="css/index.css">
</head>

<body>
  <?php include_once 'menu.php';?>
  <main>
    <section>
      <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
          <?php foreach ($resultado as $row) { ?>
            <div class="col">
              <div class="card shadow-sm">
                <?php
                $id = $row['id'];
                $imagen = $row['imagen'];

                if (!file_exists($imagen)) {
                  $imagen = "images/no.photo.jpg";
                }
                ?>
                <img src="data:image/jpg;base64,<?php echo base64_encode($row['imagen']) ?>">
                <div class="card-body">
                  <h5 class="card-title">
                    <?php echo $row['nombre_videojuego'] ?>
                  </h5>
                  <p class="card-text">CLP$
                    <?php echo number_format($row['precio'], 0, ',', '.'); ?>
                  </p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      <a href="detalles.php?id=<?php echo $row['id']; ?>&token=<?php echo
                           hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>"
                        class="btn btn-primary">Detalles</a>
                    </div>
                    <button class="btn btn-outline" type="button" onclick="addProducto(<?php echo $row['id']; ?>, 
                  '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')">Agregar</button>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
      </div>
    </section>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"></script>

  <script>
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
        })
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