<?php


if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['rol'] != "administrador") {
    header('location: index.php');
    exit();
}


if (!isset($_GET['id'])) {
    header('location: index.php');
    exit();
}

include_once "FUNCIONES/funciones.php";


$idEdificio = $_GET['id'];


$con = conectarBD();

if ($con) {

    $sql = "DELETE FROM edificio WHERE idEdificio = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $idEdificio);

    if ($stmt->execute()) {
     
        header('location: index.php?page=edificios');
        exit();
    } else {
        echo "Error al eliminar el edificio.";
    }

    $stmt->close();
    $con->close();
} else {
    echo "No se pudo conectar a la base de datos.";
}

?>
