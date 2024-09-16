<?php

include_once "FUNCIONES/funciones.php";

?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Introduzca la matrícula</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <!-- Matrícula -->
                        <div class="form-group">
                            <label for="nombreEdificio">Matrícula <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="matricula" placeholder="Ingrese la matricula" required>
                        </div>

                        <!-- Botón de enviar -->
                        <div class="text-center">
                            <button type="submit" name="boton_siguiente" class="btn btn-primary">Siguiente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal OK-->
<div class="modal fade" id="modalVehiculoNoRegistrado" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modallabel">VEHÍCULO NO REGISTRADO</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            El vehículo no se encuentra asociado a ningún usuario.
        </div>
        <div class="modal-footer">
            <a href="index.php?page=registrarVehiculo" class="btn btn-primary" role="button">Registrar vehículo</a>
            <a href="index.php?page=continuarPago&matricula=<?php echo $_POST['matricula']; ?>" class="btn btn-info" role="button">Continuar con el pago</a>
        </div>
        </div>
    </div>
</div>

    
<div class="modal fade" id="modalVehiculoRegistrado" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modallabel">EL VEHÍCULO REGISTRADO EN EL SISTEMA</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            Este vehículo ya se encuentra registrado y asociado a un cliente del sistema.
        </div>
        <div class="modal-footer">
            <a href="index.php?page=login" class="btn btn-primary" role="button">Iniciar sesión</a>
            <a href="index.php?page=continuarPago&matricula=<?php echo $_POST['matricula']; ?>" class="btn btn-info" role="button">Continuar con el pago</a>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMatriculaNoPendiente" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modallabel">ERROR</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            No existe ningún vehículo con la matrícula introducida que esté pendiente de pago.
        </div>
        <div class="modal-footer">
            <a href="index.php?page=login_matricula" class="btn btn-primary" role="button">Volver</a>
        </div>
        </div>
    </div>
</div>

<?php
if(isset($_POST['boton_siguiente']))
{
    $value = comprobarMatriculaPago($_POST['matricula']);

    if($value == 0) //No hay pendiente cerrar ninguna entrada de un vehículo con esta matrícula
    {
        ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#modalMatriculaNoPendiente").modal('show');
                });
            </script>
        <?php
    }
    else
    {
        $value = obtenerPropietarioMatricula($_POST['matricula']);

        if($value==1) //No está asociado el vehículo a ningún propietario registrado
        {
            ?>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $("#modalVehiculoNoRegistrado").modal('show');
                    });
                </script>
            <?php
        }
        else
        {
            ?>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $("#modalVehiculoRegistrado").modal('show');
                    });
                </script>
            <?php
        }
    }
}
?>