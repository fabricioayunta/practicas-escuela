<?php

session_start();

// Cierra solo la sesión de este módulo
unset($_SESSION["id_usuario"]);
unset($_SESSION["nombre"]);
unset($_SESSION["apellido"]);
unset($_SESSION["email"]);
unset($_SESSION["id_rol"]);

// Vuelve al login del módulo
header("Location: index.php");
exit();
?>
