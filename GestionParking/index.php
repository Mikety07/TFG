<?php
ob_start();
date_default_timezone_set("Europe/Madrid");

//lo uso en muchas paginas
$anio = strftime("%Y", time());
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
//echo strftime("%m", time());
$mes = $meses[strftime("%m", time())-1] ;
?>

<!DOCTYPE html>
<html lang="es">

    <head>
        <?php include("INCLUDES/head.php"); ?>
    </head>

    <body>

        <header>
            <?php include("INCLUDES/menu.php"); ?>
        </header>

        <main>
            <?php include("INCLUDES/pages.php"); ?>
        </main>

        <footer>
            <?php include("INCLUDES/footer.php"); ?>
        </footer>

    </body>

</html>

<?php
ob_end_flush();
?>
