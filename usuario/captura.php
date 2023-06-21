<?php 
require 'servicios/config.php';
require 'servicios/conexion.php';

$db = new Database();
$con = $db->conectar();

$id_transaccion = isset($_GET['payment_id']) ? $_GET['payment_id'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

if ($id_transaccion != '') {

    $fecha = date("Y-m-d H:i:s");
    $total = isset($_SESSION['carrito']['total']) ? $_SESSION['carrito']['total'] : 0;

    $id_cliente = $_SESSION['user_cliente'];
    $sql = $con->prepare("SELECT email FROM clientes WHERE id=? AND estatus=1");
    $sql->execute([$id_cliente]);
    $row_cliente = $sql->fetch(PDO::FETCH_ASSOC);
    $email = $row_cliente['email'];

    $comando = $con->prepare("INSERT INTO compra (fecha, status, email, id_cliente, total, id_transaccion, medio_pago) 
    VALUES (?,?,?,?,?,?,?)");
    $comando->execute([$fecha, $status, $email, $id_cliente, $total, $id_transaccion,'MP']);
    $id = $con->lastInsertId();

    if ($id > 0) {
        $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

        if ($productos != null) {
            foreach ($productos as $clave => $cantidad) {
                $sqlProd = $con->prepare("SELECT id, nombre_videojuego,precio FROM productos WHERE id=? AND estado=1");
                $sqlProd->execute([$clave]);
                $row_prod = $sqlProd->fetch(PDO::FETCH_ASSOC);

                $nombre = $row_prod['nombre_videojuego'];
                $precio = $row_prod['precio'];
                
                $sql = $con->prepare("INSERT INTO detalle_compra(id_compra, id_producto, nombre, 
                precio, cantidad) VALUES (?,?,?,?,?)");

                $sql->execute([$id, $row_prod['id'], $nombre, $precio, $cantidad]);
            }

            require 'clases/mailer.php';

            $asunto = "Detalles de su compra";
            $cuerpo = '<h4>Gracias por su compra</h4>';
            $cuerpo .= '<p>El ID de su compra es <b>' .$id_transaccion.'</b></p>';

            $mailer = new Mailer();
            $mailer->enviarEmail($email, $asunto, $cuerpo);

        }
        unset($_SESSION['carrito']);
        header("Location: ". SITE_URL ."/completado.php?key=" . $id_transaccion);
    }
  
}


?>