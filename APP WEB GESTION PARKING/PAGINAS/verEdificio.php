<?php

if(!isset($_SESSION))
{
    header('location: index.php');
}

if($_SESSION['rol']!="administrador")
{
    header('location: index.php');
}

if(!isset($_GET['id']))
{
    header('location: index.php');
}

include_once "FUNCIONES/funciones.php";

$_SESSION['ultimoAcceso'] = revisarTiempoSesion($_SESSION['usuario'],$_SESSION['ultimoAcceso']);

$idEdificio = $_GET['id'];

$edificio = obtenerInfoEdificio($idEdificio);

?>

<!-- Incluye Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<div class="container mt-5">
    <h2 class="text-center mb-4">Información del Edificio</h2>

    <!-- Información del Edificio -->
    <div class="card">
        <div class="card-body d-flex">
            <div>
                <h5 class="card-title"><?php echo $edificio['nombre']; ?></h5>
                <p class="card-text"><?php echo $edificio['descripcion']; ?></p>
                <p class="card-text"><strong>Latitud:</strong> <span id="latitud"><?php echo $edificio['latitud']; ?></span></p>
                <p class="card-text"><strong>Longitud:</strong> <span id="longitud"><?php echo $edificio['longitud']; ?></span></p>
            <a href="index.php?page=editarEdificio&edificio=<?php echo $idEdificio; ?>" class="btn btn-info ml-auto align-self-start">Editar edificio</a>
            <a href="index.php?page=nuevaZona&edificio=<?php echo $idEdificio; ?>" class="btn btn-success ml-auto align-self-start">Añadir zona</a>

            </div>
            <a href="index.php?page=verregistros&edificio=<?php echo $idEdificio; ?>" class="btn btn-warning ml-auto align-self-start">Ver registros</a>
        </div>
    </div>

    <!-- Mapa -->
    <div id="map" style="height: 400px;" class="mt-4"></div>

    <br>

    <!-- Información de las zonas -->
    <?php
    $con = conectarBD();
    $sql = "SELECT * FROM `zona` WHERE idEdificio=$idEdificio";
    $resultado_BD = mysqli_query($con, $sql);
    ?>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Zonas</h5>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Nº de plazas</th>
                        <th>% ocupación</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="edificioTable">
                    <?php
                    while ($fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC))
                    {
                        $idZona = $fila_consulta["idZona"];
                        $nombre = $fila_consulta['nombre'];
                        $plazas = $fila_consulta['num_plazas'];
                        $ocupacion = obtenerPlazasOcupadasZona($fila_consulta['idZona']);
                        $porcentaje = number_format(($ocupacion/$plazas)*100,2);
                        echo "<tr>";
                        echo "<td>$nombre</td>";
                        echo "<td>$plazas</td>";
                        echo "<td>$porcentaje %</td>";
                        echo "<td><a href=\"index.php?page=editarZona&zona=$idZona\">Editar  </a><a href=\"index.php?page=eliminarZona&zona=$idZona\">  Eliminar</a>  <a href=\"index.php?page=nuevaCamara&zona=$idZona\">Nueva Cámara</a></td>";
                        echo "</tr>";
                    }
                    $con -> close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <br>

    <!-- Información de las cámaras -->
    <?php
    $con = conectarBD();
    $sql = "SELECT * FROM `camara` WHERE idZona IN (SELECT idZona FROM `zona` WHERE idEdificio=$idEdificio)";
    $resultado_BD = mysqli_query($con, $sql);
    ?>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Cámaras</h5>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>URL</th>
                        <th>Zona</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="edificioTable">
                    <?php
                    while ($fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC))
                    {
                        $nombre = $fila_consulta['nombre'];
                        $descripcion = $fila_consulta['descripcion'];
                        $url = $fila_consulta['urlConexion'];
                        $idZona = $fila_consulta['idZona'];
                        $zona = obtenerInfoZona($idZona);
                        $nombreZona = $zona['nombre'];
                        $idCamara = $fila_consulta['idCamara'];
                        
                        echo "<tr>";
                        echo "<td>$nombre</td>";
                        echo "<td>$descripcion</td>";
                        echo "<td>$url</td>";
                        echo "<td>$nombreZona</td>";
                        echo "<td><a href=\"index.php?page=verCamara&idCamara={$fila_consulta['idCamara']}\">Ver </a> <a href=\"index.php?page=editarCamara&camara={$idCamara}\"> Editar </a> <a href=\"index.php?page=eliminarCamara&camara=$idCamara\"> Eliminar </a></td>";
                        echo "</tr>";
                    }
                    $con -> close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>


</div>


<!-- Incluye Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<!-- Script para mostrar el mapa -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Obtén las coordenadas desde los elementos HTML
    var latitud = parseFloat(document.getElementById('latitud').textContent);
    var longitud = parseFloat(document.getElementById('longitud').textContent);

    // Crear el mapa
    var map = L.map('map').setView([latitud, longitud], 13);

    // Añadir el tile layer de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Añadir un marcador en las coordenadas
    L.marker([latitud, longitud]).addTo(map)
        .bindPopup('Ubicación del edificio.')
        .openPopup();
});
</script>


?>