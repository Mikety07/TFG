<?php


if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['rol'] != "administrador") {
    header('location: index.php');
    exit();
}


if (!isset($_GET['edificio'])) {
    header('location: index.php');
    exit();
}

include_once "FUNCIONES/funciones.php";


$idEdificio = $_GET['edificio'];


$mensaje = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombreZona = $_POST['nombreZona'];
    $numeroPlazas = $_POST['numeroPlazas'];

   
    $con = conectarBD();


    $sql = "INSERT INTO zona (nombre, num_plazas, idEdificio) VALUES ('$nombreZona', '$numeroPlazas', '$idEdificio')";


    if (!mysqli_query($con, $sql)) {
        $mensaje = "<div class='alert alert-danger'>Hubo un error al añadir la zona.</div>";
    } else {
        $mensaje = "<div class='alert alert-success'>Zona añadida correctamente.</div>";
    }

 
    $con->close();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nueva Zona</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Añadir Nueva Zona</h2>


    <?php echo $mensaje; ?>

   
    <form method="POST" action="">
        <div class="form-group">
            <label for="nombreZona">Nombre de la Zona</label>
            <input type="text" class="form-control" id="nombreZona" name="nombreZona" required>
        </div>

        <div class="form-group">
            <label for="numeroPlazas">Número de Plazas</label>
            <input type="number" class="form-control" id="numeroPlazas" name="numeroPlazas" required>
        </div>

        <button type="submit" class="btn btn-primary">Añadir Zona</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
