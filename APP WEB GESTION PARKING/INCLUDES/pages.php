<?php

    //No se puede iniciar sesión si ya se ha iniciado
    if(isset($_SESSION['usuario']))
        if(isset($_GET['page']))
            if($_GET['page'] == "login")
                header('Location: index.php');

    if (isset($_GET['page']))
            include("PAGINAS/".$_GET['page'].".php");   
    else 
            include("PAGINAS/login_matricula.php");   

?>
