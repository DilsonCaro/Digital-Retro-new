<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../servicios/config.php';
require '../servicios/conexion.php';
require 'clienteFunciones.php';
$db = new Database();
$con = $db->conectar();

$json = file_get_contents('php://input');
$datos = json_decode($json, true);

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
    $email = $row_cliente['email'];

    $sql = $con->prepare("INSERT INTO compra (id_transaccion, fecha, status, email, id_cliente, total, medio_pago) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $sql->execute([$id_transaccion, $fecha_nueva, $status, $email, $id_cliente, $total, 'PayPal']);
    $id = $con->lastInsertId();

    if ($id > 0) {
        $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;
        if ($productos != null) {
            $claves_videojuegos = array(); // Array para almacenar las claves de los videojuegos

            foreach ($productos as $clave => $cantidad) {
                $sql_prod = $con->prepare("SELECT id, nombre_videojuego, precio, key_videojuego, stock FROM productos WHERE id = ? AND estado = 1");
                $sql_prod->execute([$clave]);
                $row_prod = $sql_prod->fetch(PDO::FETCH_ASSOC);
    
                $id_producto = $row_prod['id'];
                $nombre = $row_prod['nombre_videojuego'];
                $precio = $row_prod['precio'];
                $key_videojuego = $row_prod['key_videojuego'];
                $stock = $row_prod['stock'];
    
                $sql_insert = $con->prepare("INSERT INTO detalle_compra (id_compra, id_producto, nombre, precio, cantidad) VALUES (?, ?, ?, ?, ?)");
                $sql_insert->execute([$id, $id_producto, $nombre, $precio, $cantidad]);
    
                $claves_videojuegos[] = $key_videojuego;
    
                // Restar la cantidad comprada al stock del producto
                $nuevo_stock = $stock - $cantidad;
                $sql_update = $con->prepare("UPDATE productos SET stock = ? WHERE id = ?");
                $sql_update->execute([$nuevo_stock, $id_producto]);
            }

            require 'mailer.php';

            $asunto = "Detalles de su compra";
            $cuerpo = '<h4>Gracias por su compra</h4>';
            $cuerpo .= '<p>El ID de su compra es <b>' . $id_transaccion . '</b></p>';
            $cuerpo .= '<p>Claves de los videojuegos:</p>';
            foreach ($claves_videojuegos as $clave_videojuego) {
                $cuerpo .= '<p>' .$clave_videojuego. '</p>';
            }

            $mailer = new Mailer();
            $mailer->enviarEmail($email, $asunto, $cuerpo);
        }
        unset($_SESSION['carrito']);
    }
}

?>