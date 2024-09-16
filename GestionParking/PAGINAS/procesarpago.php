<?php

include_once "FUNCIONES/funciones.php";

$numTicket=$_GET['numTicket'];
$costo=$_GET['costo'];

marcarTicketPagado($numTicket,number_format($costo, 2));

header('location: index.php?page=mistickets');

?>