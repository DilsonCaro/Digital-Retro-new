<?php
error_reporting(1);
include_once "bdecommerce.php";
$con = mysqli_connect($host, $user, $pass, $db);
if (isset($_REQUEST['guardarP'])) {

    $id = mysqli_real_escape_string($con, $_REQUEST['id'] ?? '');
    $nombre_videojuego = mysqli_real_escape_string($con, $_REQUEST['nombreV'] ?? '');
    $precio = mysqli_real_escape_string($con, $_REQUEST['precio'] ?? '');
    $plataforma = mysqli_real_escape_string($con, $_REQUEST['plataforma'] ?? '');
    $key_videojuego = mysqli_real_escape_string($con, $_REQUEST['keyV'] ?? '');
    $stock = mysqli_real_escape_string($con, $_REQUEST['stock'] ?? '');
    $descripcion = mysqli_real_escape_string($con, $_REQUEST['descrip'] ?? '');
    $categoria = mysqli_real_escape_string($con, $_REQUEST['categ'] ?? '');
    $fecha_lanzamiento = mysqli_real_escape_string($con, $_REQUEST['fechaV'] ?? '');
    $clasificacion = mysqli_real_escape_string($con, $_REQUEST['clasificacion'] ?? '');
    $estado = mysqli_real_escape_string($con, $_REQUEST['estado'] ?? '');

    if (!empty($_FILES['imagenE']['tmp_name'])) {
        $imagen = addslashes(file_get_contents($_FILES['imagenE']['tmp_name']));

        $query = "UPDATE productos SET
            nombre_videojuego = '$nombre_videojuego',
            precio = '$precio',
            plataforma = '$plataforma',
            key_videojuego = '$key_videojuego',
            stock = '$stock',
            descripcion = '$descripcion',
            categoria = '$categoria',
            fecha_lanzamiento = '$fecha_lanzamiento',
            clasificacion = '$clasificacion',
            estado = '$estado',
            imagen = '$imagen'
            WHERE id = '$id';
        ";
    } else {
        $query = "UPDATE productos SET
        nombre_videojuego='  $nombre_videojuego  ', precio='  $precio  ', plataforma= '$plataforma', key_videojuego='  $key_videojuego  ', stock='  $stock  ', descripcion='  $descripcion  ', categoria='  $categoria  ',
        fecha_lanzamiento='  $fecha_lanzamiento  ', clasificacion='  $clasificacion  ', estado='  $estado  '
        where id='  $id ' ;
    ";
    }
    $res = mysqli_query($con, $query);
    if ($res) {
        echo '<meta http-equiv="refresh" content="0; url=panel.php?modulo=productos&mensaje1=Producto ' . $nombre_videojuego . ' editado exitosamente" />';
    } else {
?>
        <div class="alert alert-danger" role="alert">
            Error al editar producto <?php echo mysqli_error($con); ?>
        </div>
<?php

    }
}
$id = mysqli_real_escape_string($con, $_REQUEST['id'] ?? '');
$query = "SELECT id, nombre_videojuego, precio, plataforma, key_videojuego, stock, descripcion, categoria, fecha_lanzamiento, clasificacion, estado, imagen from productos where id='" . $id . "'; ";
mysqli_query($con, $query);
$res = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($res);
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Editar Productos</title>
</head>

<body>
    <h1 class="text-center"> Editar Productos</h1>
    <br>
    <div class="container">
        <form method="POST" enctype='multipart/form-data'>
            <div class="form-group mb-3">
                <label class="form-label">Nombre Videojuego</label>
                <input type="text" class="form-control" name="nombreV" value="<?php echo $row['nombre_videojuego'] ?>" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Precio</label>
                <input type="number" class="form-control" name="precio" value="<?php echo $row['precio'] ?>" required="required">
            </div>
            <div>
                <label class="form-group mb-3">Plataforma</label>
                <!-- <input type="text" class="combobox" name="plataforma" value="" required="required"> -->
                <div>
                    <select name="plataforma" required="required">
                        <option value="No especifico">Seleccionar</option>
                        <option value="PS5">PS5</option>
                        <option value="PS4">PS4</option>
                        <option value="Steam">Steam</option>
                        <option value="XBOX X/S">XBOX X/S</option>
                        <option value="Epic Games">Epic Games</option>
                        <option value="EA Play">EA Play</option>
                    </select>
                </div>
            </div>
            <br><br>
            <div class="form-group mb-3">
                <label class="form-label">Key Videojuego</label>
                <input type="text" class="form-control" name="keyV" value="<?php echo $row['key_videojuego'] ?>" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Stock</label>
                <input type="number" class="form-control" name="stock" value="<?php echo $row['stock'] ?>" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Descripcion</label>
                <input type="text" class="form-control" name="descrip" value="<?php echo $row['descripcion'] ?>" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Categoria</label>
                <input type="text" class="form-control" name="categ" value="<?php echo $row['categoria'] ?>" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Fecha Lanzamiento</label>
                <input type="date" class="form-control" name="fechaV" value="<?php echo $row['fecha_lanzamiento'] ?>" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Clasificacion</label>
                <input type="text" class="form-control" name="clasificacion" value="<?php echo $row['clasificacion'] ?>" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Estado</label>
                <input type="number" class="form-control" name="estado" value="<?php echo $row['estado'] ?>" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Imagen</label>
                <td><img height="125px" width="125px" src="data:image/jpg;base64,<?php echo base64_encode($row['imagen']) ?>"></td>
                <input type="file" class="form-control" name="imagenE" id="imagenE">
            </div>
            <div class="text-center">
                <button type="submit" name="guardarP" class="btn btn-primary">Guardar cambios</button>
                <a href="panel.php?modulo=productos" class="btn btn-dark">Regresar</a>
            </div>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>