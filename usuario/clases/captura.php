<?php

require '../servicios/config.php';
require '../servicios/conexion.php';
$db = new Database();
$con = $db->conectar();

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

// echo '<pre>';
// print_r($datos);
// echo '</pre>';

if (is_array($datos)) {

    $id_cliente = $_SESSION['user_cliente'];
    $sql = $con->prepare("SELECT email FROM clientes WHERE id=? AND estatus=1");
    $sql->execute([$id_cliente]);
    $row_cliente = $sql->fetch(PDO::FETCH_ASSOC);

    $id_transaccion = $datos['detalles']['id'];
    $total = $datos['detalles']['purchase_units'][0]['amount']['value'];
    $status = $datos['detalles']['status'];
    $fecha = $datos['detalles']['update_time'];
    $fecha_nueva = date('Y-m-d H:i:s', strtotime($fecha));
    // $email = $datos['detalles']['payer']['email_address'];
    $email = $row_cliente['email'];
    //$id_cliente = $datos['detalles']['payer']['payer_id'];
    $sql = $con->prepare("INSERT INTO compra (id_transaccion, fecha, status, email, id_cliente, total, medio_pago) VALUES (
        ?,?,?,?,?,?,?)");
    $sql->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_cliente, $total, 'PayPal']);
    $id = $con->lastInsertId();

    if ($id > 0) {
        $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
        if ($productos != null) {
            foreach ($productos as $clave => $cantidad) {
                $sql = $con->prepare("SELECT id, nombre_videojuego, precio FROM productos WHERE id=? AND estado=1");
                $sql->execute([$clave]);
                $row_prod = $sql->fetch(PDO::FETCH_ASSOC);

                $nombre = $row_prod['nombre_videojuego'];
                $precio = $row_prod['precio'];

                $sql_insert = $con->prepare("INSERT INTO detalle_compra(id_compra, id_producto, nombre, 
                precio, cantidad) VALUES (?,?,?,?,?)");

                $sql_insert->execute([$id, $clave, $nombre, $precio, $cantidad]);
            }
            require 'mailer.php';

            $asunto = "Detalles de su compra";
            $cuerpo = '<h4>Gracias por su compra</h4>';
            $cuerpo .= '<p>El ID de su compra es <b>' .$id_transaccion.'</b></p>';

            $mailer = new Mailer();
            $mailer->enviarEmail($email, $asunto, $cuerpo);
        }
        unset($_SESSION['carrito']);
    }
}


?>