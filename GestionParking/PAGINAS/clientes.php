<?php

if(!isset($_SESSION))
{
    header('location: index.php');
}

if($_SESSION['rol']!="administrador")
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
            <a href="index.php?page=registrarse" class="btn btn-info btn-lg">Añadir Nuevo Cliente</a>
        </div>
    </div>
</div>

<?php
$idUsuario = $_SESSION['usuario'];
$con = conectarBD();
$sql = "SELECT * FROM `usuario` WHERE rol='cliente'";
$resultado_BD = mysqli_query($con, $sql);
?>

<br><br>
<div class="container mt-5">
        <h2 class="text-center mb-4">Lista de Clientes</h2>
        
        <!-- Buscador -->
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Buscar cliente...">
            <div class="input-group-append">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
            </div>
        </div>
        
        <!-- Tabla -->
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Primer apellido</th>
                    <th>Segundo apellido</th>
                    <th>Último acceso</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="edificioTable">
                <?php
                while ($fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC))
                {
                    $idUsuario = $fila_consulta['idUsuario'];
                    $nombre = $fila_consulta['nombre'];
                    $apellido1 = $fila_consulta['apellido1'];
                    $apellido2 = $fila_consulta['apellido2'];
                    $ultimoAcceso = $fila_consulta['ultimo_acceso'];
                    echo "<tr>";
                    echo "<td>$nombre</td>";
                    echo "<td>$apellido1</td>";
                    echo "<td>$apellido2</td>";
                    echo "<td>$ultimoAcceso</td>";
                    echo "<td><a href=\"index.php?page=editarusuario&id=$idUsuario\"><img src=\"IMAGENES/editar.png\" style=\"width: 25px; height: auto;\"></a>
                            <a href=\"index.php?page=registrousuario&id=$idUsuario\"><img src=\"IMAGENES/lupa.png\" style=\"width: 25px; height: auto;\"></a></td>";
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