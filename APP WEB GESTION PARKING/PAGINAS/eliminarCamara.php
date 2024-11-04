<?php

// Verificar si la sesión está iniciada y el usuario tiene el rol correcto
if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['rol'] != "administrador") {
    header('location: index.php');
    exit();
}

// Verificar si se ha proporcionado un ID de cámara
if (!isset($_GET['camara'])) {
    header('location: veredificios.php');
    exit();
}

include_once "FUNCIONES/funciones.php";

// Obtener el ID de la cámara desde el parámetro GET
$idCamara = $_GET['camara'];

// Inicializar variable para mensaje de confirmación
$mensaje = '';

// Verificar si se ha confirmado la eliminación
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['confirmar']) && $_POST['confirmar'] == 'si') {
        // Conectar a la base de datos
        $con = conectarBD();

        // Preparar la consulta SQL para eliminar la cámara
        $sql = "DELETE FROM camara WHERE idCamara = '$idCamara'";

        // Ejecutar la consulta y verificar si se eliminó correctamente
        if (mysqli_query($con, $sql)) {
            $con->close();
            header('location: index.php?page=edificios');
            exit();
        } else {
            $mensaje = "<div class='alert alert-danger'>Hubo un error al eliminar la cámara.</div>";
        }
        $con->close();
    } else {
        // Si no se confirma, redirigir a veredificios.php
        header('location: veredificios.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Cámara</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Eliminar Cámara</h2>

    <!-- Mensaje de advertencia -->
    <?php if ($mensaje): ?>
        <?php echo $mensaje; ?>
    <?php else: ?>
        <div class="alert alert-warning">
            <p>¿Estás seguro de que deseas eliminar esta cámara? Esta acción no se puede deshacer.</p>
            <form method="POST" action="">
                <button type="submit" name="confirmar" value="si" class="btn btn-danger">Eliminar</button>
                <a href="veredificios.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
