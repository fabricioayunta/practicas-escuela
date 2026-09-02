<?php

session_start();

// Cierra solo la sesión de este módulo
unset($_SESSION["pi_id_usuario"]);
unset($_SESSION["pi_nombre"]);
unset($_SESSION["pi_apellido"]);
unset($_SESSION["pi_email"]);

// Vuelve al login del módulo
header("Location: index.php");
exit();
?>
