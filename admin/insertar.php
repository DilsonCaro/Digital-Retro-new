<?php 
    include_once "bdecommerce.php";
    $con = mysqli_connect($host, $user, $pass, $db);
    $nombre_videojuego=$_POST['nombreV'];
    $precio=$_POST['precio'];
    $plataformas=array(1=>"PS5", 2=>"PS4", 3=>"Steam", 4=>"XBOX X/S", 5=>"Epic Games", 6=>"EA Play", );
    $plataforma=$plataformas[$_POST['plataforma']];
    $key_videojuego=$_POST['keyV'];
    $stock=$_POST['stock'];
    $descripcion= $_POST['descrip'];
    $categoria=$_POST['categ'];
    $fecha_lanzamiento=$_POST['fechaV'];
    $clasificacion=$_POST['clasificacion'];
    $estado=$_POST['estado'];
    $imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
    $sql ="INSERT INTO productos (nombre_videojuego, precio, plataforma, key_videojuego, stock, descripcion, categoria, fecha_lanzamiento, clasificacion, estado, imagen) VALUES ('$nombre_videojuego','$precio','$plataforma','$key_videojuego','$stock','$descripcion','$categoria','$fecha_lanzamiento','$clasificacion','$estado','$imagen')";
    $query = mysqli_query($con,$sql);

    if($query === TRUE) {
         header("Location: panel.php?modulo=productos");
         
    }
 
?>