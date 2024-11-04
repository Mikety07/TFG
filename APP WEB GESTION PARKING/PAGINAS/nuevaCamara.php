<?php


if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['rol'] != "administrador") {
    header('location: index.php');
    exit();
}


if (!isset($_GET['zona'])) {
    header('location: veredificios.php');
    exit();
}

include_once "FUNCIONES/funciones.php";


$idZona = $_GET['zona'];


$mensaje = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $url = $_POST['url'];


    $con = conectarBD();


    $sql = "INSERT INTO camara (nombre, descripcion, urlConexion, idZona) VALUES ('$nombre', '$descripcion', '$url', '$idZona')";


    if (mysqli_query($con, $sql)) {
        $con->close();
        header('location: index.php?page=edificios');
        exit();
    } else {
        $mensaje = "<div class='alert alert-danger'>Hubo un error al añadir la cámara.</div>";
    }


    $con->close();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nueva Cámara</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Añadir Nueva Cámara</h2>


    <?php echo $mensaje; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="nombre">Nombre de la Cámara</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="url">URL de la Cámara</label>
            <input type="url" class="form-control" id="url" name="url" required>
        </div>

        <button type="submit" class="btn btn-primary">Añadir Cámara</button>
        <a href="veredificios.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
