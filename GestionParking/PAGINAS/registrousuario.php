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

$idUsuario = $_GET['id'];

$nombreUsuario = obtenerNombreUsuarioID($idUsuario);
$apellidosUsuario = obtenerApellUsuarioID($idUsuario);

$con = conectarBD();
$sql = "SELECT z.nombre, v.matricula, v.marca, v.modelo, r.entrada, r.salida, t.importe
        FROM `registrovehiculo` r, `edificio` e, `zona` z, `vehiculo` v, `ticket` t
        WHERE r.idZona=z.idZona AND z.idEdificio=e.idEdificio AND r.matricula=v.matricula AND t.idRegistro=r.idRegistro AND v.propietario=$idUsuario";
$resultado_BD = mysqli_query($con, $sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Registro de <?php echo "$nombreUsuario $apellidosUsuario"; ?></h2>
    <!-- Información del Edificio -->
    <div class="card">
        <div class="card-body d-flex">
            <!-- Tabla -->
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Zona</th>
                        <th>Matrícula</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Importe</th>
                    </tr>
                </thead>
                <tbody id="edificioTable">
                    <?php
                    while ($fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC))
                    {
                        $zona = $fila_consulta['nombre'];
                        $matricula = $fila_consulta['matricula'];
                        $marca = ($fila_consulta['marca']=="") ? "---" : $fila_consulta['marca'];
                        $modelo = ($fila_consulta['modelo']=="") ? "---" : $fila_consulta['modelo'];
                        $entrada = $fila_consulta['entrada'];
                        $salida = ($fila_consulta['salida']=="") ? "---" : $fila_consulta['salida'];
                        $importe = ($fila_consulta['importe'])=="" ? "---" : $fila_consulta['importe'];
                        echo "<tr>";
                        echo "<td>$zona</td>";
                        echo "<td>$matricula</td>";
                        echo "<td>$marca</td>";
                        echo "<td>$modelo</td>";
                        echo "<td>$entrada</td>";
                        echo "<td>$salida</td>";
                        echo "<td>$importe</td>";
                        echo "</tr>";
                    }
                    $con -> close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
