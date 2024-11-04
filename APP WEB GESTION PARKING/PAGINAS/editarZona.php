<?php

// Verificar si la sesión está iniciada y el usuario tiene el rol correcto
if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['rol'] != "administrador") {
    header('location: index.php');
    exit();
}

// Verificar si se ha proporcionado un ID de zona
if (!isset($_GET['zona'])) {
    header('location: index.php');
    exit();
}

include_once "FUNCIONES/funciones.php";

// Obtener el ID de la zona desde el parámetro GET
$idZona = $_GET['zona'];

// Inicializar variables para mensajes de éxito o error
$mensaje = '';

// Conectar a la base de datos
$con = conectarBD();

// Obtener los datos actuales de la zona
$sql = "SELECT nombre, num_plazas FROM zona WHERE idZona = '$idZona'";
$resultado = mysqli_query($con, $sql);
$zona = mysqli_fetch_assoc($resultado);

// Si se ha enviado el formulario, procesar la actualización de la zona
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $nombreZona = $_POST['nombreZona'];
    $numPlazas = $_POST['numPlazas'];

    // Preparar la consulta SQL para actualizar la zona
    $sql = "UPDATE zona SET nombre = '$nombreZona', num_plazas = '$numPlazas' WHERE idZona = '$idZona'";

    // Ejecutar la consulta y verificar si se actualizó correctamente
    if (!mysqli_query($con, $sql)) {
        $mensaje = "<div class='alert alert-danger'>Hubo un error al actualizar la zona.</div>";
    } else {
        $mensaje = "<div class='alert alert-success'>Zona actualizada correctamente.</div>";
    }
}

// Cerrar la conexión a la base de datos
$con->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Zona</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Zona</h2>

    <!-- Mensaje de éxito o error -->
    <?php echo $mensaje; ?>

    <!-- Formulario para editar la zona -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="nombreZona">Nombre de la Zona</label>
            <input type="text" class="form-control" id="nombreZona" name="nombreZona" value="<?php echo htmlspecialchars($zona['nombre']); ?>" required>
        </div>

        <div class="form-group">
            <label for="numPlazas">Número de Plazas</label>
            <input type="number" class="form-control" id="numPlazas" name="numPlazas" value="<?php echo htmlspecialchars($zona['num_plazas']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
