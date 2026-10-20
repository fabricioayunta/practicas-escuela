<?php

session_start();

// Cierra solo la sesión de este módulo
// (la de Laboratorios y Pañol Informático sigue abierta)
unset($_SESSION["panol_id_usuario"]);
unset($_SESSION["panol_nombre"]);
unset($_SESSION["panol_apellido"]);
unset($_SESSION["panol_email"]);
unset($_SESSION["panol_id_rol"]);
unset($_SESSION["panol_csrf_token"]);

// Vuelve al login del módulo
header("Location: index.php");
exit();
?>
