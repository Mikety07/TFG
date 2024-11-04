<?php


if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['rol'] != "administrador") {
    header('location: index.php');
    exit();
}


if (!isset($_GET['camara'])) {
    header('location: index.php');
    exit();
}

include_once "FUNCIONES/funciones.php";


$_SESSION['ultimoAcceso'] = revisarTiempoSesion($_SESSION['usuario'], $_SESSION['ultimoAcceso']);


$idCamara = $_GET['camara'];
$camara = obtenerInfoCamara($idCamara);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $nombreCamara = $_POST['nombreCamara'];
    $descripcionCamara = $_POST['descripcionCamara'];
    $urlCamara = $_POST['urlCamara'];

 
    $con = conectarBD();


    $sql = "UPDATE camara SET nombre = ?, descripcion = ?, urlConexion = ? WHERE idCamara = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('sssi', $nombreCamara, $descripcionCamara, $urlCamara, $idCamara);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Información de la cámara actualizada correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Hubo un error al actualizar la información de la cámara.</div>";
    }

    $con->close();
}

?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Información de la Cámara</h2>

    <form method="POST" action="">
        <div class="form-group">
            <label for="nombreCamara">Nombre de la Cámara</label>
            <input type="text" class="form-control" id="nombreCamara" name="nombreCamara" value="<?php echo $camara['nombre']; ?>" required>
        </div>

        <div class="form-group">
            <label for="descripcionCamara">Descripción</label>
            <textarea class="form-control" id="descripcionCamara" name="descripcionCamara" rows="3" required><?php echo $camara['descripcion']; ?></textarea>
        </div>

        <div class="form-group">
            <label for="urlCamara">URL de Conexión</label>
            <input type="text" class="form-control" id="urlCamara" name="urlCamara" value="<?php echo $camara['urlConexion']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="index.php?page=verZona&id=<?php echo $camara['idZona']; ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
