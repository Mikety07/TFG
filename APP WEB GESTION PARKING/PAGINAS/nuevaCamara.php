<?php

// Verificar si la sesión está iniciada y el usuario tiene el rol correcto
if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['rol'] != "administrador") {
    header('location: index.php');
    exit();
}

// Verificar si se ha proporcionado un ID de edificio
if (!isset($_GET['zona'])) {
    header('location: veredificios.php');
    exit();
}

include_once "FUNCIONES/funciones.php";

// Obtener el ID del edificio desde el parámetro GET
$idZona = $_GET['zona'];

// Inicializar variables para mensajes de éxito o error
$mensaje = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario según los campos de la cámara
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $url = $_POST['url'];

    // Conectar a la base de datos
    $con = conectarBD();

    // Preparar la consulta SQL para insertar la nueva cámara
    $sql = "INSERT INTO camara (nombre, descripcion, urlConexion, idZona) VALUES ('$nombre', '$descripcion', '$url', '$idZona')";

    // Ejecutar la consulta y verificar si se insertó correctamente
    if (mysqli_query($con, $sql)) {
        $con->close();
        header('location: index.php?page=edificios');
        exit();
    } else {
        $mensaje = "<div class='alert alert-danger'>Hubo un error al añadir la cámara.</div>";
    }

    // Cerrar la conexión a la base de datos
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

    <!-- Mensaje de éxito o error -->
    <?php echo $mensaje; ?>

    <!-- Formulario para añadir una nueva cámara -->
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
