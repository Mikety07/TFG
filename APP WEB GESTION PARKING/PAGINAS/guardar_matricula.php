<?php

include_once "../FUNCIONES/funciones.php"; 


if (!isset($_POST['matricula'])) {
    die("Faltan datos requeridos.");
}


$matricula = $_POST['matricula'];  
$marca = "Desconocida";             
$modelo = "Desconocido";            
$propietario = 1;                  
$idZona = 1;                        


var_dump($matricula, $marca, $modelo, $propietario, $idZona);


if (empty($matricula) || empty($idZona)) {
    die("Faltan datos requeridos.");
}


$con = conectarBD();


$sqlCheck = "SELECT COUNT(*) FROM vehiculo WHERE matricula = ?";
$stmtCheck = $con->prepare($sqlCheck);
$stmtCheck->bind_param("s", $matricula);
$stmtCheck->execute();
$stmtCheck->bind_result($exists);
$stmtCheck->fetch();
$stmtCheck->close();

if ($exists == 0) {
    $sqlInsertVehiculo = "INSERT INTO vehiculo (matricula, marca, modelo, propietario) VALUES (?, ?, ?, ?)";
    $stmtInsertVehiculo = $con->prepare($sqlInsertVehiculo);
    $stmtInsertVehiculo->bind_param("sssi", $matricula, $marca, $modelo, $propietario);

    if (!$stmtInsertVehiculo->execute()) {
        die("Error al guardar vehículo: " . $stmtInsertVehiculo->error);
    }

    $stmtInsertVehiculo->close();
}


$sqlInsertRegistro = "INSERT INTO registrovehiculo (matricula, entrada, idZona) VALUES (?, NOW(), ?)";
$stmtInsertRegistro = $con->prepare($sqlInsertRegistro);
$stmtInsertRegistro->bind_param("si", $matricula, $idZona);


if ($stmtInsertRegistro->execute()) {
    echo "Registro guardado exitosamente.";

 
    $idRegistro = $stmtInsertRegistro->insert_id;

 
    $sqlInsertTicket = "INSERT INTO Ticket (idRegistro) VALUES (?)";
    $stmtInsertTicket = $con->prepare($sqlInsertTicket);
    $stmtInsertTicket->bind_param("i", $idRegistro);

    if ($stmtInsertTicket->execute()) {
        echo "Ticket creado exitosamente.";
    } else {
        echo "Error al crear Ticket: " . $stmtInsertTicket->error;
    }

    $stmtInsertTicket->close();

} else {
    echo "Error al guardar registro: " . $stmtInsertRegistro->error;
}


$stmtInsertRegistro->close();
$con->close();
?>
