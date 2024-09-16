<center>
    <br />

    <div class="container">

      <img src="IMAGENES/estacionamiento.png" width="250"/>
      <br>
      <br>

        <div class="card text-center" style="max-width: 30rem; background: #f1e0a6;">
          <div class="card-header">
            <b><p style="color:#2366cd";>INICIO SESIÓN</p></b>
          </div>
          <div class="card-body">


                <br />


                <form name="formulario_login" method="POST" enctype="application/x-www-form-urlencoded">

                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                      </div>
                      <input type="text" name="login_usuario" placeholder="Usuario" required class="form-control">
                    </div>

                    <br />

                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                      </div>
                      <input type="password" name="login_password" placeholder="Contraseña" required class="form-control"/>
                    </div>

                    <br />
                    <input class="btn btn-light" name="boton_acceder" type="submit" value="ACCEDER">

                </form>

          </div>
          <div class="card-footer text-black">
            Gestión de Parking  © <?php echo date("Y");?>
          </div>
        </div>

    </div>

    <?php

            include_once "FUNCIONES/funciones.php"; //para poder usar las funciones

            //session_destroy();

            if(!isset($_SESSION['usuario']))
            {


                if(isset($_POST['boton_acceder']))
                {


                    $valor = comprobarLogin($_POST['login_usuario'],$_POST['login_password']);

                    if( $valor == 1)
                    {

                        if(!isset($_SESSION))
                            session_start();

                        //Relleno las variables de SESSION
                        $_SESSION['usuario'] = obtenerIDUsuario($_POST['login_usuario']);
                        $_SESSION['email'] = $_POST['login_usuario']; 
                        $_SESSION['ultimoAcceso'] = date("Y-n-j H:i:s"); 
                        $_SESSION['nombre']=obtenerNombreUsuario($_POST['login_usuario']); 
                        $_SESSION['apellidos']=obtenerApellUsuario($_POST['login_usuario']); 
                        $_SESSION['rol']=obtenerRol($_POST['login_usuario']); 

                        $ultimoacceso = strftime("%d/%m/%Y %H:%M", time());
                        actualizarUltimoAcceso($_SESSION['idUsuario'], $ultimoacceso);

                        header("Location: index.php?page=inicio");

                    }
                    else if ($valor == 0)
                    {
                        echo "<br />"; ?>

                        <div class="alert alert-danger" role="alert">
                            ¡Usuario o contraseña incorrectos, inténtelo de nuevo!
                        </div>

                        <?php

                    }
                    else if ($valor == -1)
                    {
                        echo "<br />"; ?>

                        <div class="alert alert-danger" role="alert">
                            ¡Hubo un error al consultar la base de datos!
                        </div>

                        <?php
                    }
                }
            }
        ?>

</center>
