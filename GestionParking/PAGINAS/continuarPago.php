<?php
if(!isset($_GET['matricula']))
{
    header('location: index.php');
}

$matricula = $_GET['matricula'];

include_once "FUNCIONES/funciones.php";
?>

<style>
    .ticket {
        width: 300px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #000;
        background-color: #f9f9f9;
        font-family: 'Courier New', Courier, monospace;
    }
    .ticket h3, .ticket p {
        text-align: center;
    }
    .ticket .total {
        font-weight: bold;
        font-size: 18px;
        text-align: center;
        margin-top: 20px;
    }
</style>

<div class="container mt-5">
    <div class="ticket">
        <?php

        $numTicket = obtenerNumeroTicket($matricula);

        $costo = calcularImporteTicket($matricula);

        $entrada = calcularFechaEntrada($matricula);
        $entrada = new DateTime($entrada);
        $salida = new DateTime(); // Hora y fecha actual

        $interval = $entrada->diff($salida);
        $horas = $interval->h;
        $horas += $interval->days * 24; // Añadir días completos como horas

        $nombreEdificio = obtenerNombreEdificioRegistro($matricula);

        $nombreZona = obtenerNombreZona($matricula);

        // Mostrar el ticket
        echo "<h2><center>Ticket de Parkímetro</center></h2>";
        echo "<h3><center>$nombreEdificio</center></h3>";
        echo "<h4><center>$nombreZona</center></h4>";
        echo "<p><strong>Ticket Nº: </strong> " . $numTicket . "</p>";
        echo "<p><strong>Matricula:</strong> " . $matricula . "</p>";
        echo "<p><strong>Entrada:</strong> " . $entrada->format('d-m-Y H:i') . "</p>";
        echo "<p><strong>Salida:</strong> " . $salida->format('d-m-Y H:i') . "</p>";
        echo "<p><strong>Duración:</strong> " . $horas . " hora(s)</p>";
        echo "<p class='total'>Costo Total: " . number_format($costo, 2) . "€</p>";
        ?>
    </div>

    <br>

    <div class="container mt-5 text-center">
        <form id="pagoForm" method="POST" onsubmit="return iniciarPago()">
            <button type="submit" name="pagar" class="btn btn-success btn-lg">Pagar</button>
        </form>
    </div>
</div>

<!-- Modal de procesamiento -->
<div class="modal fade" id="modalProcesandoPago" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Procesando pago...</h5>
            </div>
            <div class="modal-body text-center">
                <p>Por favor, espere mientras procesamos su pago.</p>
                <!-- Puedes reemplazar esta imagen con un GIF de procesamiento -->
                <img src="https://i.gifer.com/ZZ5H.gif" alt="Cargando..." style="width: 100px; height: 100px;">
            </div>
        </div>
    </div>
</div>


<!-- Incluye Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script para procesar el pago, mostrar el modal y redirigir después de imprimir -->
<script>
    function iniciarPago() {
        // Mostrar el modal de procesamiento
        $('#modalProcesandoPago').modal('show');
        
        // Simular el tiempo de procesamiento y luego imprimir el ticket
        setTimeout(function() {
            $('#modalProcesandoPago').modal('hide'); // Ocultar el modal después de un tiempo
            imprimirTicket(); // Llamar a la función para imprimir el ticket
        }, 3000); // Simula 3 segundos de procesamiento

        return false; // Evita que el formulario se envíe inmediatamente
    }

    function imprimirTicket() {
    // Abre el diálogo de impresión del navegador
    window.print(); 

    // Asignar las variables PHP a variables JavaScript
    var numTicket = '<?php echo $numTicket; ?>';
    var costo = '<?php echo $costo; ?>';

    // Redirigir después de un pequeño retraso
    setTimeout(function() {
        // Redirige a la página con los valores de las variables PHP
        window.location.href = "index.php?page=procesarpago&numTicket=" + numTicket + "&costo=" + costo;
    }, 1000); // Espera 1 segundo antes de redirigir
}
</script>
