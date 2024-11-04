<?php
// Incluir funciones
include_once "../FUNCIONES/funciones.php"; // Ajusta la ruta según sea necesario

// Comprobar si se recibió una matrícula
if (!isset($_POST['matricula'])) {
    die("Faltan datos requeridos.");
}

// Datos a insertar
$matricula = $_POST['matricula'];  // Recibimos la matrícula desde la aplicación
$marca = "Desconocida";             // Puede ser ajustado según tus necesidades
$modelo = "Desconocido";            // Puede ser ajustado según tus necesidades
$propietario = 1;                   // Cambia al ID del propietario correspondiente, según tu lógica
$idZona = 1;                        // Cambia al ID de zona correspondiente

// Depuración: imprimir los valores que se van a insertar
var_dump($matricula, $marca, $modelo, $propietario, $idZona);

// Validar que la matrícula y el idZona no estén vacíos
if (empty($matricula) || empty($idZona)) {
    die("Faltan datos requeridos.");
}

// Conectar a la base de datos
$con = conectarBD();

// Verificar si la matrícula ya existe en la tabla vehiculo
$sqlCheck = "SELECT COUNT(*) FROM vehiculo WHERE matricula = ?";
$stmtCheck = $con->prepare($sqlCheck);
$stmtCheck->bind_param("s", $matricula);
$stmtCheck->execute();
$stmtCheck->bind_result($exists);
$stmtCheck->fetch();
$stmtCheck->close();

// Si no existe, insertamos en la tabla vehiculo
if ($exists == 0) {
    $sqlInsertVehiculo = "INSERT INTO vehiculo (matricula, marca, modelo, propietario) VALUES (?, ?, ?, ?)";
    $stmtInsertVehiculo = $con->prepare($sqlInsertVehiculo);
    $stmtInsertVehiculo->bind_param("sssi", $matricula, $marca, $modelo, $propietario);

    if (!$stmtInsertVehiculo->execute()) {
        die("Error al guardar vehículo: " . $stmtInsertVehiculo->error);
    }

    $stmtInsertVehiculo->close();
}

// Ahora, insertamos en la tabla registrovehiculo
$sqlInsertRegistro = "INSERT INTO registrovehiculo (matricula, entrada, idZona) VALUES (?, NOW(), ?)";
$stmtInsertRegistro = $con->prepare($sqlInsertRegistro);
$stmtInsertRegistro->bind_param("si", $matricula, $idZona);


if ($stmtInsertRegistro->execute()) {
    echo "Registro guardado exitosamente.";

    // Obtener el ID del último registro insertado
    $idRegistro = $stmtInsertRegistro->insert_id;

    // Insertar en la tabla Ticket usando el idRegistro recién obtenido
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

// Cerrar la declaración y la conexión
$stmtInsertRegistro->close();
$con->close();
?>
