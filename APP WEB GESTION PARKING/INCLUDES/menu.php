<nav>

  <?php

      if(!isset($_SESSION))
        session_start();


  ?>

  <?php if(!isset($_SESSION['usuario'])) {//Si no se ha iniciado sesion solo se muestra encabezado?>

    <nav class="navbar navbar-expand-lg navbar-light" style="background: #f1e0a6">
      <a  class="navbar-brand" style="color:#2366cd ;" href="index.php">Gestión de Parking</a>
    </nav>

  <?php } else if ($_SESSION['rol'] == "administrador") //ROL ADMINISTRADOR
  {
    ?>
    <nav class="navbar navbar-expand-lg navbar-light" style="background: #f1e0a6;">
        <div class="navbar-header">
        <a class="navbar-brand" href="index.php?page=inicio"><img src="IMAGENES/estacionamiento.png" alt="GESTION DE PARKING" width="10%"></a>
    	</div>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-black" id="navbarNav">
          <ul class="navbar-nav">

          <li class="nav-item active">
              <a class="nav-link text-black" href="index.php?page=inicio">Inicio<span class="sr-only">(current)</span></a>
          </li>

          <li class="nav-item active">
              <a class="nav-link text-black" href="index.php?page=edificios">Edificios <span class="badge badge-light"></span></a>
          </li>

          <li class="nav-item active">
              <a class="nav-link text-black" href="index.php?page=clientes">Clientes<span class="badge badge-light"></span></a>
          </li>

          <li class="nav-item active">
              <a class="nav-link text-black" href="index.php?page=tickets">Tickets<span class="badge badge-light"></span></a>
          </li>


           

            <!--
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Alumnos
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="index.php?page=nuevoalumno">Nuevo alumno</a>
                <a class="dropdown-item" href="index.php?page=alumnos">Ver alumnos</a>
              </div>
            </li>
            -->
            <li class="nav-item active">
              <a class="nav-link text-black" href="index.php?page=cerrar_sesion">Cerrar sesión</a>
            </li>

          </ul>
        </div>
      </nav>
    <?php
  } else if ($_SESSION['rol'] == "cliente") //ROL ADMINISTRADOR
  {
    ?>
    <nav class="navbar navbar-expand-lg navbar-light" style="background: #f1e0a6;">
        <div class="navbar-header">
        <a class="navbar-brand" href="index.php?page=inicio"><img src="IMAGENES/estacionamiento.png" alt="GESTION DE PARKING" width="10%"></a>
    	</div>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarNav">
          <ul class="navbar-nav">

            <li class="nav-item active">
              <a class="nav-link text-black" href="index.php?page=inicio">Inicio<span class="sr-only">(current)</span></a>
            </li>

            <li class="nav-item active">
              <a class="nav-link text-black" href="index.php?page=mistickets">Mis tickets <span class="badge badge-light"></span></a>
            </li>

            <li class="nav-item active">
              <a class="nav-link text-black" href="index.php?page=misvehiculos">Mis vehículos<span class="badge badge-light"></span></a>
            </li>

            <!--
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Alumnos
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="index.php?page=nuevoalumno">Nuevo alumno</a>
                <a class="dropdown-item" href="index.php?page=alumnos">Ver alumnos</a>
              </div>
            </li>
            -->
            <li class="nav-item active">
              <a class="nav-link text-black" href="index.php?page=cerrar_sesion">Cerrar sesión</a>
            </li>

          </ul>
        </div>
      </nav>
    <?php
  }

?>

</nav>
