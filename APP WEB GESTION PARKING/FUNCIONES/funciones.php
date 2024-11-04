<?php

function conectarBD()
{
    $DB_SERVER = "localhost";
    $DB_NAME = "parking";
    $DB_USER = "root";
    $DB_PASS = "";

    $mysqli = new mysqli($DB_SERVER,$DB_USER,$DB_PASS,$DB_NAME);

    // Para no tener problema con las tildes ni eñes
    $mysqli->query("SET NAMES 'utf8'");

    if($mysqli)
        return $mysqli;
    else {
      echo "Fallo al conectarse a la BD";
    }
}

function existeUsuario($email)
{
  $con=conectarBD();

  $sql = "SELECT * FROM `usuario` WHERE email='$email'";

  $result = $con->query($sql);

  $value = $con->affected_rows;

  $con->close();

  return $value;
}

function registrarCliente($email,$password,$nombre,$primerApellido,$segundoApellido)
{
  $con = conectarBD();

  $passHash = password_hash($password, PASSWORD_BCRYPT);

  $sql = "INSERT INTO usuario(email, pass, nombre, apellido1, apellido2, rol) VALUES ('$email','$passHash','$nombre','$primerApellido','$segundoApellido','cliente')";

  if(!mysqli_query($con, $sql))
  {
    $con->close();
    return 0;
  }
  else
  {
    $con->close();
    return 1;
  }
}

function comprobarLogin($email, $password)
{
    $con = conectarBD();

    $sql = "SELECT * FROM `usuario` WHERE email='$email'";

    $resultado_BD = mysqli_query($con, $sql);

    $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

    $con->close();

    if($fila_consulta['email'] == $email && password_verify($password, $fila_consulta['pass']))
    {
      return 1;
    }

    return 0;
}

function obtenerIDUsuario($email)
{
  $con = conectarBD();

	$sql = "SELECT * FROM `usuario` WHERE email='$email'";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $con->close();

  if(!$resultado_BD)
  {
      return -1;
  }
  else
  {
    return $fila_consulta['idUsuario'];
  }
}

function obtenerNombreUsuarioID($usuario)
{
  $con = conectarBD();

	$sql = "SELECT * FROM `usuario` WHERE idUsuario='$usuario'";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  if(!$resultado_BD)
  {
      return -1;
  }
  else
  {
      return $fila_consulta['nombre'];
  }
}

function obtenerNombreUsuario($usuario)
{
  $con = conectarBD();

	$sql = "SELECT * FROM `usuario` WHERE email='$usuario'";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  if(!$resultado_BD)
  {
      return -1;
  }
  else
  {
      return $fila_consulta['nombre'];
  }
}

function obtenerApellUsuario($usuario)
{
  $con = conectarBD();

    $sql = "SELECT * FROM `usuario` WHERE email='$usuario'";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $con->close();

  if(!$resultado_BD)
  {
      return -1;
  }
  else
  {
      return $fila_consulta['apellido1']." ".$fila_consulta['apellido2'];
  }
}

function obtenerApellUsuarioID($usuario)
{
  $con = conectarBD();

    $sql = "SELECT * FROM `usuario` WHERE idUsuario='$usuario'";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $con->close();

  if(!$resultado_BD)
  {
      return -1;
  }
  else
  {
      return $fila_consulta['apellido1']." ".$fila_consulta['apellido2'];
  }
}

function obtenerRol($usuario)
{
  $con = conectarBD();

  $sql = "SELECT * FROM `usuario` WHERE email='$usuario'";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $con ->close();

  if(!$resultado_BD)
  {
      return -1;
  }
  else
  {
    return $fila_consulta['rol'];
  }
}

function actualizarUltimoAcceso($usuario, $fecha)
{
    $con = conectarBD();
    
    $sql = "UPDATE `usuario` SET ultimo_acceso='$fecha' WHERE idUsuario='$usuario'";

    if(mysqli_query($con, $sql))
    {
        $con->close();
        return 1;
    }
    else
    {

        $con->close();
        return 0;
    }

}

