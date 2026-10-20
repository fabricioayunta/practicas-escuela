<?php

session_start();

// Cierra solo la sesión de este módulo
// (la de Laboratorios y Pañol sigue abierta)
unset($_SESSION["pi_id_usuario"]);
unset($_SESSION["pi_nombre"]);
unset($_SESSION["pi_apellido"]);
unset($_SESSION["pi_email"]);
unset($_SESSION["pi_id_rol"]);
unset($_SESSION["pi_csrf_token"]);

// Vuelve al login del módulo
header("Location: index.php");
exit();
?>
