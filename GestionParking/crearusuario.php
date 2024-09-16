<?php

function conectarBD()
{
    $DB_SERVER = "localhost";
    $DB_NAME = "parking";
    $DB_USER = "root";
    $DB_PASS = "1234";

    $mysqli = new mysqli($DB_SERVER,$DB_USER,$DB_PASS,$DB_NAME);

    // Para no tener problema con las tildes ni eñes
    $mysqli->query("SET NAMES 'utf8'");

    if($mysqli)
        return $mysqli;
    else {
      echo "Fallo al conectarse a la BD";
    }
}

	$email = $argv[1];
	$password = $argv[2];
	$nombre = $argv[3];
	$apellido1 = $argv[4];
  $apellido2 = $argv[5];
  $rol = $argv[6];

    $con = conectarBD();

    $passHash = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuario(email, pass, nombre, apellido1, apellido2, rol) VALUES ('$email','$passHash','$nombre','$apellido1','$apellido2','$rol')";

    echo "$sql";

    if(!mysqli_query($con, $sql))
    {
      echo "fallo";
        $con->close();
        return 0;
    }
    else
    {
      echo "correcto";
        $con->close();
        return 1;
    }

?>