function revisarTiempoSesion($idUsuario,$fechaGuardada)
{
  //Para comprobar si la sesion ha caducado
  $ahora = date("Y-n-j H:i:s");
  $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada));

  //comparo el tiempo transcurrido
  if($tiempo_transcurrido >= 1200) //Si han  pasado 20 minutos o mas
  {
      session_destroy();
      header("Location: index.php");
  }

  //Si no ha caducado la sesion, actualizo el ultimo acceso a la fecha actual

  $ultimoacceso = strftime("%d/%m/%Y %H:%M", time());
  actualizarUltimoAcceso($idUsuario, $ultimoacceso);

  return $ahora;
}

function nuevoEdificio($nombre,$descripcion,$latitud,$longitud,$idUsuario)
{
  $con=conectarBD();

  $sql = "SELECT * FROM `edificio` WHERE nombre='$nombre'";

  $result = $con->query($sql);

  $value = $con->affected_rows;

  if($value == 1)
  {
    $con->close();
    return 0;
  }

  $sql = "INSERT INTO edificio(nombre,descripcion,latitud,longitud,idUsuario) VALUES ('$nombre','$descripcion','$latitud','$longitud','$idUsuario')";

  if(!mysqli_query($con, $sql))
  {
    $con->close();
    return 0;
  }

  $con->close();
  return 1;
}

function obtenerInfoEdificio($idEdificio)
{
  $con = conectarBD();
  $sql = "SELECT * FROM edificio WHERE idEdificio=$idEdificio";
  $resultado_BD = mysqli_query($con, $sql);
  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);
  $con ->close();
  return $fila_consulta;
}


function nuevoVehiculo($matricula,$marca,$modelo,$propietario)
{
  $con=conectarBD();

  $sql = "SELECT * FROM `vehiculo` WHERE matricula='$matricula'";

  $result = $con->query($sql);

  $value = $con->affected_rows;

  if($value == 1)
  {
    $con->close();
    return 0;
  }

  $sql = "INSERT INTO vehiculo(matricula,marca,modelo,propietario) VALUES ('$matricula','$marca','$modelo','$propietario')";

  if(!mysqli_query($con, $sql))
  {
    $con->close();
    return 0;
  }

  $con->close();
  return 1;
}

function obtenerPropietarioMatricula($matricula)
{
  $con=conectarBD();

  $sql = "SELECT * FROM `vehiculo` WHERE matricula='$matricula' AND propietario=3";

  $result = $con->query($sql);

  $value = $con->affected_rows;

  $con->close();

  return $value;
}

function comprobarMatriculaPago($matricula)
{
  $con=conectarBD();

  $sql = "SELECT * FROM `registrovehiculo` WHERE matricula='$matricula' AND salida IS NULL";

  $result = $con->query($sql);

  $value = $con->affected_rows;

  $con->close();

  return $value;
}

function calcularImporteTicket($matricula)
{
  $con = conectarBD();

  $sql = "SELECT * FROM `registrovehiculo` WHERE matricula='$matricula' AND salida IS NULL";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $entrada = $fila_consulta['entrada'];
  $entrada = new DateTime($entrada);

  $salida = new DateTime(); 

 
  $interval = $entrada->diff($salida);
  $horas = $interval->h;
  $horas += $interval->days * 24; 

 
  $costo = 0;
  if ($horas <= 1) {
      $costo = 2.00; // Tarifa básica por la primera hora
  } else {
      $costo = 2.00 + ($horas - 1) * 1.00; // 2.00€ por la primera hora, 1.00€ por cada hora adicional
  }

  $con->close();

  return $costo;
}

function calcularFechaEntrada($matricula)
{
  $con = conectarBD();

  $sql = "SELECT * FROM `registrovehiculo` WHERE matricula='$matricula' AND salida IS NULL";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $con->close();

  return $fila_consulta['entrada'];
}

