<?php

if(!isset($_SESSION))
{
    header('location: index.php');
}

if($_SESSION['rol']!="administrador")
{
    header('location: index.php');
}

if(!isset($_GET['idCamara']))
{
    header('location: index.php');
}

include_once "FUNCIONES/funciones.php";

$_SESSION['ultimoAcceso'] = revisarTiempoSesion($_SESSION['usuario'],$_SESSION['ultimoAcceso']);

$idCamara = $_GET['idCamara'];

// Obtener información de la cámara
$con = conectarBD();
$sql = "SELECT * FROM `camara` WHERE idCamara=$idCamara";
$resultado_BD = mysqli_query($con, $sql);
$camara = mysqli_fetch_assoc($resultado_BD);
$con->close();

// Si no existe la cámara
if (!$camara) {
    echo "No se ha encontrado la cámara";
    exit;
}

// URL de la transmisión en vivo desde la base de datos
$urlCamara = $camara['urlConexion'];

?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Transmisión en vivo de la cámara: <?php echo $camara['nombre']; ?></h2>

    <!-- Descripción de la cámara -->
    <p><strong>Descripción:</strong> <?php echo $camara['descripcion']; ?></p>

    <!-- Stream de la cámara -->
    <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item" src="<?php echo $urlCamara; ?>" allowfullscreen></iframe>
    </div>
</div>
