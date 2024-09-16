
<?php

if(!isset($_SESSION))
{
    header('location: index.php');
}

include_once "FUNCIONES/funciones.php";

$_SESSION['ultimoAcceso'] = revisarTiempoSesion($_SESSION['usuario'],$_SESSION['ultimoAcceso']);

if($_SESSION['rol'] == "administrador")
{
    // Conexión a la base de datos
    $dsn = 'mysql:host=localhost;dbname=parking;charset=utf8';
    $username = 'root';
    $password = '1234';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Conexión fallida: ' . $e->getMessage();
        exit;
    }

    // Consulta para obtener la ocupación por edificio y zona
    $sql = "
        SELECT e.nombre AS edificio, z.nombre AS zona, COUNT(rv.idRegistro) AS ocupacion, MAX(z.num_plazas) AS num_plazas
        FROM edificio e
        JOIN zona z ON e.idEdificio = z.idEdificio
        LEFT JOIN registrovehiculo rv ON z.idZona = rv.idZona AND rv.salida IS NULL
        GROUP BY e.nombre, z.nombre, z.idZona
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Arrays para almacenar los datos de la gráfica
    $edificios = [];
    $ocupaciones = [];
    $capacidad = [];

    foreach ($resultado as $fila) {
        $edificios[] = $fila['edificio'] . ' - ' . $fila['zona'];
        $ocupaciones[] = $fila['ocupacion'];
        $capacidad[] = $fila['num_plazas'];
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ocupación de los Edificios</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
        <div class="container mt-5">
            <h2 class="text-center">Ocupación de los Edificios por Zona</h2>
            <canvas id="ocupacionChart" width="400" height="200"></canvas>
        </div>

        <script>
            var ctx = document.getElementById('ocupacionChart').getContext('2d');
            var ocupacionChart = new Chart(ctx, {
                type: 'bar', // Tipo de gráfico: 'bar' para barras
                data: {
                    labels: <?php echo json_encode($edificios); ?>, // Nombres de las zonas
                    datasets: [{
                        label: 'Ocupación',
                        data: <?php echo json_encode($ocupaciones); ?>, // Datos de ocupación
                        backgroundColor: 'rgba(54, 162, 235, 0.6)', // Color de las barras de ocupación
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Capacidad',
                        data: <?php echo json_encode($capacidad); ?>, // Capacidad total de cada zona
                        backgroundColor: 'rgba(255, 99, 132, 0.6)', // Color de las barras de capacidad
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
        <?php
}
else if($_SESSION['rol']=="cliente")
{
    $registrosPendientes = obtenerRegistrosPendientesUsuario($_SESSION['usuario']);
    
    if($registrosPendientes>0)
    {
        $idUsuario = $_SESSION['usuario'];

        $nombreUsuario = obtenerNombreUsuarioID($idUsuario);
        $apellidosUsuario = obtenerApellUsuarioID($idUsuario);

        $con = conectarBD();
        $sql = "SELECT z.nombre, v.matricula, v.marca, v.modelo, r.entrada, r.salida, t.importe
                FROM `registrovehiculo` r, `edificio` e, `zona` z, `vehiculo` v, `ticket` t
                WHERE r.salida IS NULL AND r.idZona=z.idZona AND z.idEdificio=e.idEdificio AND r.matricula=v.matricula AND t.idRegistro=r.idRegistro AND v.propietario=$idUsuario";
        $resultado_BD = mysqli_query($con, $sql);
        ?>

        <div class="container mt-5">
            <h4 class="text-center mb-4"><?php echo "$nombreUsuario $apellidosUsuario, estos son tus tickets pendientes de pago:"; ?></h4>
            <!-- Información del Edificio -->
            <div class="card">
                <div class="card-body d-flex">
                    <!-- Tabla -->
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Zona</th>
                                <th>Matrícula</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody id="edificioTable">
                            <?php
                            while ($fila_consulta = $resultado_BD->fetch_array(MYSQLI_ASSOC))
                            {
                                $zona = $fila_consulta['nombre'];
                                $matricula = $fila_consulta['matricula'];
                                $marca = ($fila_consulta['marca']=="") ? "---" : $fila_consulta['marca'];
                                $modelo = ($fila_consulta['modelo']=="") ? "---" : $fila_consulta['modelo'];
                                $entrada = $fila_consulta['entrada'];
                                $salida = ($fila_consulta['salida']=="") ? "---" : $fila_consulta['salida'];
                                $importe = ($fila_consulta['importe'])=="" ? "---" : $fila_consulta['importe'];
                                echo "<tr>";
                                echo "<td>$zona</td>";
                                echo "<td>$matricula</td>";
                                echo "<td>$marca</td>";
                                echo "<td>$modelo</td>";
                                echo "<td>$entrada</td>";
                                echo "<td>$salida</td>";
                                echo "<td>$importe</td>";
                                echo "</tr>";
                            }
                            $con -> close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }
    else
    {
        echo '
            <div class="container mt-5">
            <div class="alert alert-info text-center mt-4" role="alert">
                <strong>No existen tickets pendientes de pago</strong>
            </div></div>
            ';
    }
}

?>
