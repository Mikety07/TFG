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


$con = conectarBD();
$sql = "SELECT * FROM `camara` WHERE idCamara=$idCamara";
$resultado_BD = mysqli_query($con, $sql);
$camara = mysqli_fetch_assoc($resultado_BD);
$con->close();


if (!$camara) {
    echo "No se ha encontrado la cámara";
    exit;
}


$urlCamara = $camara['urlConexion'];

?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Transmisión en vivo de la cámara: <?php echo $camara['nombre']; ?></h2>


    <p><strong>Descripción:</strong> <?php echo $camara['descripcion']; ?></p>


    <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item" src="<?php echo $urlCamara; ?>" allowfullscreen></iframe>
    </div>
</div>
