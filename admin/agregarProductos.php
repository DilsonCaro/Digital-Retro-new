<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Agregar Productos</title>
</head>

<body>
    <h1 class="text-center"> Agregar Productos</h1>
    <br>

    <div class="container">
        <form action="insertar.php" method="POST" enctype='multipart/form-data'>
            <div class="form-group mb-3">
                <label class="form-label">Nombre Videojuego</label>
                <input type="text" class="form-control" placeholder="God Of War" name="nombreV" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Precio</label>
                <input type="number" class="form-control" placeholder="$75.990" name="precio" required="required">
            </div>
            <div>
                <label class="form-group mb-3">Plataforma</label>
                <div>
                    <select name="plataforma" required="required">
                        <option value="0">Seleccionar</option>
                        <option value="1">PS5</option>
                        <option value="2">PS4</option>
                        <option value="3">Steam</option>
                        <option value="4">XBOX X/S</option>
                        <option value="5">Epic Games</option>
                        <option value="6">EA Play</option>
                    </select>
                </div>
            </div>
            <br><br>
            <div class="form-group mb-3">
                <label class="form-label">Key Videojuego</label>
                <input type="text" class="form-control" placeholder="AAAAA-BBBBB-CCCCC" name="keyV" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Stock</label>
                <input type="number" class="form-control" placeholder="10" name="stock" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">descripcion</label>
                <input type="text" class="form-control" placeholder="Juego mundo abierto de accion" name="descrip" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">categoria</label>
                <input type="text" class="form-control" placeholder="Mundo Abierto" name="categ" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Fecha Lanzamiento</label>
                <input type="date" class="form-control" name="fechaV" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Clasificacion</label>
                <input type="text" class="form-control" placeholder="PG13" name="clasificacion" required="required">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Estado</label>
                <input value="1" type="number" class="form-control" name="estado">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Imagen</label>
                <input type="file" class="form-control"  name="imagen" id="imagen" required="required">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
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