<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Ventas</h1>
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
              <table id="tablaVentas" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Id Compra</th>
                    <th>Id Producto</th>
                    <th>Nombre</th>
                    <th>Precio CLP</th>
                    <th>Precio USD</th>
                    <th>Cantidad</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  include_once "bdecommerce.php";
                  $con=mysqli_connect($host, $user, $pass, $db);
                  $query = "SELECT id, id_compra, id_producto, nombre, precio, cantidad from detalle_compra ; ";
                  $res = mysqli_query($con, $query);
                  while ($row = mysqli_fetch_assoc($res)) {
                  ?>
                  <tr>
                    <td><?php echo $row['id'];?></td>
                    <td><?php echo $row['id_compra'];?></td>
                    <td><?php echo $row['id_producto'];?></td>
                    <td><?php echo $row['nombre'];?></td>
                    <td><?php echo $row['precio'];?></td>
                    <td><?php echo round($row['precio']/800);?></td>
                    <td><?php echo $row['cantidad'];?></td>
                  </tr>
                  <?php 
                  }?>
                </tbody>
              </table>
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