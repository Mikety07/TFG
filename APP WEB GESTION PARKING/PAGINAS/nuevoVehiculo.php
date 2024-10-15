<?php

if(!isset($_SESSION))
{
    header('location: index.php');
}

include_once "FUNCIONES/funciones.php";

$_SESSION['ultimoAcceso'] = revisarTiempoSesion($_SESSION['usuario'],$_SESSION['ultimoAcceso']);

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Añadir Nuevo Vehículo</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <!-- Matricula -->
                        <div class="form-group">
                            <label for="matricula">Matrícula <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="matricula" placeholder="Ingrese la matrícula del vehículo" required>
                        </div>

                        <!-- Marca -->
                        <div class="form-group">
                            <label for="marca">Marca <span class="text-danger">*</label>
                            <input type="text" class="form-control" name="marca" placeholder="Ingrese la marca del vehículo" required>
                        </div>

                        <!-- Modelo -->
                        <div class="form-group">
                            <label for="modelo">Modelo <span class="text-danger">*</label>
                            <input type="text" class="form-control" name="modelo" placeholder="Ingrese el modelo del vehículo" required>
                        </div>

                        <!-- Botón de enviar -->
                        <div class="text-center">
                            <button type="submit" name="boton_nuevo" class="btn btn-primary">Guardar Vehículo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal OK-->
<div class="modal fade" id="modalOK" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modallabel">VEHÍCULO DADO DE ALTA</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            El vehículo ha sido dado de alta correctamente
        </div>
        <div class="modal-footer">
            <a href="index.php?page=nuevoVehiculo" class="btn btn-secondary" role="button">Añadir otro</a>
            <a href="index.php?page=misvehiculos" class="btn btn-primary" role="button">Mis vehículos</a>
        </div>
        </div>
    </div>
    </div>

    <!-- Modal existeZona-->
    <div class="modal fade" id="modalERROR" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modallabel">ERROR, VEHÍCULO NO INSERTADO</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            Ya existe un vehículo con la misma matrícula, inténtelo de nuevo.
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-danger">Aceptar</button>
        </div>
        </div>
    </div>
    </div>

<?php
if(isset($_POST['boton_nuevo']))
{
    $matricula = $_POST['matricula'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $propietario = $_SESSION['usuario'];

    $value = nuevoVehiculo($matricula,$marca,$modelo,$propietario);

    if($value==1)
    {
        ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#modalOK").modal('show');
                });
            </script>
        <?php
    }
    else
    {
        ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#modalERROR").modal('show');
                });
            </script>
        <?php
    }
}
else
{
    echo "hola<br>";
}
?>