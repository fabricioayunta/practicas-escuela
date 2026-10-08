<?php
/*
   Esta página solo envía a cada usuario a su panel según su rol.
   (Se conserva porque otras pantallas pueden enlazar a "inicio.php").
*/
session_start();

if (!isset($_SESSION["id_usuario"]) || !isset($_SESSION["id_rol"])) {
    header("Location: index.php?error=sesion");
    exit();
}

switch ((int)$_SESSION["id_rol"]) {

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
        session_unset();
        session_destroy();
        header("Location: index.php?error=rol");
}

exit();
