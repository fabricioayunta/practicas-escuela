<?php

session_start();

// Cierra solo la sesión de este módulo
// (la de Herramientas y Máquinas de Impresión 3D sigue abierta)
unset($_SESSION["id_usuario"]);
unset($_SESSION["nombre"]);
unset($_SESSION["apellido"]);
unset($_SESSION["email"]);
unset($_SESSION["id_rol"]);
unset($_SESSION["csrf_token"]);

// Vuelve al login
header("Location: index.php");
exit();
?>
