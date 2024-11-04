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
if (!isset($_GET['edificio'])) {
    header('location: index.php');
    exit();
}

include_once "FUNCIONES/funciones.php";

// Establecer la última hora de acceso de la sesión
$_SESSION['ultimoAcceso'] = revisarTiempoSesion($_SESSION['usuario'], $_SESSION['ultimoAcceso']);

// Obtener la información del edificio mediante su ID
$idEdificio = $_GET['edificio'];
$edificio = obtenerInfoEdificio($idEdificio);

// Si se ha enviado el formulario, procesar la actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];

    // Conectar a la base de datos
    $con = conectarBD();

    // Actualizar la información del edificio
    $sql = "UPDATE edificio SET nombre = ?, descripcion = ?, latitud = ?, longitud = ? WHERE idEdificio = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('sssdi', $nombre, $descripcion, $latitud, $longitud, $idEdificio);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Información del edificio actualizada correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger'>Hubo un error al actualizar la información del edificio.</div>";
    }

    $con->close();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Información del Edificio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card-container {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Información del Edificio</h2>

    <!-- Contenedor con estilo para el formulario -->
    <div class="card-container">
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre del Edificio</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $edificio['nombre']; ?>" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo $edificio['descripcion']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="latitud">Latitud</label>
                <input type="text" class="form-control" id="latitud" name="latitud" value="<?php echo $edificio['latitud']; ?>" required>
            </div>

            <div class="form-group">
                <label for="longitud">Longitud</label>
                <input type="text" class="form-control" id="longitud" name="longitud" value="<?php echo $edificio['longitud']; ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="index.php?page=verEdificio&id=<?php echo $idEdificio; ?>" class="btn btn-secondary">Cancelar</a>

            <br><br>

            <!-- Botón para abrir el modal de confirmación -->
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">Eliminar edificio</button>
        </form>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este edificio? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <a href="index.php?page=eliminarEdificio&id=<?php echo $idEdificio; ?>" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