function obtenerNombreEdificioRegistro($matricula)
{
  $con = conectarBD();

  $sql = "SELECT * FROM `registrovehiculo` WHERE matricula='$matricula' AND salida IS NULL";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $idZona = $fila_consulta['idZona'];

  $sql = "SELECT * FROM `zona` WHERE idZona=$idZona";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $idEdificio = $fila_consulta['idEdificio'];

  $sql = "SELECT * FROM `edificio` WHERE idEdificio = $idEdificio";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $con->close();

  return $fila_consulta['nombre'];
}

function obtenerNombreZona($matricula)
{
  $con = conectarBD();

  $sql = "SELECT * FROM `registrovehiculo` WHERE matricula='$matricula' AND salida IS NULL";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $idZona = $fila_consulta['idZona'];

  $sql = "SELECT * FROM `zona` WHERE idZona=$idZona";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $con->close();

  return $fila_consulta['nombre'];
}

function obtenerPlazasOcupadasZona($idZona)
{
  $con = conectarBD();

  $sql = "SELECT COUNT(*) FROM `registrovehiculo` WHERE idZona=$idZona";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $con->close();

  return $fila_consulta['COUNT(*)'];
}

function obtenerInfoZona($idZona)
{
  $con = conectarBD();

  $sql = "SELECT * FROM `zona` WHERE idZona=$idZona";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $con->close();

  return $fila_consulta;
}

function obtenerNumeroTicket($matricula)
{
  $con=conectarBD();

  $sql = "SELECT * FROM `registrovehiculo` WHERE matricula='$matricula' AND salida IS NULL";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $idRegistro = $fila_consulta['idRegistro'];

  $sql = "SELECT * FROM `ticket` WHERE idRegistro='$idRegistro'";

  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $con -> close();

  if (isset($fila_consulta['idTicket']))
    return $fila_consulta['idTicket'];
  return -1;
  
}

function marcarTicketPagado($idTicket,$importe)
{
  $con = conectarBD();
    
  $sql = "UPDATE `ticket` SET estado='pagado', importe='$importe' WHERE idTicket='$idTicket'";

  mysqli_query($con, $sql);

  $salida = new DateTime();
  $salida = $salida->format('Y-m-d H:i');

  $sql = "UPDATE `registrovehiculo` SET salida = '$salida' WHERE idRegistro = (SELECT idRegistro FROM `ticket` WHERE idTicket='$idTicket')";

  if(mysqli_query($con, $sql))
  {
      $con->close();
      return 1;
  }
  else
  {

      $con->close();
      return 0;
  }
}

function obtenerRegistrosPendientesUsuario($idUsuario)
{
  $con=conectarBD();

  $sql = "SELECT COUNT(*)
          FROM `registrovehiculo` r, `vehiculo` v
          WHERE r.salida IS NULL AND v.matricula=r.matricula AND v.propietario='$idUsuario'";
  
  $resultado_BD = mysqli_query($con, $sql);

  $fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC);

  $con -> close();

  return $fila_consulta['COUNT(*)'];
}

function obtenerCamaraPorZona($idZona) {
  
  $con = conectarBD();

  
  $sql = "SELECT * FROM camara WHERE idZona = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param('i', $idZona);


  $stmt->execute();


  $resultado = $stmt->get_result();

  
  if ($resultado->num_rows > 0) {
      $camara = $resultado->fetch_assoc();
      $stmt->close();
      $con->close();
      return $camara;
  } else {
      
      $stmt->close();
      $con->close();
      return null;
  }
}

function obtenerInfoCamara($idCamara) {

  $con = conectarBD();


  $sql = "SELECT * FROM camara WHERE idCamara = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param('i', $idCamara);


  $stmt->execute();


  $resultado = $stmt->get_result();


  if ($resultado->num_rows > 0) {
    
      $camara = $resultado->fetch_assoc();
      $stmt->close();
      $con->close();
      return $camara;
  } else {
     
      $stmt->close();
      $con->close();
      return null;
  }
}



?>
