<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Transacciones</h1>
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
              <table id="tablaTransaccion" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Id transacion</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Email</th>
                    <th>ID Cliente</th>
                    <th>Total USD</th>
                    <th>Total CLP</th>
                    <th>Medio de Pago</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  include_once "bdecommerce.php";
                  $con=mysqli_connect($host, $user, $pass, $db);
                  $query = "SELECT id, id_transaccion, fecha, status, email, id_cliente, total, medio_pago FROM compra ORDER BY DATE(fecha) DESC; ";
                  $res = mysqli_query($con, $query);
                  while ($row = mysqli_fetch_assoc($res)) {
                  ?>
                  <tr>
                    <td><?php echo $row['id'];?></td>
                    <td><?php echo $row['id_transaccion'];?></td>
                    <td><?php echo $row['fecha'];?></td>
                    <td><?php echo $row['status'];?></td>
                    <td><?php echo $row['email'];?></td>
                    <td><?php echo $row['id_cliente'];?></td>
                    <td><?php echo $row['total'];?></td>
                    <td><?php echo $row['total'] *800;?></td>
                    <td><?php echo $row['medio_pago'];?></td>
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