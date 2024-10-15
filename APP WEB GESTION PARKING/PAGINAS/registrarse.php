<?php
include_once "FUNCIONES/funciones.php";
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h2>Formulario alta cliente</h2>
            <form method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" required>
                </div>
                <div class="form-group">
                    <label for="primerApellido">Primer Apellido</label>
                    <input type="text" class="form-control" name="primerApellido" id="primerApellido" required>
                </div>
                <div class="form-group">
                    <label for="segundoApellido">Segundo Apellido</label>
                    <input type="text" class="form-control" name="segundoApellido" id="segundoApellido">
                </div>

                <h4 class="mt-4">Vehículos</h4>
                <div id="vehiculos">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="matricula">Matrícula</label>
                            <input type="text" class="form-control" name="matriculas[]" id="matricula" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="marca">Marca</label>
                            <input type="text" class="form-control" name="marcas[]" id="marca" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="modelo">Modelo</label>
                            <input type="text" class="form-control" name="modelos[]" id="modelo" required>
                        </div>
                    </div>
                </div>
                <center>
                    <button type="button" class="btn btn-secondary" onclick="addVehicle()">Añadir otro vehículo</button>
                    <br>
                    <button type="submit" name="nuevoCliente" class="btn btn-primary mt-4">Enviar</button>
                </center>
            </form>
        </div>
    </div>
</div>

<!-- Modal OK-->
<div class="modal fade" id="modalOK" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modallabel">Datos añadidos con éxito</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            Los datos han sido registrados correctamente.
        </div>
        <div class="modal-footer">
            <a href="index.php?page=login" class="btn btn-secondary" role="button">Iniciar sesión</a>
            <a href="index.php" class="btn btn-primary" role="button">Salir</a>
        </div>
        </div>
    </div>
</div>

<!-- Modal ERROR-->
<div class="modal fade" id="modalError" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modallabel">Error en el formulario de registro</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            Los datos introducidos no son correctos. El usuario o vehículo(s) registrado(s) ya se encuentran en el sistema.
        </div>
        <div class="modal-footer">
            <a href="index.php?page=registrarse" class="btn btn-secondary" role="button">Intentar de nuevo</a>
            <a href="index.php" class="btn btn-primary" role="button">Salir</a>
        </div>
        </div>
    </div>
</div>


<!-- Enlace a Bootstrap JS y dependencias -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function addVehicle() {
        const vehicleForm = `
        <div class="form-row mt-3">
            <div class="form-group col-md-4">
                <label for="matricula">Matrícula</label>
                <input type="text" class="form-control" name="matriculas[]" id="matricula" required>
            </div>
            <div class="form-group col-md-4">
                <label for="marca">Marca</label>
                <input type="text" class="form-control" name="marcas[]" id="marca" required>
            </div>
            <div class="form-group col-md-4">
                <label for="modelo">Modelo</label>
                <input type="text" class="form-control" name="modelos[]" id="modelo" required>
            </div>
        </div>`;
        document.getElementById('vehiculos').insertAdjacentHTML('beforeend', vehicleForm);
    }
</script>


<?php
if(isset($_POST['nuevoCliente']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nombre = $_POST['nombre'];
    $primerApellido = $_POST['primerApellido'];
    $segundoApellido = isset($_POST['segundoApellido']) ? $_POST['segundoApellido'] : null;

    if(existeUsuario($email))
    {
        ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#modalError").modal('show');
                });
            </script>
        <?php
    }
    else
    {

        $value = registrarCliente($email,$password,$nombre,$primerApellido,$segundoApellido);

        $idNuevoUsuario = obtenerIDUsuario($email);

        // Captura de los datos de los vehículos
        $matriculas = isset($_POST['matriculas']) ? $_POST['matriculas'] : [];
        $marcas = isset($_POST['marcas']) ? $_POST['marcas'] : [];
        $modelos = isset($_POST['modelos']) ? $_POST['modelos'] : [];

        foreach ($matriculas as $index => $matricula) 
        {
            $marca = isset($marcas[$index]) ? $marcas[$index] : null;
            $modelo = isset($modelos[$index]) ? $modelos[$index] : null;

            $value = nuevoVehiculo($matricula, $marca, $modelo,$idNuevoUsuario);
            
            if($value == 0)
            {
                ?>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $("#modalError").modal('show');
                        });
                    </script>
                <?php
            }
        }
    }

    ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#modalOK").modal('show');
            });
        </script>
    <?php

}
?>