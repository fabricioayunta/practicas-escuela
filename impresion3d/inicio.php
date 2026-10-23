<?php
/*
   Esta página solo envía a cada usuario a su panel según su rol.
   (Igual que en Laboratorios).
*/
session_start();

if (!isset($_SESSION["pi_id_usuario"]) || !isset($_SESSION["pi_id_rol"])) {
    header("Location: index.php?error=sesion");
    exit();
}

switch ((int)$_SESSION["pi_id_rol"]) {

    case 1:
        header("Location: administrativo/inicio.php");
        break;

    case 2:
        header("Location: profesor/inicio.php");
        break;

    case 3:
        header("Location: ematp/inicio.php");
        break;

    default:
        header("Location: logout.php");
}

exit();
