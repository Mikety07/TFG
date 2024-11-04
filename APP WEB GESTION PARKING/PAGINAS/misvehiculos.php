<?php

if(!isset($_SESSION))
{
    header('location: index.php');
}

include_once "FUNCIONES/funciones.php";

$_SESSION['ultimoAcceso'] = revisarTiempoSesion($_SESSION['usuario'],$_SESSION['ultimoAcceso']);

?>
<br>
<div class="container d-flex vh-100">
    <div class="row justify-content-center align-self-center w-100">
        <div class="col text-center">
            <a href="index.php?page=nuevoVehiculo" class="btn btn-info btn-lg">Añadir Nuevo Vehículo</a>
        </div>
    </div>
</div>

<?php
$idUsuario = $_SESSION['usuario'];
$con = conectarBD();
$sql = "SELECT * FROM `vehiculo` WHERE propietario=$idUsuario";
$resultado_BD = mysqli_query($con, $sql);
?>

<div class="container mt-5">
        <h2 class="text-center mb-4">Lista de Vehículos</h2>
        
        <!-- Buscador -->
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar vehiculo...">
            <div class="input-group-append">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
            </div>
        </div>
        
        <!-- Tabla -->
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Matrícula</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                </tr>
            </thead>
            <tbody id="edificioTable">
                <?php
                while ($fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC))
                {
                    $matricula = $fila_consulta['matricula'];
                    $marca = $fila_consulta['marca'];
                    $modelo = $fila_consulta['modelo'];
                    echo "<tr>";
                    echo "<td>$matricula</td>";
                    echo "<td>$marca</td>";
                    echo "<td>$modelo</td>";
                    echo "</tr>";
                }
                $con -> close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Incluye jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Incluye Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Script para el buscador -->
    <script>
        $(document).ready(function() {
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#edificioTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
<?php

?>