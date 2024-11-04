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
if (!isset($_GET['id'])) {
    header('location: index.php');
    exit();
}

include_once "FUNCIONES/funciones.php";

// Obtener el ID del edificio
$idEdificio = $_GET['id'];

// Conectar a la base de datos
$con = conectarBD();

if ($con) {
    // Preparar y ejecutar la consulta para eliminar el edificio
    $sql = "DELETE FROM edificio WHERE idEdificio = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $idEdificio);

    if ($stmt->execute()) {
        // Redirigir a index.php después de eliminar
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
