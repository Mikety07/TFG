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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Añadir Nuevo Edificio</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <!-- Nombre del edificio -->
                        <div class="form-group">
                            <label for="nombreEdificio">Nombre del Edificio <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nombreEdificio" placeholder="Ingrese el nombre del edificio" required>
                        </div>

                        <!-- Descripción del edificio -->
                        <div class="form-group">
                            <label for="descripcionEdificio">Descripción del Edificio</label>
                            <textarea class="form-control" name="descripcionEdificio" rows="3" placeholder="Ingrese una descripción del edificio (opcional)"></textarea>
                        </div>

                        <!-- Coordenadas: latitud y longitud -->
                        <div class="form-group">
                            <label for="latitud">Latitud</label>
                            <input type="text" class="form-control" name="latitud" placeholder="Ingrese la latitud (opcional)">
                        </div>

                        <div class="form-group">
                            <label for="longitud">Longitud</label>
                            <input type="text" class="form-control" name="longitud" placeholder="Ingrese la longitud (opcional)">
                        </div>

                        <!-- Botón de enviar -->
                        <div class="text-center">
                            <button type="submit" name="boton_nuevo" class="btn btn-primary">Guardar Edificio</button>
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
            <h5 class="modal-title" id="modallabel">EDIFICIO DADO DE ALTA</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            El edificio ha sido dado de alta correctamente
        </div>
        <div class="modal-footer">
            <a href="index.php?page=nuevoEdificio" class="btn btn-secondary" role="button">Añadir otro</a>
            <a href="index.php?page=edificios" class="btn btn-primary" role="button">Ver edificios</a>
        </div>
        </div>
    </div>
</div>

<!-- Modal existeZona-->
    <div class="modal fade" id="modalERROR" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modallabel">ERROR, EDIFICIO NO INSERTADO</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            Ya existe un edificio con el mismo nombre, inténtelo de nuevo.
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
    $nombre = $_POST['nombreEdificio'];
    $descripcion = $_POST['descripcionEdificio'];
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];
    $idUsuario = $_SESSION['usuario'];

    $value = nuevoEdificio($nombre,$descripcion,$latitud,$longitud,$idUsuario);
    echo "$value<br>";

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