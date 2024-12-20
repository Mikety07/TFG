<?php


if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['rol'] != "administrador") {
    header('location: index.php');
    exit();
}


if (!isset($_GET['zona'])) {
    header('location: edificios.php');
    exit();
}

include_once "FUNCIONES/funciones.php";


$idZona = $_GET['zona'];


$mensaje = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['confirmar']) && $_POST['confirmar'] == 'si') {
       
        $con = conectarBD();

      
        $sql = "DELETE FROM zona WHERE idZona = '$idZona'";

      
        if (mysqli_query($con, $sql)) {
            $con->close();
            header('location: index.php?page=edificios');
            exit();
        } else {
            $mensaje = "<div class='alert alert-danger'>Hubo un error al eliminar la zona.</div>";
        }
        $con->close();
    } else {
      
        header('location: edificios.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Zona</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Eliminar Zona</h2>

  
    <?php if ($mensaje): ?>
        <?php echo $mensaje; ?>
    <?php else: ?>
        <div class="alert alert-warning">
            <p>¿Estás seguro de que deseas eliminar esta zona? Esta acción no se puede deshacer.</p>
            <form method="POST" action="">
                <button type="submit" name="confirmar" value="si" class="btn btn-danger">Eliminar</button>
                <a href="edificios.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
