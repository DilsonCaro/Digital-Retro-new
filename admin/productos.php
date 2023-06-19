<?php
include_once "bdecommerce.php";
$con = mysqli_connect($host, $user, $pass, $db);
if (isset($_REQUEST['idBorrarP'])) {
  $id = mysqli_real_escape_string($con, $_REQUEST['idBorrarP'] ?? '');
  $query = "DELETE from productos where id='" . $id . "';";
  $res = mysqli_query($con, $query);
  if ($res) {
    echo '<meta http-equiv="refresh" content="0; url=panel.php?modulo=productos&mensaje2=Producto borrado exitosamente" />';
?>

  <?php
  } else {
  ?>
    <div class="alert alert-danger float-right" role="alert">
      Error al borrar Productos <?php echo mysqli_error($con); ?>
    </div>
<?php
  }
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Productos</h1>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tablaProductos" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>id</th>
                    <th>Nombre_videojuego</th>
                    <th>Precio</th>
                    <th>Plataforma</th>
                    <th>Key_videojuego</th>
                    <th>Stock</th>
                    <th>Descripcion</th>
                    <th>Categoria</th>
                    <th>Fecha_lanzamiento</th>
                    <th>Clasificacion</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // include_once "bdecommerce.php";
                  // $con=mysqli_connect($host, $user, $pass, $db);
                  $query = "SELECT id, nombre_videojuego, precio, plataforma, key_videojuego, stock, descripcion, categoria, fecha_lanzamiento, clasificacion, imagen from productos; ";
                  $res = mysqli_query($con, $query);
                  while ($row = mysqli_fetch_assoc($res)) {
                  ?>
                    <tr>
                      <td><?php echo $row['id'] ?></td>
                      <td><?php echo $row['nombre_videojuego'] ?></td>
                      <td><?php echo $row['precio'] ?></td>
                      <td><?php echo $row['plataforma'] ?></td>
                      <td><?php echo $row['key_videojuego'] ?></td>
                      <td><?php echo $row['stock'] ?></td>
                      <td><?php echo $row['descripcion'] ?></td>
                      <td><?php echo $row['categoria'] ?></td>
                      <td><?php echo $row['fecha_lanzamiento'] ?></td>
                      <td><?php echo $row['clasificacion'] ?></td>
                      <td><img height='125px' width='125px' src="data:image/jpg;base64,<?php echo base64_encode($row['imagen'])?>"></td>
                      <td scope="row">
                        <a href="panel.php?modulo=editarProductos&id=<?php echo $row['id'] ?>" style="margin-right: 5px;"> <i class="btn btn-warning">Editar producto</i></a>
                        <a href="panel.php?modulo=productos&idBorrarP=<?php echo $row['id'] ?>" class="text-danger borrarP"> <i class="btn btn-danger">Borrar producto</i></a>
                      </td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
            <div class="container col-sm-2">
              <a href="panel.php?modulo=agregarProductos" class="btn btn-primary">Agregar Productos</a>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>